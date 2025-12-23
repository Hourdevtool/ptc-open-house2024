<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>เข้าสู่ระบบ | Open House 2024</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Modern Theme CSS -->
    <link rel="stylesheet" href="css/modern-theme.css">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar (Optional for Login page, but keeps navigation consistent) -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="fas fa-rocket me-2"></i>PTC OPEN HOUSE</a>
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
                        <a class="nav-link" href="examine.php">ตรวจสอบการจอง</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="examine-wrapper"> <!-- Using examine-wrapper for centering -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">

                    <div class="check-box-contain" style="padding: 2rem;">
                        <div class="text-center mb-4">
                            <h3 class="form-title">เข้าสู่ระบบ</h3>
                            <p class="text-secondary small">สำหรับเจ้าหน้าที่ / Admin</p>
                        </div>

                        <form action="command/login.php" method="Post">
                            <div class="mb-3 text-start">
                                <label for="username" class="form-label text-white-50">ชื่อผู้ใช้งาน</label>
                                <input type="text" name="username" class="modern-input mb-1" id="username"
                                    placeholder="" required>
                            </div>

                            <div class="mb-4 text-start">
                                <label for="password" class="form-label text-white-50">รหัสผ่าน</label>
                                <input type="password" name="password" class="modern-input mb-1" id="password"
                                    placeholder="" required>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember_me" class="form-check-input"
                                        id="exampleCheck1"
                                        style="background-color: rgba(255,255,255,0.1); border-color: var(--glass-border);">
                                    <label class="form-check-label text-secondary" for="exampleCheck1">จดจำฉัน</label>
                                </div>
                            </div>

                            <button type="submit" class="modern-btn w-100">เข้าสู่ระบบ</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>