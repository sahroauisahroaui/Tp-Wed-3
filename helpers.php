<?php
/**
 * ملف المساعدات التقنية (Helpers)
 * يحتوي على دوال عامة تستخدم في كامل المشروع
 */

// الخطوة 8: دالة الحماية من هجمات XSS
function clean($data) {
    if (is_null($data)) return "";
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// يمكنك إضافة دالة أخرى لتغيير تنسيق التاريخ مثلاً (مهمة إضافية)
function formatDate($date) {
    return date('Y-m-d', strtotime($date));
}
?>

