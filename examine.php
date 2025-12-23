<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ตรวจสอบการจอง | Open House 2024</title>
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
            <a class="nav-link" href="index.php">ลงเวลาเข้างาน</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="examine.php">ตรวจสอบการจอง</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              เพิ่มเติม
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="signin.php"><i
                    class="fas fa-sign-in-alt me-2"></i>สำหรับเจ้าหน้าที่</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="examine-wrapper">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">

          <div class="check-box-contain mx-auto">
            <h2 class="form-title">ตรวจสอบการจอง</h2>
            <h4 class="form-subtitle">PTC OPEN HOUSE 2024</h4>
            <p class="mb-4" style="color: var(--text-secondary);">กรุณากรอกหมายเลขโทรศัพท์เพื่อค้นหาข้อมูล</p>

            <form action="" method="POST">
              <input type="text" class="modern-input" placeholder="หมายเลขโทรศัพท์ (ไม่ต้องมีขีด)" name="phone_number"
                required>
              <button type="submit" class="modern-btn">
                <i class="fas fa-search me-2"></i> ตรวจสอบข้อมูล
              </button>
            </form>
          </div>

          <!-- Display Result -->
          <div class="mt-5">
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
                echo '<table class="custom-table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ชื่อ-นามสกุล</th>';
                echo '<th>เบอร์โทรศัพท์</th>';
                echo '<th>วันที่จอง</th>';
                echo '<th>รอบ</th>';
                echo '<th>จำนวน</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // แสดงข้อมูลในตาราง
                foreach ($results as $row) {
                  echo '<tr>';
                  echo '<td>' . htmlspecialchars($row['member_name']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['phone_number']) . '</td>';
                  echo '<td>' . htmlspecialchars($row['date_value']) . '</td>';
                  echo '<td><span class="status-badge open">' . htmlspecialchars($row['date_round']) . '</span></td>';
                  echo '<td class="text-center">' . htmlspecialchars($row['quandity']) . '</td>';
                  echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
              } else {
                echo "<div class='alert alert-warning text-center mt-4' style='background: rgba(255, 193, 7, 0.1); border: 1px solid #ffc107; color: #ffc107;'>
                                        <i class='fas fa-exclamation-triangle me-2'></i> ไม่พบข้อมูลการจองสำหรับหมายเลขโทรศัพท์นี้
                                      </div>";
              }
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="modern-footer">
    <div class="container">
      <h5 class="text-uppercase mb-3" style="letter-spacing: 2px;">ติดต่อสอบถาม</h5>
      <div class="footer-contact">
        <i class="fas fa-user-circle me-1"></i> นางสาววสุธารา หมื่นโฮ้ง (น้องกระปุก) <span
          class="footer-highlight">087-361-5563</span>
      </div>
      <div class="footer-contact">
        <i class="fas fa-user-circle me-1"></i> นางสาวเกษกรจันทร์ วันมหาใจ (น้องสา) <span
          class="footer-highlight">087-545-9306</span>
      </div>
    </div>
  </footer>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>