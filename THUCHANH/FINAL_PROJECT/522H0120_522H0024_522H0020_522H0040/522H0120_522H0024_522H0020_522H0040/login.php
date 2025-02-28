<?php
    session_start();
    require_once('./server/connection.php');

    $error='';
    $email = '';
    $pasword = '';

    if(isset($_SESSION['ID']))
    {
        header('Location: index.php');
    }

    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
      
        if (empty($email)) {
          $error = 'Hãy nhập email của bạn';
        } else if (empty($password)) {
          $error = 'Hãy nhập mật khẩu của bạn';
        } else if (strlen($password) < 6) {
          $error = 'Mật khẩu phải có ít nhất 6 kí tự';
        } 
        else
        {
          $data = login($email, $password);
              
          if ($data['code'] == 0) 
          {
              $_SESSION['ID'] = $data['acc']['ID'];
              $_SESSION['name'] = $data['acc']['name'];
              $_SESSION['phone'] = $data['acc']['phone'];
              $_SESSION['email'] = $data['acc']['email'];

              $_SESSION['order1'] = 0;
              $_SESSION['order2'] = 0;
              $_SESSION['order3'] = 0;

      
              header('Location: index.php');
              exit();
      
          } else {
              $error = $data['error'];
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
        <link rel="stylesheet" href="./css/style_login.css">
    </head>

    <body>
        <form name="login_form"  method="POST" class="login_form" action="">

            <div class="login-heading">
                <h1 class="title" >Đăng nhập</h1>
            </div>

            <div class="line"></div>

            <div class="input-title">
                <h4>Email</h4>
                <input name="email" type="email" placeholder="Email" autocomplete="off">
            </div>

            <div class="input-title">
                <h4>Mật khẩu</h4>
                <input name="password" type="password" name="password" placeholder="Mật khẩu">
            </div>

            <p class="regis-message" >Bạn chưa có tài khoản? <a href="signup.php">Đăng ký</a> tại đây.</p>
            <p class="regis-message" ><a href="forget_password.php">Quên mật khẩu?</a></p>

            <?php
            if (!empty($error)) {
                echo "<div class='error-message'>$error</div>";
            }
            ?>

            <button name = "login" type="submit">Đăng nhập</button>
        </form>
    </body>
</html>
