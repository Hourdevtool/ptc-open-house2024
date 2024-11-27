
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php

    require_once 'conn.php';

    if (isset($_GET['date_id'])) {
        $date_id = $_GET['date_id'];
    
        // คำสั่ง SQL สำหรับลบข้อมูล
        $delete_query = $conn->prepare("DELETE FROM tb_date WHERE date_id =:date_id");
        $delete_query ->execute([':date_id'=> $date_id]);
    
        // รันคำสั่ง SQL
        if ($delete_query) {
            echo "<script type='text/javascript'>
            Swal.fire({
             title: 'สำเร็จ!',
            text: 'ลบข้อมูลสำเร็จ',
             icon: 'success',
             confirmButtonText: 'ตกลง'
             }).then((result) => {
                       if (result.isConfirmed) {
                           window.location.href='../admin/date_mange.php';
                       }
                   });
                 </script>";
        } else {
            echo "<script type='text/javascript'>
            Swal.fire({
             title: 'ผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการลบข้อมูล',
             icon: 'error',
             confirmButtonText: 'ตกลง'
             }).then((result) => {
                       if (result.isConfirmed) {
                           window.location.href='../admin/date_mange.php';
                       }
                   });
                 </script>";
        }
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    
        // คำสั่ง SQL สำหรับลบข้อมูล
        $delete_query = $conn->prepare("DELETE FROM tb_checkopen WHERE preorder_id =:id");
        $delete_query ->execute([':id'=> $id]);
    
        // รันคำสั่ง SQL
        if ($delete_query) {
            echo "<script type='text/javascript'>
            Swal.fire({
             title: 'สำเร็จ!',
            text: 'ลบข้อมูลสำเร็จ',
             icon: 'success',
             confirmButtonText: 'ตกลง'
             }).then((result) => {
                       if (result.isConfirmed) {
                           window.location.href='../admin/dashboard.php';
                       }
                   });
                 </script>";
        } else {
            echo "<script type='text/javascript'>
            Swal.fire({
             title: 'ผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการลบข้อมูล',
             icon: 'error',
             confirmButtonText: 'ตกลง'
             }).then((result) => {
                       if (result.isConfirmed) {
                           window.location.href='../admin/dashboard.php';
                       }
                   });
                 </script>";
        }
    }

    if (isset($_GET['member_id'])) {
        $member_id = $_GET['member_id'];
    
        // คำสั่ง SQL สำหรับลบข้อมูล
        $delete_query = $conn->prepare("DELETE FROM tb_user WHERE member_id =:id");
        $delete_query ->execute([':id'=> $member_id]);
    
        // รันคำสั่ง SQL
        if ($delete_query) {
            echo "<script type='text/javascript'>
            Swal.fire({
             title: 'สำเร็จ!',
            text: 'ลบข้อมูลสำเร็จ',
             icon: 'success',
             confirmButtonText: 'ตกลง'
             }).then((result) => {
                       if (result.isConfirmed) {
                           window.location.href='../admin/admin_mange.php';
                       }
                   });
                 </script>";
        } else {
            echo "<script type='text/javascript'>
            Swal.fire({
             title: 'ผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการลบข้อมูล',
             icon: 'error',
             confirmButtonText: 'ตกลง'
             }).then((result) => {
                       if (result.isConfirmed) {
                           window.location.href='../admin/admin_mange.php';
                       }
                   });
                 </script>";
        }
    }
?>

</body>
</html>