<?php
// Auth & Admin Logic
class Controller {
    // تسجيل الدخول (صورة 1)
    public function login($email, $password) {
        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
        return false;
    }

    // حفظ المادة (صورة 3)
    public function saveCourse($name, $credits, $semId, $id = null) {
        if ($credits <= 0) return "Error: Credits must be positive";
        return $id ? Course::update($id, $name, $credits, $semId) : Course::create($name, $credits, $semId);
    }

    // إدارة تسجيل الطلاب (صورة 5)
    public function syncEnrollments($studentId, $newSemesterIds) {
        $currentIds = Enrollment::getSemesterIds($studentId);
        
        // إضافة الفصول الجديدة
        foreach(array_diff($newSemesterIds, $currentIds) as $id) {
            Enrollment::create($studentId, $id);
        }
        
        // حذف الفصول القديمة بشرط عدم وجود درجات (صورة 5 خطوة 4)
        foreach(array_diff($currentIds, $newSemesterIds) as $id) {
            if (Grade::countByStudentSemester($studentId, $id) == 0) {
                Enrollment::delete($studentId, $id);
            }
        }
    }
}

