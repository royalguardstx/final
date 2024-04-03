<?php
if (array_key_exists('email', $_POST)) {
    date_default_timezone_set('Etc/UTC');

    require 'php-mailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;

    // SMTP configuration to work with GoDaddy's email
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com'; // Microsoft's SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'info@royalguardstx.com'; // Your full email address
    $mail->Password = 'yourpassword'; // Your email account password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted but different port
    $mail->Port = 587; // SMTP port for TLS/STARTTLS

    // Sender email address and name
    $mail->setFrom('info@royalguardstx.com', 'Royal Guards Texas');

    // Recipient email address (where to send the inquiries)
    $mail->addAddress('info@royalguardstx.com');

    // Add a reply-to address
    if ($mail->addReplyTo($_POST['email'], $_POST['name'])) {
        $mail->Subject = 'Contact Form Submission: '.$_POST['name'];
        $mail->isHTML(false);
        $mail->CharSet = 'UTF-8';
        
        // Construct email body
        $mail->Body = <<<EOT
Email: {$_POST['email']}
Name: {$_POST['name']}
Message: {$_POST['message']}
EOT;

        // Send the message, check for errors
        if (!$mail->send()) {
            $arrResult = array('response'=>'error', 'message'=>$mail->ErrorInfo);
        } else {
            $arrResult = array('response'=>'success');
        }
        echo json_encode($arrResult);
    } else {
        $arrResult = array('response'=>'error', 'message'=>'Invalid email address');
        echo json_encode($arrResult);
    }
}
?>
