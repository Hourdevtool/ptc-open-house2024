

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลการจอง</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: white;
        }
        .container {
            max-width: 800px;
        }
        .btn-custom {
            background-color: #dc3545; /* สีแดง */
            color: white;
        }
        .btn-custom:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<?php
// เชื่อมต่อฐานข้อมูล
require_once 'conn.php';

// ตรวจสอบว่า ID ของการจองถูกส่งมาหรือไม่
if (!isset($_GET['id'])) {
    echo "ไม่พบข้อมูล!";
    exit;
}

// รับค่า preorder_id จาก URL
$preorder_id = $_GET['id'];

// ดึงข้อมูลการจองจาก tb_checkopen
$query = "SELECT co.*, d.date_open FROM tb_checkopen co 
          JOIN tb_date d ON co.date_id = d.date_id
          WHERE co.preorder_id = :preorder_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':preorder_id', $preorder_id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "ไม่พบข้อมูลที่ต้องการแก้ไข!";
    exit;
}

// ดึงข้อมูลจาก tb_date สำหรับ select
$query_dates = "SELECT date_id, date_open FROM tb_date";
$stmt_dates = $conn->query($query_dates);
$dates = $stmt_dates->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $school = $_POST['school'];
    $member_name = $_POST['member_name'];
    $phone_number = $_POST['phone_number'];
    $quandity = $_POST['quandity'];
    $date_id = $_POST['date_id'];

    // อัปเดตข้อมูลใน tb_checkopen
    $update_query = "UPDATE tb_checkopen SET school = :school, member_name = :member_name, 
                     phone_number = :phone_number, quandity = :quandity, date_id = :date_id 
                     WHERE preorder_id = :preorder_id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':school', $school);
    $update_stmt->bindParam(':member_name', $member_name);
    $update_stmt->bindParam(':phone_number', $phone_number);
    $update_stmt->bindParam(':quandity', $quandity);
    $update_stmt->bindParam(':date_id', $date_id);
    $update_stmt->bindParam(':preorder_id', $preorder_id);
    
    if ($update_stmt->execute()) {
        echo "<script type='text/javascript'>
        Swal.fire({
         title: 'สำเร็จ!',
        text: 'แก้ไขข้อมูลสำเร็จ',
         icon: 'success',
         confirmButtonText: 'ตกลง'
         }).then((result) => {
                   if (result.isConfirmed) {
                       window.location.href='../admin/dashboard.php';
                   }
               });
             </script>";
        exit;
    } else {
        echo "<script type='text/javascript'>
        Swal.fire({
         title: 'เกิดข้อผิดพลาด!',
        text: 'ไม่สามารถแก้ไขข้อมูลได้',
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
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">แก้ไขข้อมูลการจอง</h2>

    <!-- ฟอร์มแก้ไขข้อมูล -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="school" class="form-label">ชื่อโรงเรียน</label>
            <input type="text" class="form-control" id="school" name="school" value="<?php echo htmlspecialchars($data['school']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="member_name" class="form-label">ชื่อผู้ประสานงาน</label>
            <input type="text" class="form-control" id="member_name" name="member_name" value="<?php echo htmlspecialchars($data['member_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">เบอร์ติดต่อ</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($data['phone_number']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="quandity" class="form-label">จำนวนผู้ลงทะเบียน</label>
            <input type="number" class="form-control" id="quandity" name="quandity" value="<?php echo htmlspecialchars($data['quandity']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="date_id" class="form-label">วันที่เปิด</label>
            <select class="form-select" id="date_id" name="date_id" required>
                <option value="">เลือกวันที่</option>
                <?php foreach ($dates as $date): ?>
                    <option value="<?php echo $date['date_id']; ?>" <?php echo ($data['date_id'] == $date['date_id']) ? 'selected' : ''; ?>>
                        <?php echo $date['date_open']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-custom w-100">อัปเดตข้อมูล</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
