<?php
    session_start();
    require_once '../command/conn.php';
    require_once '../command/function.php';
    $sql = "SELECT * FROM tb_user WHERE type ='admin'";
    $result = $conn->prepare($sql);
    $result->execute();
    
    $fname = $_SESSION['member_fname'];
    $lname = $_SESSION['member_lname'];

    if (isset($_GET['search'])) {
        $search = $_GET['search'];
       
        
        $sql = "SELECT * FROM tb_user WHERE date_open = :search";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':search', $search);
        $stmt->execute();
    } else {
        $sql = "SELECT * FROM tb_user";
        $stmt = $conn->query($sql);
    }


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>สรุปผลรายวัน</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet"> 
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>Admin Home</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="../admin/img/user.png" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0"><?php echo "$fname  $lname"; ?></h6>
                <span>
                        <?php 
                            if (isset($_SESSION['type'])) {
                                echo ($_SESSION['type'] === 'sp_admin') ? 'Super Admin' : 'Admin';
                            } else {
                                echo 'Admin'; // ค่าดีฟอลต์หากไม่ได้ตั้งค่า type ใน session
                            }
                        ?>
                </span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="../admin/dashboard.php" class="nav-item nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <a href="../admin/report.php" class="nav-item nav-link active"><i class="fas fa-scroll"></i>report</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle " data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>จัดการ</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="../admin/date_mange.php" class="dropdown-item">เพิ่มวันที่</a>
                    <?php if (isset($_SESSION['type']) && $_SESSION['type'] === 'sp_admin') { ?>
                        <a href="../admin/admin_mange.php" class="dropdown-item ">เพิ่มadmin</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
</div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>

                <div class="navbar-nav align-items-center ms-auto">
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="img/user.png" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex"><?php echo "$fname  $lname"?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="../command/logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->
            
            <div class="container-fluid  mt-5 d-flex  justify-content-between ">
            <button onclick="printTable()" class="btn btn-primary">พิมพ์ตาราง</button>
            <!-- <a href='certificate.php' class="btn btn-primary">พิมพ์เกียรติบัตร</a> -->
            </div>
            <?php

$sql = "SELECT *
        FROM tb_date 
        ORDER BY date_open ASC, 
                 CASE 
                    WHEN date_round = 'เช้า' THEN 1 
                    WHEN date_round = 'บ่าย' THEN 2 
                    ELSE 3 
                 END ASC";
$stmt = $conn->query($sql);
$dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($dates) {
    foreach ($dates as $dateRow) {
           $dateId = $dateRow['date_id'];
        $dateOpen = $dateRow['date_open'];
        $dateRound = $dateRow['date_round'];
        $max_value = $dateRow['max_value'];
        $date_thai = formatDateThai($dateOpen);


        $sqlTotal = "SELECT SUM(quandity) AS total_quantity 
                        FROM tb_checkopen 
                        WHERE date_id = :date_id";
            $stmtTotal = $conn->prepare($sqlTotal);
            $stmtTotal->execute([':date_id' => $dateId]);
            $resultTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);

            $total_quantity = $resultTotal['total_quantity'] ?? 0; 

 

        echo '<div class="container-fluid pt-4 px-4  ">';
        echo '    <div class="bg-secondary text-center rounded p-4 report">';
        echo '        <div class="d-flex align-items-center justify-content-between mb-4  ">';
        echo "            <h6 class='mb-0'>สรุปผลข้อมูลประจำวันที่</h6>";
        echo "            <h6>$date_thai ($dateRound)</h6>";
        echo "            <h6>จำนวน: $total_quantity/$max_value</h6>";
        echo '        </div>';
        echo '        <div class="table-responsive">';
        echo '            <table class="table text-start align-middle table-bordered table-hover mb-0  table-custom ">';
        echo '                <thead>';
        echo '                    <tr class="text-white">';
        echo '                        <th scope="col" >ชื่อโรงเรียน</th>';
        echo '                        <th scope="col" >ชื่อ-นามสกุล</th>';
        echo '                        <th scope="col" >อีเมล</th>';
        echo '                        <th scope="col" >เบอร์โทรศัพท์</th>';
        echo '                        <th scope="col" >จำนวนที่จอง</th>';
        echo '                        <th scope="col" >รถรับส่ง</th>';
        echo '                    </tr>';
        echo '                </thead>';
        echo '                <tbody>';

        // Query เพื่อดึงข้อมูลการจองในแต่ละวันที่
        $sqlDetails = "SELECT school, member_name, email, phone_number, quandity,car_service
                       FROM tb_checkopen 
                       WHERE date_id = :date_id 
                       ORDER BY preorder_id ASC";
        $stmtDetails = $conn->prepare($sqlDetails);
        $stmtDetails->execute([':date_id' => $dateId]);
        $details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

        if ($details) {
            foreach ($details as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['school']) . '</td>';
                echo '<td>' . htmlspecialchars($row['member_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['phone_number']) . '</td>';
                echo '<td>' . htmlspecialchars($row['quandity']) . '</td>';
                echo '<td>' . ($row['car_service'] == 1 ? 'ต้องการรถรับส่ง' : 'ไม่ต้องการรถรับส่ง') . '</td>';
                echo '</tr>';
            }
        } else {
            // หากไม่มีข้อมูลในวันที่นั้น
            echo '<tr>';
            echo '<td colspan="6">ไม่มีข้อมูลในวันที่นี้</td>';
            echo '</tr>';
        }

        echo '                </tbody>';
        echo '            </table>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
} else {
    echo '<p>ไม่มีวันที่ที่เปิดให้ลงทะเบียน</p>';
}
?>

            <!-- Recent Sales End -->

    

            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">2024</a>, BY CTNPHRAE. 
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                          
                            Designed By <a href="#">CTN Team</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
      function printTable() {
    // เลือกทุกตารางที่ต้องการพิมพ์
    var tables = document.querySelectorAll(".report");
    var printWindow = window.open('', '', 'height=800,width=1000'); // สร้างหน้าต่างใหม่
    
    // เริ่มสร้างเอกสาร HTML สำหรับการพิมพ์
    printWindow.document.write('<html><head><title>Print Tables</title>');
    printWindow.document.write('<style>');
    printWindow.document.write(`
        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: white;
            }
            .table-container {
                page-break-after: always;
            }
            .table-custom {
                width: 100%;
                border-collapse: collapse;
                margin:0;
                background-color: white;
            }
            .table-custom th, .table-custom td {
                border: 1px solid black;
                padding: 8px;
                text-align: center;
                font-size: 14px;
            }
            .table-custom th {
                background-color: #343a40;
                color: white;
            }
            .table-header {
                background-color: #343a40;
                color: white;
                padding: 10px;
                font-size: 16px;
                text-align: left;
                margin-bottom: 10px;
                border-radius: 5px;
            }
        }
    `); // จัดสไตล์สำหรับการพิมพ์
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    
    // ดึงข้อมูลทุกตารางมาใส่ในหน้าต่างพิมพ์
    tables.forEach(function (table) {
        printWindow.document.write('<div class="table-container">');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write('</div>');
    });

    printWindow.document.write('</body></html>');
    printWindow.document.close(); // ปิดการเขียนเอกสาร
    printWindow.print(); // เรียกหน้าต่างพิมพ์
}


    </script>
</body>

</html>