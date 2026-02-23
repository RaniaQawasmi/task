<?php
require_once __DIR__ . '/../Services/StudentService.php';



class StudentController {

    private $service;

    public function __construct() {
        $this->service = new StudentService();
    }

    
    // تسجيل دخول

    public function login($email, $password) {
        return $this->service->login($email, $password);
    }

   
    // تسجيل طالب جديد
 
    public function register($post, $files) {
        return $this->service->register($post, $files);
    }

   
    // جلب بيانات الطالب بالـ ID

    public function getById($id) {
        return $this->service->getById($id);
    }

    
    // تحديث بيانات الطالب
  
    public function update($id, $post, $files) {
        return $this->service->update($id, $post, $files);
    }
}
