<?php
    session_start();

    require_once('./server/order_connection.php');
    

    if(!isset($_SESSION['ID']))
    {
        header('Location: ../login.php');
        die;
    }

    if (isset($_GET['id'])) 
    {
        $order_id = $_GET['id'];
    }

    $load_order_array = load_order()['data'];
    for($i = 0; $i < sizeof($load_order_array); $i ++)
    {
        if($load_order_array[$i]['id'] == $order_id)
        {
            $data = $load_order_array[$i];
        }
    }
    
    $order_list = explode(",", $data['order_list']);
    $ordered_flowers = getFlowerinOrderlist($order_list)['data'];

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Flower Shop Management</title>
        <link rel="stylesheet" href="css/style_customer.css">
        <link rel="stylesheet" href="css/style_index.css">
    </head>

    <body>

        <div class="back_container">
            <a class="back" href="javascript:history.go(-1)">Back</a>
        </div>

        <div class = "section_container">
            <h2>Your Order Details</h2>
            <div class="panel">
                <div class= "left-column">
                    <img class="ava" src="component/avatar.jpg" alt="image">
                    <div class="name"><?= $data['name']?></div>
                    <div class="phone"><?= $data['num_phone']?></div>
                    <div class= "address"><?= $data['address']?></div>
                    
                    <?php
                    if($data['status'] == 0)
                    {
                        ?>
                        <div class="button-container">
                            <form action="server/order_process.php" method="POST">
                                <button type="submit" name="accept" value="<?= $data['id'] ?>" class="accept">Accept</button>
                                <button type="submit" name="decline" value="<?= $data['id'] ?>" class="decline">Decline</button>
                            </form>
                        </div>
                        <?php
                    }
                    elseif($data['status'] == 1)
                    {
                        ?>
                        <div class="message_box">
                            Đơn hàng đã được duyệt
                        </div>
                        <?php
                    }
                    ?>

                </div>

                <div class="right-column">
                    <?php
                        for($i = 0; $i < sizeof($ordered_flowers); $i ++)
                        {
                        ?>
                            <p class = "product">-<?=$ordered_flowers[$i] ?></p>
                        <?php
                        }
                    ?>

                </div>
            </div>
        </div>

        <!-- <script src="../javascript/customer.js"></script> -->

    </body>
</html>