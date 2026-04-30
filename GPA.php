<?php
class GPA {
    public static function recompute($studentId, $semId) {
        $rows = Grade::getAllWithCredits($studentId, $semId);
        $totalPoints = 0;
        $totalCredits = 0;

        foreach ($rows as $row) {
            // مجموع (الدرجة × الأرصدة)
            $totalPoints += ($row['grade'] * $row['credits']);
            $totalCredits += $row['credits'];
        }

        if ($totalCredits > 0) {
            $gpa = $totalPoints / $totalCredits;
            // حفظ المعدل مقرباً لخانة واحدة أو خانتين
            GpaRecord::upsert($studentId, $semId, round($gpa, 2));
        }
    }
}

