<?php
if (!function_exists('formatDateThai')) {
    function formatDateThai($date) {
        $thaiMonths = [
            "", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", 
            "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
        ];
        $day = date("j", strtotime($date)); // วันที่ (1-31)
        $month = $thaiMonths[date("n", strtotime($date))]; // ชื่อเดือนภาษาไทย
        $year = date("Y", strtotime($date)) + 543; // แปลงปี ค.ศ. เป็น พ.ศ.

        return "$day $month $year";
    }
}
?>
