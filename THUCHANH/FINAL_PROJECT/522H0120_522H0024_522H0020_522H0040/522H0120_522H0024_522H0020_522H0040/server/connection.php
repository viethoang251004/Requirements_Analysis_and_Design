<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';


    // Hàm dùng để kết nối cơ sở dữ liệu
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

    function sendActivationEmail($email, $token)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'thinhlanguyenquoc@gmail.com';                     //SMTP username
            $mail->Password   = 'qrjm rxpd tdfv jcfx';                             //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom('thinhlanguyenquoc@gmail.com', 'Flower Management');
            $mail->addAddress($email, 'Customer');     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
        
            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Activate your account';
            $mail->Body    = "Click <a href = 'http://localhost:8080/Software/activate.php?email=$email&token=$token'>here</a> to activate your account";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Hàm kiểm tra xem email đã được đăng ký hay chưa
    function isEmailExist($email)
    {
        $sql = "SELECT name FROM users WHERE email = ?";
        $conn = connection();

        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $email);

        if (!$stm->execute()) {
            return null;
        }

        $result = $stm->get_result();

        if ($result->num_rows >= 1) {
            return true;
        }

        return false;
    }


    // Hàm kiểm tra và đăng nhập người dùng
    function login($email, $password)
    {
        $conn = connection();
        $sql = "SELECT * FROM users where email = ?";

        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $email);

        if (!$stm->execute()) {
            return array('code' => 1, 'error' => 'Cannot execute command');
        }

        // Nếu như sql chạy thành công
        $result = $stm->get_result();

        if ($result->num_rows == 0) {
            return array('code' => 1, 'error' => 'Tài khoản không tồn tại');
        }
        
        $acc = $result->fetch_assoc();

        // if (!password_verify($password, $acc['password'])) 
        if ($password != $acc['password']) 
        {
            return array('code' => 2, 'error' => "Mật khẩu không đúng");
        }
        elseif($acc['activated'] == 0){
            return array('code' => 3, 'error' => 'Tài khoản của bạn chưa được kích hoạt');
        } 
        else {
            return array('code' => 0, 'acc' => $acc);
        }
    }

    function signupAccount($name, $email, $phone, $password)
    {
        if(isEmailExist($email))
        {
            return array('code' => 1, 'error' => 'Tài khoản đã tồn tại');
        }

        $conn = connection();

        $rand = random_int(0, 1000);
        $token = md5($name . '+' . $rand);
        
        $sql = "INSERT INTO users(name, email, phone, password, token) values(?,?,?,?,?)";

        $stm = $conn -> prepare($sql);
        $stm -> bind_param('sssss', $name, $email, $phone, $password, $token);

        if(!$stm -> execute())
        {
            return array('code' => 2, 'error' => 'An error occured. Please try again later');
        }

        $reset_token = '';
        $sql = "INSERT INTO reset_token(email, token, expire_on) values(?,?,?)";

        $stm = $conn -> prepare($sql);
        $stm -> bind_param('sss',$email,$reset_token, $exp);

        if(!$stm -> execute())
        {
            return array('code' => 2, 'error' => 'An error occured. Please try again later');
        }

        sendActivationEmail($email, $token);
        return array('code' => 0, 'error' => 'Tài khoản của bạn đã được đăng ký thành công, hãy kiểm tra mail để kích hoạt tài khoản.');
    }

    function updateActivateToken($email, $token)
    {
        $conn = connection();
        $sql = "SELECT name from users where email = ? and token = ? AND activated = 0";
        $stm = $conn -> prepare($sql);
        $stm -> bind_param('ss', $email, $token);

        if(!$stm -> execute())
        {
            return array('code' => 1, 'error' => 'An error occured, please try again later');
        }

        $result = $stm -> get_result();

        if($result -> num_rows == 0)
        {
            return array('code' => 2, 'error' => 'Email của bạn chưa được đăng ký hoặc tài khoản của bạn đã được kích hoạt');
        }

        $sql = "UPDATE  users set activated = 1, token = '' where email = ?";
        $stm = $conn -> prepare($sql);
        $stm -> bind_param('s', $email);

        if(!$stm -> execute())
        {
            return array('code' => 1, 'error' => 'An error occured, please try again later');
        }
        return array('code' => 0, 'error' => 'Tài khoản của bạn đã được kích hoạt thành công');
    }

    function updateInformation($current_ID, $current_password, $name, $email, $phone, $password)
    {

        $conn = connection();
        $sql = "SELECT * FROM users where ID = ?";

        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $current_ID);

        if (!$stm->execute()) {
            return array('code' => 1, 'error' => 'Cannot execute command');
        }

        // Nếu như sql chạy thành công
        $result = $stm->get_result();        
        $acc = $result->fetch_assoc();

        if($current_password == $acc['password'])
        {
            $sql = "UPDATE users SET name= ?, email = ?, phone = ?, password = ? WHERE ID = ? ";

            $stm = $conn -> prepare($sql);
            $stm -> bind_param('sssss', $name, $email, $phone, $password, $current_ID);

            if(!$stm -> execute())
            {
                return array('code' => 2, 'error' => 'An error occured. Please try again later');
            }

            // Lấy thông tin mới
            $sql = "SELECT * FROM users where ID = ?";
            $stm = $conn->prepare($sql);
            $stm->bind_param('s', $current_ID);
            if (!$stm->execute()) {
                return array('code' => 1, 'error' => 'Cannot execute command');
            }

            $result = $stm->get_result();
            $acc = $result->fetch_assoc();
            return array('code' => 0, 'acc' => $acc);
        }
        else
        {
            return array('code' => 3, 'error' => 'Mật khẩu của bạn không đúng, không thể cập nhật thông tin mới');
        }
        
    }

    function takeTokentoReset($email)
    {
        $conn = connection();
        $sql = "SELECT * FROM reset_token where email = ? and token = '' ";

        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $email);

        if (!$stm->execute()) {
            return array('code' => 2, 'error' => 'An error occured, please try again');
        }
        $result = $stm->get_result();

        if ($result->num_rows == 0) {
            return array('code' => 1, 'error' => 'Tài khoản không tồn tại hoặc là bạn chưa nhận được Mail thông báo');
        }

        $acc = $result->fetch_assoc();
        //tao mat khau moi
        //
        $rand = random_int(0, 1000);
        $new_token = md5($acc['username']. '+' . $rand);

        $sql = "UPDATE reset_token SET token = ? where email = ?";
        $stm = $conn -> prepare($sql);
        $stm -> bind_param('ss', $new_token, $email);

        if (!$stm->execute()) 
        {
            return array('code' => 2, 'error' => 'An error occured, please try again');
        }

        return array('code' => 0, 'newToken' => $new_token, 'error' => "Hãy kiểm tra mail của bạn để thay đổi mật khẩu");
        
    }

    function sendResetpasswordEmails($email, $token)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'thinhlanguyenquoc@gmail.com';                     //SMTP username
            $mail->Password   = 'qrjm rxpd tdfv jcfx';                             //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom('thinhlanguyenquoc@gmail.com', 'PHIM KHONG HAY');
            $mail->addAddress($email, 'Customer');     //Add a recipient
            // $mail->addAddress('ellen@example.com');               //Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
        
            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Reset password';
            $mail->Body    = "Click <a href = 'http://localhost:8080/Software/reset_password.php?email=$email&token=$token'>here</a> to reset your password";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function resetPassword($email, $token, $newPassword)
    {
        $conn = connection();
        $sql = "SELECT * from reset_token where email = ? and token = ?";
        $stm = $conn -> prepare($sql);
        $stm -> bind_param('ss', $email, $token);

        if(!$stm -> execute())
        {
            return array('code' => 1, 'error' => 'An error occured, please try again later');
        }

        $result = $stm -> get_result();

        if($result -> num_rows == 0)
        {
            return array('code' => 2, 'error' => 'Tài khoản email của bạn không tồn tại hoặc mật khẩu đã được cập nhật trước đó');
        }

        
        $sql = "UPDATE  users set  password = ? where email = ?";
        $stm = $conn -> prepare($sql);
        $stm -> bind_param('ss',$newPassword, $email);

        if(!$stm -> execute())
        {
            return array('code' => 1, 'error' => 'An error occured, please try again later');
        }

        $sql = "UPDATE reset_token set  token = '' where email = ?";
        $stm = $conn -> prepare($sql);
        $stm -> bind_param('s', $email);

        if(!$stm -> execute())
        {
            return array('code' => 1, 'error' => 'An error occured, please try again later');
        }

        return array('code' => 0, 'error' => 'Bạn đã thay đổi mật khẩu thành công');
    }
?>