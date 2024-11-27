<?php
session_start(); // เริ่ม session

// ตรวจสอบคุกกี้เพื่อเข้าสู่ระบบอัตโนมัติ
if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    require_once 'command/conn.php'; // เชื่อมต่อฐานข้อมูล

    // รับค่าจากคุกกี้
    $username = $_COOKIE['username'];
    $password = $_COOKIE['password'];

    // ตรวจสอบข้อมูลในฐานข้อมูล
    $result = $conn->prepare("SELECT * FROM tb_user WHERE member_code = :username LIMIT 1");
    $result->bindParam(':username', $username);
    $result->execute();
    $query = $result->fetch(PDO::FETCH_ASSOC);

    if ($query && password_verify($password, $query['password'])) {
        // เก็บข้อมูลใน session
        $_SESSION['member_code'] = $query['member_code'];
        $_SESSION['member_id'] = $query['member_id'];
        $_SESSION['member_fname'] = $query['member_fname'];
        $_SESSION['member_lname'] = $query['member_lname'];
        $_SESSION['member_allow'] = $query['member_allow'];

        // หากเข้าสู่ระบบสำเร็จ
        if ($_SESSION['member_allow'] == 1) {
            header("Location: admin/dashboard.php");
            exit();
        }
    }
}

require_once "command/conn.php";
require_once 'command/function.php';

// ดึงข้อมูลวันที่และการจอง
$sql = "
    SELECT d.date_id, d.date_open, d.max_value, d.start_time, d.end_time, d.date_round,
           COALESCE(SUM(c.quandity), 0) AS total_quantity
    FROM tb_date d
    LEFT JOIN tb_checkopen c ON d.date_id = c.date_id
    WHERE d.date_open >= CURDATE()
    GROUP BY d.date_id, d.date_open, d.max_value, d.start_time, d.end_time, d.date_round
    ORDER BY d.date_open ASC, d.date_round ASC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);



