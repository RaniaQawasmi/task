<?php
require_once __DIR__ . '/../Repositories/StudentRepository.php';

class StudentService {

    private $repository;

    public function __construct() {
        $this->repository = new StudentRepository();
    }

    public function register($post, $files) {

        if ($this->repository->emailExists($post['email'])) {
            throw new Exception("This email is already registered");
        }

        $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);

        $post['subjects'] = isset($post['subjects'])
            ? implode(", ", $post['subjects'])
            : "";

        $post['photo'] = $this->uploadPhoto($files);

        return $this->repository->create($post);
    }

    public function login($email, $password) {

        $user = $this->repository->findByEmail($email);

        if (!$user) {
            throw new Exception("Email not found");
        }

        if (!password_verify($password, $user['password'])) {
            throw new Exception("Wrong password");
        }

        return $user;
    }

    private function uploadPhoto($files) {

        if (!isset($files['photo']) || $files['photo']['error'] !== 0) {
            return "";
        }

        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $photo_name = time() . "_" . basename($files["photo"]["name"]);
        move_uploaded_file($files["photo"]["tmp_name"], $target_dir . $photo_name);

        return $photo_name;
    }

    public function getById($id) {
        return $this->repository->findById($id);
    }

    public function update($id, $post, $files) {

        if (!empty($files['photo']['name'])) {
            $post['photo'] = $this->uploadPhoto($files);
        } else {
            $post['photo'] = $this->repository->findById($id)['photo'];
        }

        return $this->repository->update($id, $post);
    }

}
