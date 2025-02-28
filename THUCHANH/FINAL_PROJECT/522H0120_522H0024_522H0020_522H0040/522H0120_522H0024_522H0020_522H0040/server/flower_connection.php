<?php

function connection()
{
    $server = 'localhost';
    $user = 'root';
    $password = '';
    $databasename = 'flower_management';

    $conn = new mysqli($server, $user, $password, $databasename);

    if ($conn->connect_error) {
        die("cant connect to database, error" . $conn->connect_error);
    }
    return $conn;
}

function loadFlowerlist()
{
    $conn = connection();

    $sql = "SELECT * FROM flower";

    $result = $conn -> query($sql);

    $data = array();

    for($i = 0; $i < $result -> num_rows; $i ++)
    {
        $row = $result -> fetch_assoc();
        $data[] = $row;
    }

    return array('code' => 0, 'data' => $data);
}

function addFlower($name, $price, $description, $quantity, $image)
{
    $conn = connection();

    
    $sql = "INSERT INTO flower(flower_name, price, description, quantity, image) values(?,?,?,?,?)";

    $stm = $conn -> prepare($sql);
    $stm -> bind_param('sssss', $name, $price, $description, $quantity, $image);

    if(!$stm -> execute())
    {
        return array('code' => 2, 'error' => 'An error occured. Please try again later');
    }

    return array('code' => 0, 'error' => 'Đã thêm hoa mới vào hệ thống');
}

function flowerInformation($id)
{
    $conn = connection(); 
    $sql = "SELECT * FROM `flower` WHERE `ID` = ?"; 

    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $id);


    if (!$stm->execute()) {
        return array('code' => 2, 'error' => 'An error occurred. Please try again later');
    }

    $result = $stm->get_result();

    if ($result->num_rows == 0) {
        return array('code' => 1, 'error' => 'Không tìm thấy dữ liệu');
    }

    $data = $result->fetch_assoc();
    return array('code' => 0, 'data' => $data);
}


function updateFlower($id, $name, $price, $description, $quantity, $image)
{
    $conn = connection();
    $sql = "UPDATE `flower` SET `flower_name`=?, `price`=?, `description`=?, `quantity`=?, `image`=? WHERE id = ?";

    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ssssss', $name, $price, $description, $quantity, $image, $id);

    if(!$stm -> execute())
    {
        return array('code' => 2, 'error' => 'An error occured. Please try again later');
    }

    return array('code' => 0, 'error' => 'Đã cập nhật hoa');
}

function deleteFlower($id)
{
    $sql = "DELETE FROM `flower` WHERE id = ?";
    $conn = connection();

    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $id);

    if (!$stm->execute()) {
        return null;
    }

    return true;
}
?>