
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php

require_once 'conn.php';


    if(isset($_POST['submitadd'])){
        try{

            $date = $_POST['date'];
            $maxvalue = $_POST['maxvalue'];
            $gridRadios = $_POST['gridRadios'];
            $start = $_POST['start'];
            $end = $_POST['end'];

            $query = $conn->prepare("INSERT INTO tb_date(date_open,max_value,date_round,start_time,end_time) VALUES(:date,:maxvalue,:gridRadios,:start,:end)");
            $query->execute([':date'=>$date,
                                ':maxvalue' => $maxvalue,
                                ':gridRadios'=>$gridRadios,
                                ':start'=>$start,
                                ':end'=>$end]);
                    
            if($query){
                         echo "<script type='text/javascript'>
                                     Swal.fire({
                                      title: 'สำเร็จ!',
                                     text: 'บันทึกข้อมูลสำเร็จ',
                                      icon: 'success',
                                      confirmButtonText: 'ตกลง'
                                      }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href='../admin/date_mange.php';
                                                }
                                            });
                                          </script>";
                        }
                                
        }catch(PDOException $e){
            echo"เกิดข้อผิดพลาดในการบันทีกข้อมูล" .$e->getMessage();
        }
    }
?>

</body>
</html>
