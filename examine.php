<!DOCTYPE html>
<html lang="th">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบการจอง</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/examine-3.css">
</head>
<body>

<div class="wrapper">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container-fluid">
    <a class="navbar-brand fs-3" href="#">Open House</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">ลงเวลาเข้างาน</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="examine.php">ตรวจสอบการจอง</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            เพิ่มเติม
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="signin.php">Login</a></li>
            <!-- คุณสามารถเพิ่มลิงก์เพิ่มเติมได้ที่นี่ -->
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </nav>

    <!-- กล่องตรวจสอบการจอง -->
    <div class="container-fluid d-flex justify-content-center align-items-center content">
        <div class="check-box">
            <h2 style='color:#fff;'>ตรวจสอบการจอง</h2>
            <h3 style='color:#fff;'>PTC OPEN HOUSE 2024</h3>
            <p style='color:#88cc14;'>ป้อน หมายเลขโทรศัพท์ ของท่าน</p>
            <form action="" method="POST">
                <input type="text" class="form-control mb-3" placeholder="กรอกหมายเลขโทรศัพท์ที่นี่" name="phone_number" required>
                <div>
                    <button type="submit" class="button">
                      <span class="fold"></span>

                      <div class="points_wrapper">
                        <i class="point"></i>
                        <i class="point"></i>
                        <i class="point"></i>
                        <i class="point"></i>
                        <i class="point"></i>
                        <i class="point"></i>
                        <i class="point"></i>
                        <i class="point"></i>
                        <i class="point"></i>
                        <i class="point"></i>
                      </div>

                      <span class="inner"
                        ><svg
                          class="icon"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                          xmlns="http://www.w3.org/2000/svg"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2.5"
                        >
                          <polyline
                            points="13.18 1.37 13.18 9.64 21.45 9.64 10.82 22.63 10.82 14.36 2.55 14.36 13.18 1.37"
                          ></polyline></svg
                        >ตรวจสอบ</span
                      >
                    </button>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ตารางแสดงผล -->
    <div class="container mt-5">
        <?php
        // เชื่อมต่อฐานข้อมูล
        require_once 'command/conn.php';

        // ตรวจสอบว่ามีการส่งข้อมูลเบอร์โทรศัพท์หรือไม่
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['phone_number'])) {
            $phone_number = htmlspecialchars($_POST['phone_number']);

            // คำสั่ง SQL ดึงข้อมูลจาก tb_checkopen และ tb_date
            $query = $conn->prepare("
                SELECT 
                    c.member_name, 
                    c.phone_number, 
                    d.date_open AS date_value, 
                    d.date_round, 
                    c.quandity 
                FROM tb_checkopen c
                JOIN tb_date d ON c.date_id = d.date_id
                WHERE c.phone_number = :phone_number
            ");

            $query->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            // ตรวจสอบว่าพบข้อมูลหรือไม่
            if ($results) {
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered table-hover">';
                echo '<thead class="table-primary text-center">';
                echo '<tr>';
                echo '<th>ชื่อ-นามสกุล</th>';
                echo '<th>เบอร์โทรศัพท์</th>';
                echo '<th>วันที่จอง</th>';
                echo '<th>รอบ</th>'; // คอลัมน์ใหม่
                echo '<th>จำนวนที่จอง</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // แสดงข้อมูลในตาราง
                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['member_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['phone_number']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date_value']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['date_round']) . '</td>'; // ข้อมูลรอบ
                    echo '<td class="text-center">' . htmlspecialchars($row['quandity']) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo "<div class='alert alert-warning text-center'>ไม่พบข้อมูลการจองสำหรับหมายเลขโทรศัพท์นี้</div>";
            }
        }
        ?>
    </div>
</div>

<!-- Footer -->
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