// จัดกลุ่มผลลัพธ์ตาม date_open
$groupedResults = [];
foreach ($results as $row) {
    $groupedResults[$row['date_open']][] = $row;
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open House 2024</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style-5.css">
</head>
<body>
<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container-fluid">
    <a class="navbar-brand fs-3" href="#">Open House</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto" >
        <li class="nav-item" >
          <a class="nav-link" href="index.php">ลงเวลาเข้างาน</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="examine.php">ตรวจสอบการจอง</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            เพิ่มเติม
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style=' background-color: #9C27B0;'>
            <li><a class="dropdown-item" href="signin.php">Login</a></li>
            
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>



<header>
    <div>
        <img src="img/banner.jpg" style="width: 100%;object-fit: cover;" alt="Header Image">
        
    </div>
    <div class="line"></div>
    <h1 style=' color: #88cc14;'class='text-center mb-3'>📢ลงทะเบียนที่นี่</h1>
    <div class="container me-5" >
        <div class="row">
            <div class="col-md-12">
                        <h4 class="mb-3 d-flex align-items-start text-white ms-5">
                            <span class="me-2">1.</span> 
                            <span>ในแต่ละรอบผู้เข้าร่วมจะได้เรียนรู้ครบทั้ง 5 โซน ต่อ 1 รอบ (เช้า/บ่าย)</span>
                        </h4>
                
                <h4 class="mb-3 d-flex align-items-start text-white ms-5">
                    <span class="me-2">2.</span> <!-- หมายเลข 2 -->
                    <span>โซนการเรียนรู้ 1 โซน สามารถรองรับผู้เข้าร่วมได้สูงสุด จำนวน 80 คน ต่อรอบ (เช้า/บ่าย)</span>
                </h4>
                <h4 class="mb-3 d-flex align-items-start text-white ms-5">
                    <span class="me-2">3.</span> <!-- หมายเลข 3 -->
                    <span>ระยะเวลาในการจัดกิจกรรม</span>
                </h4>
                <h4 class="mb-5  text-start ps-3 ms-5" style=' color: #88cc14;'>
                    รอบเช้า เวลา 8.30 - 11.30 น.<br>
                    รอบบ่าย เวลา 13.00 - 16.00 น.
                </h4>
            </div>
        </div>
        </div>

   
  
        
                 
</header>
<?php
foreach ($groupedResults as $date_open => $rows) {
    $morningFull = $afternoonFull = false;

    foreach ($rows as $row) {
        if ($row['date_round'] === 'เช้า' && $row['total_quantity'] >= $row['max_value']) {
            $morningFull = true;
        } elseif ($row['date_round'] === 'บ่าย' && $row['total_quantity'] >= $row['max_value']) {
            $afternoonFull = true;
        }
    }

    echo "<div class='con'>";
    echo "<h1 style='color:  #88cc14;'>วันที่: " . formatDateThai($date_open) . "</h1>";

    foreach ($rows as $row) {
        $date_id = $row['date_id'];
        $max_value = $row['max_value'];
        $start_time = $row['start_time'];
        $end_time = $row['end_time'];
        $date_round = $row['date_round'];
        $total_quantity = $row['total_quantity'];

        if ($date_round === 'เช้า') {
            if ($morningFull) {
                echo "<div class='max' style='cursor: no-drop;'>";
                echo "<p class='text'><span style='color:#FFF;'>เต็มแล้ว</span><span class='sub-text'>📢</span></p>";
                echo "<p class='detail-text' style='color:#FFF; cursor: no-drop;'>ขณะนี้ที่นั่งเต็มแล้ว</p>";
                echo "</div>";
            } else {
                    echo "<a href='command/pre.php?date_id=$date_id' style='text-decoration:none;'>";
                    echo "<div class='morning'>";
                    echo "<p class='time-text'><span>$total_quantity/$max_value</span><span class='time-sub-text'>คน</span></p>";
                    echo "<div class='pg-bar mb-3 px-3'> 
                            <div class='progress mb-3' style='border-radius: 10px; overflow: hidden;'>
                                <div class='progress-bar progress-bar-striped bg-info' role='progressbar' style='width: " . ($total_quantity / $max_value * 100) . "%; transition: width 0.5s ease-in-out;' aria-valuenow='" . ($total_quantity) . "' aria-valuemin='0' aria-valuemax='" . ($max_value) . "'></div>
                            </div> 
                        </div>";
                    echo "<p class='day-text'>รอบเช้า ($start_time - $end_time)</p>";

                    echo "</div></a>";
            }
        } elseif ($date_round === 'บ่าย') {
            if ($afternoonFull) {
                echo "<div class='max'>";
                echo "<p class='text'><span>เต็มแล้ว</span><span class='sub-text'>📢</span></p>";
                echo "<p class='detail-text'>ขณะนี้ที่นั่งเต็มแล้ว</p>";
                echo "</div>";
            } else {
                echo "<a href='command/pre.php?date_id=$date_id' style='text-decoration:none;'>";
                echo "<div class='afternoon'>";
                echo "<p class='time-text'><span>$total_quantity/$max_value</span><span class='time-sub-text'>คน</span></p>";
                echo "<div class='pg-bar mb-3 px-3'> 
                            <div class='progress mb-3' style='border-radius: 10px; overflow: hidden;'>
                                <div class='progress-bar progress-bar-striped bg-info' role='progressbar' style='width: " . ($total_quantity / $max_value * 100) . "%; transition: width 0.5s ease-in-out;' aria-valuenow='" . ($total_quantity) . "' aria-valuemin='0' aria-valuemax='" . ($max_value) . "'></div>
                            </div> 
                        </div>";
                echo "<p class='day-text'>รอบบ่าย ($start_time - $end_time)</p>";

                echo "</div></a>";
            }
        }
    }
    echo "</div>";
}
?>

<footer class="custom-footer">
    <div class="container text-center py-3">
        <p class="mb-3"><span class="highlight fs-3">&nbsp; ติดต่อสอบถาม</span></p>
        <p class="mb-1">นางสาววสุธารา หมื่นโฮ้ง (น้องกระปุก)<span class="highlight">&nbsp; 087-361-5563</span></p>
        <p class="mb-0">นางสาวเกษกรจันทร์ วันมหาใจ (น้องสา)  <span class="highlight"> &nbsp; 087-545-9306</span></p>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
