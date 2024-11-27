<?php
    session_start();
    require_once '../command/conn.php';

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
    <title>เพิ่มผู้ดูแลระบบ</title>
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
            <a href="../admin/report.php" class="nav-item nav-link "><i class="fas fa-scroll"></i>report</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>จัดการ</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="../admin/date_mange.php" class="dropdown-item">เพิ่มวันที่</a>
                    <?php if (isset($_SESSION['type']) && $_SESSION['type'] === 'sp_admin') { ?>
                        <a href="../admin/admin_mange.php" class="dropdown-item active">เพิ่มadmin</a>
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

                <div class="container-fluid pt-4 px-4">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h6 class="mb-4">เพิ่มข้อมูลadmin</h6>
                            <form action="../command/add_admin.php" method ="POST">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">ชื่อ</label>
                                    <div class="col-sm-10">
                                        <input type="text" name='fname' class="form-control" required >
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label  class="col-sm-2 col-form-label">นามสกุล</label>
                                    <div class="col-sm-10">
                                        <input type="text"name='lname' class="form-control" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label  class="col-sm-2 col-form-label">ชื่อผู้ใช้งาน</label>
                                    <div class="col-sm-10">
                                        <input type="username"name='username' class="form-control" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label  class="col-sm-2 col-form-label">รหัสผ่าน</label>
                                    <div class="col-sm-10">
                                    <input type="password" name="password" class="form-control" required>
                                    </div>
                                </div>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">ยืนยันสิทธิ</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios1" value="1" checked>
                                            <label class="form-check-label" for="gridRadios1">
                                                อนุญาต
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios2" value="2">
                                            <label class="form-check-label" for="gridRadios2">
                                                     ไม่อนุญาต
                                            </label>
                                        </div>
                                        
                                    </div>
                                </fieldset>
                                <button type="submit" name="submitadd" class="btn btn-primary">เพิ่มข้อมูล</button>
                            </form>
                        </div>
                    </div>


            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-secondary text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">ข้อมูลรายชื่อadmin</h6>
            <input class="form-control form-control-sm w-50" type="text" id="search" placeholder="ค้นหา..." onkeydown="searchData(event)">
                </div>
               
                                <div class="table-responsive">
                                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                                        <thead>
                                            <tr class="text-white">
                                                <th scope="col">ชื่อผู้ใช้งาน</th>
                                                <th scope="col">ชื่อ</th>
                                                <th scope="col">นามสกุล</th>
                                                <th scope="col">สถานะ</th>
                                                <th scope="col">จัดการข้อมูล</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            
                                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                                                $member_id = isset($row['member_id']) ? $row['member_id'] : 'N/A';
                                                $member_fname = isset($row['member_fname']) ? $row['member_fname'] : 'N/A';
                                                $member_lname = isset($row['member_lname']) ? $row['member_lname'] : 'N/A';
                                                $member_code = isset($row['member_code']) ? $row['member_code'] : 'N/A';
                                                $member_allow = isset($row['member_allow']) ? $row['member_allow'] : 0;
                                            
                                                $status = ($member_allow == 1) ? "อนุญาต" : "ไม่อนุญาต";
                                                // แสดงผลในตาราง
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($member_code) . "</td>";
                                                echo "<td>" . htmlspecialchars($member_fname) . "</td>";
                                                echo "<td>" . htmlspecialchars($member_lname) . "</td>";
                                                echo "<td>" . htmlspecialchars($status) . "</td>";
                                                echo "<td>
                                                        <a class='btn btn-sm btn-primary' href='../command/edit_admin.php?member_id=" . urlencode($member_id) . "'>แก้ไข</a>
                                                        <a class='btn btn-sm btn-danger' href='../command/del.php?member_id=" . urlencode($member_id) . "'>ลบ</a>
                                                    </td>";
                                                echo "</tr>";
                                            }
                                            
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                         </div>
                    </div>
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
function searchData(event) {
    if (event.key === 'Enter') { // ตรวจสอบว่ากดปุ่ม Enter
        var searchValue = document.getElementById('search').value.trim();
        if (searchValue !== "") {
            // เปลี่ยน URL เพื่อส่งค่า search
            window.location.href = '?page=1&search=' + encodeURIComponent(searchValue);
        }
    }
}
</script>
</body>

</html>