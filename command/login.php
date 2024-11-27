<?php 
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    require_once 'conn.php';
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
 
    $result = $conn->prepare("SELECT * FROM tb_user WHERE member_code = :username LIMIT 1");
    $result->bindParam(':username', $username);
    $result->execute();
    $query = $result->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าพบข้อมูลหรือไม่
    if ($query) {
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $query['password'])) {

            $_SESSION['member_id'] = $query['member_id'];
            $_SESSION['member_allow'] = $query['member_allow'];
            $_SESSION['member_fname'] = $query['member_fname'];
            $_SESSION['member_lname'] = $query['member_lname'];
            $_SESSION['type'] = $query['type'];

            // ถ้ามีการเลือก "จดจำฉัน"
            if (isset($_POST['remember_me'])) {
                // ตั้งค่าคุกกี้ให้หมดอายุใน 30 วัน
                setcookie('username', $username, time() + (86400 * 30), "/"); // 86400 = 1 วัน
                setcookie('password', $password, time() + (86400 * 30), "/"); // 86400 = 1 วัน
            } else {
                // ถ้าไม่ได้เลือก จะแค่เริ่ม session ปกติ
                setcookie('username', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }

           
            if ($_SESSION['member_allow'] == 1) {
                echo "<script>alert('เข้าสู่ระบบสำเร็จ');</script>";
                echo "<script>window.location.href='../admin/dashboard.php'</script>";
            } else {
                echo "<script>alert('ขออภัยกรุณาติดต่อ admin');</script>";
                echo "<script>window.location.href='../dashboard.php';</script>";
            }

        } else {
            echo "<script>alert('ขออภัย รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง');</script>";
            echo "<script>window.location.href='../index.php';</script>";
        }
    } else {
        echo "<script>alert('ไม่พบชื่อผู้ใช้นี้ในระบบ');</script>";
        echo "<script>window.location.href='../index.php';</script>";
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>