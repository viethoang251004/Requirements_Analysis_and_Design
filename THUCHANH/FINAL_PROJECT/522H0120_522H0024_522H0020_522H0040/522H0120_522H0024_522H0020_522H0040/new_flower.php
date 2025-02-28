<?php
    session_start();

    if(!isset($_SESSION['ID'])) //Nếu chưa đăng nhập (Tức là SESSION ID chưa được set) thì hệ thống sẽ tự động điều hướng người dùng đến tận trang login
    {
        header('Location: login.php');
        die;
    }

    require_once ('server/flower_connection.php');

    if(isset($_POST['flower_name']) && isset($_POST['flower_price']) && isset($_POST['flower_description']) && isset($_POST['flower_quantity']) && isset($_POST['flower_link']))
    {
        $name = $_POST['flower_name'];
        $price = $_POST['flower_price'];
        $description = $_POST['flower_description'];
        $quantity = $_POST['flower_quantity'];
        $image = $_POST['flower_link'];

        if(empty($name) || empty($price) || empty($description) || empty($quantity) || empty($image)) {
            $error = "Vui lòng điền đầy đủ thông tin.";
        } elseif(!is_numeric($price) || $price <= 0) {
            $error = "Giá tiền phải là một số lớn hơn 0.";
        } elseif(!is_numeric($quantity) || $quantity <= 0) {
            $error = "Số lượng phải là một số lớn hơn 0.";
        } else {
            $result = addFlower($name, $price, $description, $quantity, $image);
            
            if($result['code'] == 0)
            {
            ?>
            <script>
                alert('<?= $result['error']; ?>');
                window.location.href = 'inventory_management.php';
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
        <title>Flower Shop Management</title>
        <link rel="stylesheet" href="./css/style_signup.css">
    </head>

    <body>
        <div class="back_container">
            <a class="back" href="javascript:history.go(-1)">Back</a>
        </div>

        <form name="signup_form"  method="POST" class="signup_form" action="">

            <div class="signup-heading">
                <h1 class="title" >Thêm hoa mới</h1>
            </div>

            <div class="input-title">
                <h4>Tên loài hoa mới</h4>
                <input name="flower_name" type="text" autocomplete="off">
            </div>

            <div class="input-title">
                <h4>Giá của hoa</h4>
                <input name="flower_price" type="text" autocomplete="off">
            </div>

            <div class="input-title">
                <h4>Mô tả loài hoa</h4>
                <input name="flower_description" type="text" autocomplete="off">
            </div>

            <div class="input-title">
                <h4>Số lượng thêm vào kho</h4>
                <input name="flower_quantity" type="text" autocomplete="off">
            </div>

            <div class="input-title">
                <h4>Link dẫn đến ảnh của hoa</h4>
                <input name="flower_link" type="text" autocomplete="off">
            </div>

            <?php
            if (!empty($error)) {
                echo "<div class='error-message'>$error</div>";
            }
            ?>

            <button name = "signup" type="submit">Thêm hoa vào kho</button>
        </form>
    </body>
</html>