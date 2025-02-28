<?php
require_once('order_connection.php');
// Kiểm tra xem người dùng đã nhấn nút Accept hay Decline chưa
if(isset($_POST['accept'])) {
    updateStatus($_POST['accept']);
    //Gọi hàm cập nhật trạng thái đơn hàng
    header("Location: ../order_management.php");

}

elseif(isset($_POST['decline'])) 
{
    deleteOrder($_POST['decline']);
    // $order_id = $_POST['decline'];
    header("Location: ../order_management.php");
}
?>
