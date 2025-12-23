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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open House 2024</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Modern Theme CSS -->
    <link rel="stylesheet" href="css/modern-theme.css">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-rocket me-2"></i>PTC OPEN HOUSE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">ลงเวลาเข้างาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="examine.php">ตรวจสอบการจอง</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            เพิ่มเติม
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="signin.php"><i class="fas fa-sign-in-alt me-2"></i>สำหรับเจ้าหน้าที่</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <header class="header-section">
        <div>
            <img src="img/banner2025.jpg" class="banner-img" alt="Header Image">
        </div>
        
        <div class="container mt-5">
            <h1 class="section-title">📢 ลงทะเบียนเข้าร่วมกิจกรรม</h1>
            
            <div class="info-text-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-item">
                            <span class="info-number">1</span>
                            <span>ในแต่ละรอบผู้เข้าร่วมจะได้เรียนรู้ครบทั้ง 8 ฐานการเรียนรู้ต่อ 1 รอบ (เช้า/บ่าย)</span>
                        </div>

                        <div class="info-item">
                            <span class="info-number">2</span>
                            <span>ฐานการเรียนรู้ 1 ฐาน สามารถรองรับผู้เข้าร่วมได้สูงสุด จำนวน 20 คนต่อฐาน รวม 160 คนต่อรอบ (เช้า/บ่าย)</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-number">3</span>
                            <span>ระยะเวลาในการจัดกิจกรรม <br>รอบเช้า 08.00 - 11.20 น. | รอบบ่าย 13.00 - 16.20 น.</span>
                        </div>
                        
                        <div class="station-list">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="station-item">ฐานที่ 1 ภารกิจพิชิตท้องฟ้า</div>
                                    <div class="station-item">ฐานที่ 2 วาดเล่นเป็นกันเอง</div>
                                    <div class="station-item">ฐานที่ 3 ไม้แพร่ แกะใจ</div>
                                    <div class="station-item">ฐานที่ 4 มือระเบิดพลังงาน Power Puncher</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="station-item">ฐานที่ 5 กด สั่ง คอมพิวเตอร์ คิด ทำ</div>
                                    <div class="station-item">ฐานที่ 6 BOB the Builder</div>
                                    <div class="station-item">ฐานที่ 7 ถิ่นกำเนิดเกิดเทคโนโลยี(EN)</div>
                                    <div class="station-item">ฐานที่ 8 Driving Simulation</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Section -->
    <div class="container mb-5">
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

            echo "<div class='timeline-container'>";
            echo "<div class='date-group'>";
            
            // Date Title
            echo "<div class='date-title'><i class='far fa-calendar-alt me-2'></i>วันที่: " . formatDateThai($date_open) . "</div>";
            
            echo "<div class='card-wrapper'>";

            foreach ($rows as $row) {
                $date_id = $row['date_id'];
                $max_value = $row['max_value'];
                $start_time = $row['start_time'];
                $end_time = $row['end_time'];
                $date_round = $row['date_round'];
                $total_quantity = $row['total_quantity'];
                
                // Calculate percentage
                $percent = ($total_quantity / $max_value) * 100;
                $isFull = ($total_quantity >= $max_value);
                
                if ($date_round === 'เช้า') {
                    $disabled = $morningFull ? 'disabled' : '';
                    $link = $morningFull ? '#' : "command/pre.php?date_id=$date_id";
                    $statusText = $morningFull ? 'เต็มแล้ว' : "$total_quantity/$max_value";
                    $statusClass = $morningFull ? 'full' : 'open';
                    $progressColor = $morningFull ? 'progress-bar-warning' : 'progress-bar-custom';
                    
                    echo "<a href='$link' class='text-decoration-none'>";
                    echo "<div class='time-card $disabled'>";
                    echo "<div class='card-footer-text mb-2'><span class='status-badge $statusClass'>$date_round</span><span>$start_time - $end_time น.</span></div>";
                    
                    if($morningFull) {
                        echo "<div class='card-status' style='color: #ff512f;'>Full <span class='sub'>ที่นั่งเต็ม</span></div>";
                    } else {
                        echo "<div class='card-status'>$total_quantity <span class='sub'>/ $max_value คน</span></div>";
                    }
                    
                    echo "<div class='progress-container'>";
                    echo "<div class='$progressColor' style='width: {$percent}%'></div>";
                    echo "</div>";
                    
                    echo "<div class='text-end text-white-50'><small>คลิกเพื่อลงทะเบียน</small> <i class='fas fa-arrow-right ms-1'></i></div>";
                    echo "</div>";
                    echo "</a>";

                } elseif ($date_round === 'บ่าย') {
                    $disabled = $afternoonFull ? 'disabled' : '';
                    $link = $afternoonFull ? '#' : "command/pre.php?date_id=$date_id";
                    $statusText = $afternoonFull ? 'เต็มแล้ว' : "$total_quantity/$max_value";
                    $statusClass = $afternoonFull ? 'full' : 'open';
                    $progressColor = $afternoonFull ? 'progress-bar-warning' : 'progress-bar-custom';

                    echo "<a href='$link' class='text-decoration-none'>";
                    echo "<div class='time-card $disabled'>";
                    echo "<div class='card-footer-text mb-2'><span class='status-badge $statusClass'>$date_round</span><span>$start_time - $end_time น.</span></div>";
                    
                    if($afternoonFull) {
                         echo "<div class='card-status' style='color: #ff512f;'>Full <span class='sub'>ที่นั่งเต็ม</span></div>";
                    } else {
                        echo "<div class='card-status'>$total_quantity <span class='sub'>/ $max_value คน</span></div>";
                    }
                    
                    echo "<div class='progress-container'>";
                    echo "<div class='$progressColor' style='width: {$percent}%'></div>";
                    echo "</div>";
                    
                    echo "<div class='text-end text-white-50'><small>คลิกเพื่อลงทะเบียน</small> <i class='fas fa-arrow-right ms-1'></i></div>";
                    echo "</div>";
                    echo "</a>";
                }
            }
            echo "</div>"; // End card-wrapper
            echo "</div>"; // End date-group
            echo "</div>"; // End timeline-container
        }
        ?>
    </div>

    <footer class="modern-footer">
        <div class="container">
            <h5 class="text-uppercase mb-3" style="letter-spacing: 2px;">ติดต่อสอบถาม</h5>
            <div class="footer-contact">
                <i class="fas fa-user-circle me-1"></i> นางสาววสุธารา หมื่นโฮ้ง (น้องกระปุก) <span class="footer-highlight">087-361-5563</span>
            </div>
            <div class="footer-contact">
                 <i class="fas fa-user-circle me-1"></i> นางสาวเกษกรจันทร์ วันมหาใจ (น้องสา) <span class="footer-highlight">087-545-9306</span>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>