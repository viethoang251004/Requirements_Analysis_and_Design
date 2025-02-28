<?php
    session_start();

    if(!isset($_SESSION['ID'])) //Nếu chưa đăng nhập (Tức là SESSION ID chưa được set) thì hệ thống sẽ tự động điều hướng người dùng đến tận trang login
    {
        header('Location: login.php');
        die;
    }

    $name = $_SESSION['name'];
    $phone = $_SESSION['phone'];
    $email = $_SESSION['email'];

    if(isset($_POST['logout']))
    {
        session_destroy();
        header('Location: index.php');
    }
    elseif(isset($_POST['info_update']))
    {
        header('Location: edit_profile.php');
    }
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Interface</title>
    <link rel="stylesheet" type="text/css" href="./css/style_logout.css">
</head>
<body>
    <?php
        include('./component/header.php');
    ?>

    <div class="container_section">
        <form action="" method="POST">
            <img src="./component/avatar.jpg" alt="Avatar">
            <div class="user-info">
                <p><strong>Họ và tên: </strong><?= $name ?></p>
                <p><strong>Số điện thoại:</strong> <?= $phone ?></p>
                <p><strong>Email:</strong> <?= $email ?></p>
            </div>
            <div class="buttons">
                <button name="info_update" type = "submit">Chỉnh sửa thông tin</button>
                <button name="logout" type="submit" >Đăng xuất</button>
            </div>
        </form>
    </div>

    <?php
        include('./component/footer.php');
    ?>
</body>
</html>

