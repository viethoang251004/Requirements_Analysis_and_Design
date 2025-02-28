<?php
    session_start();
    require_once ('server/flower_connection.php');

    if(!isset($_SESSION['ID'])) //Nếu chưa đăng nhập (Tức là SESSION ID chưa được set) thì hệ thống sẽ tự động điều hướng người dùng đến tận trang login
    {
        header('Location: login.php');
        die;
    }

    if(isset($_GET['id']))
    {
       $id_flower = $_GET['id'];
       $flower = flowerInformation($id_flower)['data'];
    }

    if(isset($_POST['flower_name']) && isset($_POST['flower_price']) && isset($_POST['flower_description']) && isset($_POST['flower_quantity']) && isset($_POST['flower_link']))
    {
        $name = $_POST['flower_name'];
        if (empty($name)) {
            $name = $flower['flower_name'];
        }

        $price = $_POST['flower_price'];
        if (empty($price)) {
            $price = $flower['price'];
        }

        $description = $_POST['flower_description'];
        if (empty($description)) {
            $description = $flower['description'];
        }

        $quantity = $_POST['flower_quantity'];
        if (empty($quantity)) {
            $quantity = $flower['quantity'];
        }

        $image = $_POST['flower_link'];
        if (empty($image)) {
            $image = $flower['image'];
        }


        if(!is_numeric($price) || $price <= 0) 
        {
            $error = "Giá tiền phải là một số lớn hơn 0.";
        } 
        if(!is_numeric($quantity) || $quantity <= 0) 
        {
            $error = "Số lượng phải là một số lớn hơn 0.";
        } 
        else 
        {
            $result = updateFlower($id_flower, $name, $price, $description, $quantity, $image);
            
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

        <form  method="POST" class="signup_form" action="">

            <div class="signup-heading">
                <h1 class="title" >Cập nhật thông tin hoa</h1>
            </div>

            <div class="input-title">
                <h4>Tên hoa mới</h4>
                <input name="flower_name" type="text" autocomplete="off" placeholder="<?= $flower['flower_name'] ?>">
            </div>

            <div class="input-title">
                <h4>Giá mới của hoa</h4>
                <input name="flower_price" type="text" autocomplete="off" placeholder="<?= $flower['price'] ?>">
            </div>

            <div class="input-title">
                <h4>Cập nhật mô tả</h4>
                <input name="flower_description" type="text" autocomplete="off" placeholder="<?= $flower['description'] ?>">
            </div>

            <div class="input-title">
                <h4>Cập nhật số lượng</h4>
                <input name="flower_quantity" type="text" autocomplete="off" placeholder="<?= $flower['quantity'] ?>">
            </div>

            <div class="input-title">
                <h4>Cập nhật ảnh mới</h4>
                <input name="flower_link" type="text" autocomplete="off" >
            </div>

            <?php
            if (!empty($error)) {
                echo "<div class='error-message'>$error</div>";
            }
            ?>

            <button type="submit">Cập nhật</button>
        </form>
    </body>
</html>