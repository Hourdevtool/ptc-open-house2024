
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

            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $gridRadios = $_POST['gridRadios'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $hash_password =  password_hash($password,PASSWORD_DEFAULT);


            $sql = "SELECT * FROM tb_user WHERE member_code = :username";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['username' => $username]);
            if ($stmt->rowCount() > 0) {
                echo "<script type='text/javascript'>
                Swal.fire({
                 title: 'ขออภัย!',
                text: 'ขณะนี้มีชื่อผู้ใช้นี้อยู่ในระบบเเล้ว',
                 icon: 'error',
                 confirmButtonText: 'ตกลง'
                 }).then((result) => {
                           if (result.isConfirmed) {
                               window.location.href='../admin/admin_mange.php';
                           }
                       });
                     </script>";
                exit();
            }

            $query = $conn->prepare("INSERT INTO tb_user(member_fname,member_lname,member_code,password,member_allow,type) VALUES(:fname,:lname,:username,:password,:gridRadios,'admin')");
            $query->execute([
                                ':fname' => $fname,
                                 ':lname' => $lname,
                                ':gridRadios'=>$gridRadios,
                                ':username'=>$username,
                                ':password'=>$hash_password]);
                    
            if($query){
                         echo "<script type='text/javascript'>
                                     Swal.fire({
                                      title: 'สำเร็จ!',
                                     text: 'บันทึกข้อมูลสำเร็จ',
                                      icon: 'success',
                                      confirmButtonText: 'ตกลง'
                                      }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location.href='../admin/admin_mange.php';
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