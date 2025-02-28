<?php
    session_start();

    if(!isset($_SESSION['ID'])) //Nếu chưa đăng nhập (Tức là SESSION ID chưa được set) thì hệ thống sẽ tự động điều hướng người dùng đến tận trang login
    {
        header('Location: login.php');
        die;
    }

    require_once ('server/flower_connection.php');

    $flowerList = loadFlowerlist()['data'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Flower Shop Management</title>
        <link rel="stylesheet" href="./css/style_inventory.css">
    </head>

    <body>

        <?php
            include "./component/header.php";
        ?>

        <div class = "section_container">

            <h1 class="title">Inventory Management</h1>
            <div class="add-button">
                <a class="add-flower" href="new_flower.php">Thêm hoa mới</a>
            </div>

            <div class="section">

                <?php
                for($i = 0; $i < sizeof($flowerList); $i++)
                {
                    ?>
                    <div class="panel">
                        <a class="link" href="flower_description.php?id=<?= $flowerList[$i]['ID'] ?>">
                            <img class="img" src="<?= $flowerList[$i]['image'] ?>" alt="image">
                        </a>
                        <p class="name"><?= $flowerList[$i]['flower_name'] ?></p>
                        <p class="price"><?= $flowerList[$i]['price'] ?></p>
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>

        <?php
            include "./component/footer.php"
        ?>

    </body>
</html>