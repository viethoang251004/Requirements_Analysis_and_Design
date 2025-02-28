<?php
    session_start();
    require_once('server/flower_connection.php');

    if(!isset($_SESSION['ID'])) //Nếu chưa đăng nhập (Tức là SESSION ID chưa được set) thì hệ thống sẽ tự động điều hướng người dùng đến tận trang login
    {
        header('Location: ../login.php');
        die;
    }

    if(isset($_GET['id']))
    {
       $id_flower = $_GET['id'];
       $flower = flowerInformation($id_flower)['data'];
    }

    if(isset($_POST['delete']))
    {
        deleteFlower($id_flower);
        ?>
            <script>
                alert('Bạn đã xóa hoa');
                window.location.href = 'inventory_management.php';
            </script>
        <?php
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Flower Shop Management</title>
        <link rel="stylesheet" href="css/style_index.css">
        <link rel="stylesheet" href="css/style_flower_decription.css">
    </head>

    <body>
        <?php
            include "component/header.php";
        ?>

        <div class = "section_container">
            <h2 class="title">Product Information</h2>
            <div class="section">
                <div class="panel">
                    <div>
                        <div class = "img-container">
                            <img class="img" src="<?= $flower['image'] ?>" alt="image"> 
                        </div>
                        <div class="name"><?= $flower['flower_name'] ?></div>
                        <p class="price">Price: <?= $flower['price'] ?></p>
                        <p class="quantity">Quantity: <?= $flower['quantity'] ?></p>
                        <a class="link" href="inventory_management.php">Back to Inventory</a>
                    </div>

                    <div>
                        <h3 class="title-description">Decription</h3>
                        <p class="text-description"><?= $flower['description'] ?></p>

                        <form class="button-container" action="" method="post">
                            <a class="button-update" href="update_flower.php?id=<?= $id_flower?>">
                                Update flower information
                            </a>

                            <button action='submit' class="button-delete" name="delete">
                                Delete flower
                            </button>
                        </form>
                            
                    </div>
                </div>
            </div>
        </div>
        
        <?php
            include "component/footer.php";
        ?>

    </body>
</html>