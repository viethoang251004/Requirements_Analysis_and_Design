<?php
    session_start();
    require_once'./server/connection.php';

    if(!isset($_SESSION['ID'])) //Nếu chưa đăng nhập (Tức là SESSION ID chưa được set) thì hệ thống sẽ tự động điều hướng người dùng đến tận trang login
    {
        header('Location: login.php');
        die;
    }

    if (isset($_POST['fullName']) && isset($_POST['phoneNumber']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['current_password'])) {
        
        $name = $_POST['fullName'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $password = $_POST['password'];
        $current_password = $_POST['current_password'];
      
        if (empty($name)) {
            $error = 'Hãy nhập tên của bạn';
        } 
        else if (empty($phoneNumber)) {
            $error = 'Hãy nhập số điện thoại của';
        } 
        else if (empty($email)) {
            $error = 'Hãy nhập email của bạn';
        }
        else if (empty($password)) {
            $error = 'Hãy nhập mật khẩu của bạn';
        } else if (strlen($password) < 6) {
            $error = 'Mật khẩu phải có ít nhất 6 kí tự';
        } 

        else
        {
            $result = updateInformation($_SESSION['ID'], $current_password, $name, $email, $phoneNumber, $password);
            if($result['code'] == 0)
            {
                $error = 'Tài khoản của bạn đã được cập nhật thông tin';
                ?>
                <script>
                    alert('<?= $error?>');
                    <?php
                        session_destroy();
                    ?>
                    window.location.href = 'login.php';
                </script>
                <?php
            }
            elseif($result['code'] == 1)
            {
                $error = $result['error'];
            }
            else
            {
                $error = $result['error'];
            } 
        }
      }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profile</title>
<link rel="stylesheet" type="text/css" href="./css/style_edit_profile.css">
</head>
<body>
    <?php
        include "./component/header.php"
    ?>

    <div class="container">
        <img src="./component/avatar.jpg" alt="Avatar">
        <form action="" method="post">
            <div class="user-info">
                <input type="text" id="fullName" name="fullName" placeholder="Họ và tên mới">
                <input type="text" id="phoneNumber" name="phoneNumber" placeholder="Số điện thoại mới">
                <input type="email" id="email" name="email" placeholder="Email mới">
                <input type="password" id="password" name="password" placeholder="Mật khẩu mới">
                <p id='current_password'>Hãy nhập mật khẩu hiện tại của bạn: </p>
                <input type="password" id="current_password" name="current_password" placeholder="Mật khẩu hiện tại của bạn">

                <?php
                if (!empty($error)) {
                    echo "<div class='error-message'>$error</div>";
                }
                ?>

            </div>
            <div class="buttons">
                <button type="submit">Lưu thay đổi</button>
            </div>
        </form>
    </div>

    <?php
        include "./component/footer.php"
    ?>

</body>
</html>
