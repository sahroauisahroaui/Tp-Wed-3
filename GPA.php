<?php
class GPA {
    public static function recompute($studentId, $semId) {
        [span_4](start_span)// افترضنا وجود صنف Grade لجلب البيانات من قاعدة البيانات[span_4](end_span)
        $rows = Grade::getAllWithCredits($studentId, $semId); 
        $totalPoints = 0;
        $totalCredits = 0;

        foreach ($rows as $row) {
            $totalPoints += ($row['grade'] * $row['credits']); [span_5](start_span)//[span_5](end_span)
            $totalCredits += $row['credits']; [span_6](start_span)//[span_6](end_span)
        }

        if ($totalCredits > 0) {
            $gpa = $totalPoints / $totalCredits;
            [span_7](start_span)// تحديث أو إدخال السجل الجديد مقرباً لخانة واحدة[span_7](end_span)
            GpaRecord::upsert($studentId, $semId, round($gpa, 2));
        }
    }
}
?>
