<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    session_start();
    
    require 'phpMailer/src/Exception.php';
    require 'phpMailer/src/PHPMailer.php';
    require 'phpMailer/src/SMTP.php';
    include 'User.php';
    include 'constants.php';
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        
        if(isset($_POST['forgot-email'])){
            $email = filter_input(INPUT_POST,"forgot-email",FILTER_VALIDATE_EMAIL);
            $user = User::getByEmail($email);
            if(!isset($user->email)){
                $_SESSION["forgot_error"] = "No user found with email:".$email;
                header("location: forgotPage.php");
                exit();        
            }
            $pass = $user->password;
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true; 
            $mail->Username = 'epicnews206@gmail.com';
            $mail->Password = file_get_contents('gmail.txt');//PASSWORD IS IN gmail.txt
            $mail->setFrom('epicnews206@gmail.com','EPIC NEWS');
            $mail->addAddress($email,$user->username);
            $mail->isHTML(true);
            $mail->Subject = 'EPIC NEWS Reset Password';

            $url = file_get_contents('host.txt');

            $mail->Body = "<a href='".$url."/project-Ferdinand737/resetPage.php?key=".$email."&reset=".$pass."'>Click To Reset password</a>";
            $mail->send();
            echo "<h1 style='color: green;'>Email sent! close this window</h1>";
        }	

    }
    
?>
