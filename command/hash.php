
<?php
require_once 'conn.php';

// ดึงข้อมูลผู้ใช้ที่ยังไม่ได้เข้ารหัสรหัสผ่าน
$re = $conn->prepare("SELECT * FROM tb_user");
$re->execute();
$members = $re->fetchAll(PDO::FETCH_ASSOC);

foreach ($members as $member) {
    // ตรวจสอบว่ารหัสผ่านถูกเข้ารหัสหรือยัง
    if (!password_verify('test', $member['password']) && strlen($member['password']) < 60) { // assume <60 chars means not hashed
        
        $hashedPassword = password_hash($member['password'], PASSWORD_DEFAULT);

        // อัปเดตรหัสผ่านในฐานข้อมูล
        $update = $conn->prepare("UPDATE tb_user SET password = :hashedPassword WHERE member_id = :id");
        $update->bindParam(':hashedPassword', $hashedPassword);
        $update->bindParam(':id', $member['member_id']);
        $update->execute();
    }
}

echo "เข้ารหัสรหัสผ่านเรียบร้อยแล้ว";


?>