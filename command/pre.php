<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ฟอร์มลงทะเบียน</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/form-style.css"> <!-- ไฟล์ CSS แยก -->
</head>
<body>
<?php
    // เชื่อมต่อฐานข้อมูล
    require_once 'conn.php';
    require_once 'function.php';
    

    // ตรวจสอบว่า date_id ถูกส่งมาหรือไม่
    if (!isset($_GET['date_id'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่พบข้อมูลรอบเวลา!'
            }).then(() => window.location = 'index.php');
        </script>";
        exit();
    }

    $date_id = htmlspecialchars($_GET['date_id']);

            $date = $conn->prepare("SELECT date_open FROM tb_date WHERE date_id = :id");
            $date->bindParam(":id", $date_id, PDO::PARAM_INT);
            $date->execute();
            $date_open = $date->fetch(PDO::FETCH_ASSOC);

             $date_thai = $date_open['date_open'];

    // ดึงข้อมูลรอบวันที่จาก tb_date
    $query = $conn->prepare("SELECT * FROM tb_date WHERE date_id = :date_id");
    $query->bindParam(":date_id", $date_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ข้อมูลรอบเวลาไม่ถูกต้อง!'
            }).then(() => window.location = 'index.php');
        </script>";
        exit();
    }

    // ตรวจสอบเมื่อมีการส่งข้อมูลผ่านฟอร์ม
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $school = htmlspecialchars($_POST['school']);
        $member_name = htmlspecialchars($_POST['member_name']);
        $phone_number = htmlspecialchars($_POST['phone_number']);
        $email = htmlspecialchars($_POST['email']);
        $quantity = (int) $_POST['quantity'];
        $date_id = (int) $_POST['date_id'];
        $car_service = isset($_POST['shuttle_service']) ? 1 : 0; // กำหนดค่าเป็น 1 หากเลือก checkbox
    
        // ดึงข้อมูลรอบเวลาเพื่อตรวจสอบ max_value
        $query = $conn->prepare(
            "SELECT max_value, 
                    (SELECT IFNULL(SUM(quandity), 0) 
                     FROM tb_checkopen 
                     WHERE date_id = :date_id) AS total_registered 
             FROM tb_date 
             WHERE date_id = :date_id"
        );
        $query->bindParam(":date_id", $date_id, PDO::PARAM_INT);
        $query->execute();
        $date_info = $query->fetch(PDO::FETCH_ASSOC);
    
        if (!$date_info) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ข้อมูลรอบเวลาไม่ถูกต้อง!'
                }).then(() => window.history.back());
            </script>";
            exit();
        }
    
        $max_value = $date_info['max_value'];
        $total_registered = $date_info['total_registered'];
    
        // ตรวจสอบจำนวนที่นั่ง
        if ($quantity + $total_registered > $max_value) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'จำนวนเต็มแล้ว',
                    text: 'ไม่สามารถเพิ่มข้อมูลได้ เนื่องจากเกินจำนวนสูงสุด!'
                }).then(() => window.history.back());
            </script>";
            exit();
        }
    
        // เพิ่มข้อมูลใน tb_checkopen
        $insert_query = $conn->prepare(
            "INSERT INTO tb_checkopen (school, member_name, phone_number, email, date_id, quandity, car_service) 
             VALUES (:school, :member_name, :phone_number, :email, :date_id, :quandity,:car_service)"
        );
        $insert_query->bindParam(":school", $school);
        $insert_query->bindParam(":member_name", $member_name);
        $insert_query->bindParam(":phone_number", $phone_number);
        $insert_query->bindParam(":email", $email);
        $insert_query->bindParam(":date_id", $date_id, PDO::PARAM_INT);
        $insert_query->bindParam(":quandity", $quantity, PDO::PARAM_INT);
        $insert_query->bindParam(":car_service", $car_service, PDO::PARAM_INT);
    
        if ($insert_query->execute()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: 'เพิ่มข้อมูลเรียบร้อยแล้ว!'
                }).then(() => window.location = '../index.php');
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถเพิ่มข้อมูลได้!'
                }).then(() => window.history.back());
            </script>";
        }
    }
    
    ?>
    <div class="container mt-5">
    <h2 class="text-center text-primary mb-4">ฟอร์มลงทะเบียน</h2>
    <h2 class="text-center text-primary mb-4"><?php  echo 'วันที่ลงทะเบียน'.formatDateThai($date_thai)?></h2>
    
    <form action="" method="POST" id="preForm" class="p-4 bg-light shadow rounded">
        <input type="hidden" name="date_id" value="<?= $date_id ?>">
        <div class="mb-3">
            <label for="school" class="form-label">โรงเรียน:</label>
            <input type="text" name="school" id="school" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="member_name" class="form-label">ผู้ประสานงาน:</label>
            <input type="text" name="member_name" id="member_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">หมายเลขโทรศัพท์:</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">จำนวน:</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
        </div>
        <div class="form-check mb-3">
                <input type="checkbox" name="shuttle_service" id="shuttle_service" class="form-check-input">
                <label for="shuttle_service" class="form-check-label">ต้องการรถรับส่ง (เฉพาะเขตในตัวเมือง)</label>
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-3">ยืนยัน</button>
        <a href="../index.php" class="btn btn-danger w-100">ย้อนกลับ</a>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
