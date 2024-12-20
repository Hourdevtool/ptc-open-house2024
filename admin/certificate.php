<?php

    require_once  "../command/conn.php";

    $sql = "SELECT DISTINCT
                    TRIM(REPLACE(school, 'โรงเรียน', '')) AS school_name
                FROM tb_checkopen
                WHERE school IS NOT NULL
                ORDER BY school_name";

                
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<link href="css/cer-2.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>certificate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=IBM+Plex+Sans+Thai+Looped:wght@100;200;400&family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rosario:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
</head>
<body>
<?php
    foreach ($result as $row) {
        echo '<div class="container">';
        echo ' <img src="img/Cer.png" alt="">';
        echo '<div class="abs">';
        echo '<div class="school">';
        echo '<h3>โรงเรียน</h3>';
        echo "<h3>" . htmlspecialchars($row['school_name']) . "</h3>";
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
?>
</body>
<script>
    window.onload = function () {
        alert('กรุณาตั้งค่า "Paper size" เป็น "A4" และ"Margins" เป็น "None" และเปิดใช้งาน "Background Graphics" ในหน้าต่างการพิมพ์');
        window.print();
        window.onafterprint = function () {
            window.location.href = "report.php";
        };
    };
</script>
</html>