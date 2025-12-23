<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ฟอร์มลงทะเบียน | Open House 2024</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Modern Theme CSS (Linked from parent directory) -->
    <link rel="stylesheet" href="../css/modern-theme.css">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .required-star {
            color: #ff512f;
            margin-left: 4px;
            font-size: 1.2rem;
            line-height: 0;
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php"><i class="fas fa-rocket me-2"></i>PTC OPEN HOUSE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">ลงเวลาเข้างาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../examine.php">ตรวจสอบการจอง</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    // เชื่อมต่อฐานข้อมูล
    require_once 'conn.php';
    require_once 'function.php';


    // ตรวจสอบว่า date_id ถูกส่งมาหรือไม่
    if (!isset($_GET['date_id']) && !isset($_POST['date_id'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่พบข้อมูลรอบเวลา!',
                background: '#1e1e2f',
                color: '#fff'
            }).then(() => window.location = '../index.php');
        </script>";
        exit();
    }

    $date_id = isset($_GET['date_id']) ? htmlspecialchars($_GET['date_id']) : htmlspecialchars($_POST['date_id']);

    $date = $conn->prepare("SELECT date_open,date_round FROM tb_date WHERE date_id = :id");
    $date->bindParam(":id", $date_id, PDO::PARAM_INT);
    $date->execute();
    $date_open = $date->fetch(PDO::FETCH_ASSOC);

    if (!$date_open) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ข้อมูลรอบเวลาไม่ถูกต้อง!',
                 background: '#1e1e2f',
                color: '#fff'
            }).then(() => window.location = '../index.php');
        </script>";
        exit();
    }

    $date_thai = $date_open['date_open'];
    $date_round = $date_open['date_round'];

    // ตรวจสอบเมื่อมีการส่งข้อมูลผ่านฟอร์ม
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $school = htmlspecialchars($_POST['school']);
        $member_name = htmlspecialchars($_POST['member_name']);
        $phone_number = htmlspecialchars($_POST['phone_number']);
        $email = htmlspecialchars($_POST['email']);
        $quantity = (int) $_POST['quantity'];
        $date_id = (int) $_POST['date_id'];
        $car_service = isset($_POST['shuttle_service']) ? 1 : 0;

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
                    text: 'ข้อมูลรอบเวลาไม่ถูกต้อง!',
                    background: '#1e1e2f',
                    color: '#fff'
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
                    text: 'ไม่สามารถเพิ่มข้อมูลได้ เนื่องจากเกินจำนวนสูงสุด!',
                    background: '#1e1e2f',
                    color: '#fff'
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
                    text: 'ลงทะเบียนเรียบร้อยแล้ว!',
                    background: '#1e1e2f',
                    color: '#fff',
                    confirmButtonColor: '#00f2ff'
                }).then(() => window.location = '../index.php');
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถเพิ่มข้อมูลได้!',
                    background: '#1e1e2f',
                    color: '#fff'
                }).then(() => window.history.back());
            </script>";
        }
    }
    ?>

    <div class="examine-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">

                    <div class="check-box-contain text-start mx-auto p-4">
                        <div class="text-center mb-4">
                            <h2 class="form-title">ฟอร์มลงทะเบียน</h2>
                            <h5 style="color: var(--accent-cyan);" class="mb-2">
                                <i
                                    class="far fa-calendar-check me-2"></i><?php echo formatDateThai($date_thai) . ' ' . '<span class="badge rounded-pill bg-light text-dark">' . $date_round . '</span>'; ?>
                            </h5>
                            <hr style="border-color: var(--glass-border);">
                        </div>

                        <form action="" method="POST" id="preForm">
                            <input type="hidden" name="date_id" value="<?= $date_id ?>">

                            <div class="mb-3">
                                <label for="school" class="form-label">โรงเรียน <span
                                        class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        style="background: rgba(255,255,255,0.05); border-color: var(--glass-border); color: var(--text-secondary);"><i
                                            class="fas fa-school"></i></span>
                                    <input type="text" name="school" id="school" class="modern-input mb-0"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="member_name" class="form-label">ผู้ประสานงาน <span
                                        class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        style="background: rgba(255,255,255,0.05); border-color: var(--glass-border); color: var(--text-secondary);"><i
                                            class="fas fa-user-tie"></i></span>
                                    <input type="text" name="member_name" id="member_name" class="modern-input mb-0"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">เบอร์โทรศัพท์ <span
                                            class="required-star">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"
                                            style="background: rgba(255,255,255,0.05); border-color: var(--glass-border); color: var(--text-secondary);"><i
                                                class="fas fa-phone"></i></span>
                                        <input type="text" name="phone_number" id="phone_number"
                                            class="modern-input mb-0"
                                            style="border-top-left-radius: 0; border-bottom-left-radius: 0;" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <div class="input-group">
                                        <span class="input-group-text"
                                            style="background: rgba(255,255,255,0.05); border-color: var(--glass-border); color: var(--text-secondary);"><i
                                                class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" id="email" class="modern-input mb-0"
                                            style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="quantity" class="form-label">จำนวนนักเรียนที่เข้าร่วม <span
                                        class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        style="background: rgba(255,255,255,0.05); border-color: var(--glass-border); color: var(--text-secondary);"><i
                                            class="fas fa-users"></i></span>
                                    <input type="number" name="quantity" id="quantity" class="modern-input mb-0"
                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;" min="1"
                                        required>
                                </div>
                            </div>

                            <div class="mb-4 form-check ps-4">
                                <input type="checkbox" name="shuttle_service" id="shuttle_service"
                                    class="form-check-input"
                                    style="background-color: rgba(255,255,255,0.1); border-color: var(--glass-border);">
                                <label for="shuttle_service" class="form-check-label text-white-50 ms-2">ต้องการรถรับส่ง
                                    (เฉพาะเขตในตัวเมือง)</label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="modern-btn">
                                    <i class="fas fa-check-circle me-2"></i> ยืนยันการลงทะเบียน
                                </button>
                                <a href="../index.php" class="btn btn-outline-light text-white-50"
                                    style="border-radius: 10px; padding: 0.8rem;">
                                    <i class="fas fa-arrow-left me-2"></i> ย้อนกลับ
                                </a>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Footer for Pre page (Simplified) -->
    <footer class="modern-footer mt-auto">
        <div class="container">
            <p class="mb-0 text-white-50"><small>&copy; 2024 PTC Open House. All rights reserved.</small></p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>