<?php
    session_start();

    if(!isset($_SESSION['ID'])) //Nếu chưa đăng nhập (Tức là SESSION ID chưa được set) thì hệ thống sẽ tự động điều hướng người dùng đến tận trang login
    {
        header('Location: login.php');
        die;
    }

    require_once('./server/order_connection.php');
    $data = load_order()['data'];

    if(isset($_POST['accept'])) 
    {
        $order_id = $_POST['accept'];

        ?>
        <script>
            alert('Bạn đã chấp nhận đơn hàng của <?= $data[$order_id]['name']?>');
        </script>
        <?php
    }

    if(isset($_POST['decline'])) 
    {
        $order_id = $_POST['decline'];
        
        ?>
        <script>
            alert('Bạn đã xóa đơn hàng của <?= $data[$order_id]['name']?>');
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
        <link rel="stylesheet" href="./css/style_order.css">
    </head>

    <body>

        <?php
            include "./component/header.php"
        ?>

        <form action="server/order_process.php" method="POST">
            <div class = "section_container">
                <?php

                    for($i = 0; $i < sizeof($data); $i ++)
                    {
                        if($data[$i]['status'] == 0) // Trạng thái đơn chưa được duyệt
                        {
                        ?>

                            <div class="panel">
                                <div>
                                    <img class="ava" src="./component/avatar.jpg" alt="image">
                                </div>
                                
                                <div>
                                    <div class="name"><?= $data[$i]['name'] ?></div>
                                    <div class="phone"><?= $data[$i]['num_phone'] ?></div>
                                </div>

                                <div class ="linkandbutton">
                                    <a class="details" href="order_details.php?id=<?= $data[$i]['id'] ?>">Details</a>

                                    <div class="button-container">
                                        <button type="submit" name="accept" value="<?= $data[$i]['id'] ?>" class="accept">Accept</button>
                                        <button type="submit" name="decline" value="<?= $data[$i]['id'] ?>" class="decline">Decline</button>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }
                        elseif($data[$i]['status'] == 1)
                        {
                        ?>

                            <div class="panel">
                                    
                                    <div>
                                        <img class="ava" src="./component/avatar.jpg" alt="image">
                                    </div>
                                    
                                    <div>
                                        <div class="name"><?= $data[$i]['name'] ?></div>
                                        <div class="phone"><?= $data[$i]['num_phone'] ?></div>
                                    </div>

                                    <div class ="linkandbutton">
                                        <a class="details" href="order_details.php?id=<?= $data[$i]['id'] ?>">Details</a>

                                        <div class="button-container">
                                            <p>Đơn hàng đã được duyệt</p>
                                        </div>
                                    </div>
                            </div>

                        <?php
                        }
                    }

                ?>
            </div>
        </form>

        <?php
            include "./component/footer.php"
        ?>

    </body>
</html>