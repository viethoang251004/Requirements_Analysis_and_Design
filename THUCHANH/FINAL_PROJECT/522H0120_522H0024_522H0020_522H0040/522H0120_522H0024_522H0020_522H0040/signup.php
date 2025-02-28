<?php
    session_start();
    require_once('./server/connection.php');

    if(isset($_SESSION['ID']))
    {
        header("Location: index.php");
    }

    $error='';

    $name = '';
    $email = '';
    $phone = '';
    $password = '';
    $password_confirm = '';


    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
    
        $name = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        if(empty($name))
        {
            $error = 'Hãy nhập Username của bạn';
        }
        else if(empty($email))
        {
            $error = 'Hãy nhập Email của bạn';
        }
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $error = 'Địa chỉ Email của bạn không hợp lệ';
        }
        else if(empty($phone))
        {
            $error = 'Hãy nhập số điện thoại của bạn';
        }
        else if(empty($password))
        {
            $error = 'Hãy nhập Mật khẩu của bạn';
        }
        else if(empty($password_confirm))
        {
            $error = 'Hãy nhập mật khẩu lần 2 của bạn';
        }
        else if(empty($email))
        {
            $error = 'Hãy nhập Email của bạn';
        }
        else if (strlen($password) < 6) {
            $error = 'Mật khẩu phải có ít nhất 6 kí tự';
        }
        else if ($password != $password_confirm) {
            $error = 'Mật khẩu KHÔNG trùng khớp';
        }

        else {

            $result = signupAccount($name, $email, $phone, $password);

            if($result['code'] == 0)
            {
            ?>
            <script>
                alert('<?= $result['error']; ?>');
                window.location.href = 'login.php';
            </script>
            <?php
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
        <title>Flower Shop Management</title>
        <link rel="stylesheet" href="./css/style_signup.css">
    </head>

    <body>
        <form name="signup_form"  method="POST" class="signup_form" action="">

            <div class="signup-heading">
                <h1 class="title" >Đăng ký</h1>
            </div>

            <div class="line"></div>

            <div class="input-title">
                <h4>Username</h4>
                <input name="username" type="text" placeholder="Username" autocomplete="off">
            </div>

            <div class="input-title">
                <h4>Email</h4>
                <input name="email" type="email" placeholder="Email" autocomplete="off">
            </div>

            <div class="input-title">
                <h4>Số điện thoại</h4>
                <input name="phone" type="text" placeholder="Phonenum" autocomplete="off">
            </div>

            <div class="input-title">
                <h4>Mật khẩu</h4>
                <input name="password" type="password" name="password" placeholder="Mật khẩu">
            </div>

                    
            <div class="input-title">
                <h4>Nhập lại mật khẩu</h4>
                <input name="password_confirm" type="password" placeholder="Hãy nhập lại mật khẩu" autocomplete="off">
            </div>

            <p class="regis-message" >Bạn đã có tài khoản? <a href="login.php">Đăng nhập</a> tại đây.</p>

            <?php
            if (!empty($error)) {
                echo "<div class='error-message'>$error</div>";
            }
            ?>

            <button name = "signup" type="submit">Đăng kí tài khoản</button>
        </form>
    </body>
</html>