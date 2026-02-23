<?php
require_once __DIR__ . '/../Core/Database.php';

class StudentRepository {

    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    // ==========================================
    // التحقق إذا الإيميل موجود مسبقًا
    // ==========================================
    public function emailExists($email) {

        $stmt = $this->conn->prepare(
            "SELECT id FROM students WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        $exists = $stmt->num_rows > 0;

        $stmt->close();
        return $exists;
    }

    // ==========================================
    // إنشاء طالب جديد
    // ==========================================
    public function create($data) {

        if (isset($data['subjects']) && is_array($data['subjects'])) {
            $data['subjects'] = implode(", ", $data['subjects']);
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO students 
            (full_name, email, password, phone, dob, gender, grade, schedule, subjects, notes, photo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssssssssss",
            $data['full_name'],
            $data['email'],
            $data['password'],
            $data['phone'],
            $data['dob'],
            $data['gender'],
            $data['grade'],
            $data['schedule'],
            $data['subjects'],
            $data['notes'],
            $data['photo']
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // ==========================================
    // جلب الطالب عن طريق الإيميل (login)
    // ==========================================
    public function findByEmail($email) {

        $stmt = $this->conn->prepare(
            "SELECT * FROM students WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        }

        $stmt->close();
        return null;
    }

    // ==========================================
    // جلب طالب عن طريق ID
    // ==========================================
    public function findById($id) {

        $stmt = $this->conn->prepare(
            "SELECT * FROM students WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $student = $result->fetch_assoc();

        $stmt->close();
        return $student;
    }

// ==========================================
// تحديث بيانات الطالب
// ==========================================
public function update($id, $data) {

    // ============================
    // التحقق من العمر (>= 5 سنوات)
    // ============================
    if (!empty($data['dob'])) {

        $dob = new DateTime($data['dob']);
        $today = new DateTime();
        $age = $today->diff($dob)->y;

        if ($age < 5) {
            throw new Exception("Student must be at least 5 years old");
        }
    }

    // تحويل subjects إذا كانت array
    if (isset($data['subjects']) && is_array($data['subjects'])) {
        $data['subjects'] = implode(", ", $data['subjects']);
    }

    $stmt = $this->conn->prepare(
        "UPDATE students SET 
            email = ?, 
            phone = ?, 
            dob = ?, 
            gender = ?, 
            grade = ?, 
            subjects = ?, 
            notes = ?, 
            photo = ?
        WHERE id = ?"
    );

    $stmt->bind_param(
        "ssssssssi",
        $data['email'],
        $data['phone'],
        $data['dob'],
        $data['gender'],
        $data['grade'],
        $data['subjects'],
        $data['notes'],
        $data['photo'],
        $id
    );

    $result = $stmt->execute();
    $stmt->close();

    return $result;
}


}
