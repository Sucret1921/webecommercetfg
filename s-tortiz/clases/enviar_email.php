<?php
use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;     //SMTP::DEBUG_OFF;                 //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'hectortizferrerfpjob@gmail.com';                     //SMTP username
    $mail->Password   = 'Ortiz192120055';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('hectortizferrerfpjob@gmail.com', 'Tienda Online');
    $mail->addAddress('hectortizferrerfpjob@gmail.com', 'Joe User');     //Add a recipient
   ;

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Detalle de su compra';

    $longBody = "Estimado cliente, <br><br> Gracias por su compra. A continuación, encontrará los detalles de su pedido:<br>";
    $longBody .= "<p>El ID de su compra es <b>" . $id_transaccion . "<b></p>";

    $mail->Body = utf8_decode($longBody);
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Error al enviar correro electronico de la compra: {$mail->ErrorInfo}";
}