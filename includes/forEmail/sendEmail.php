<?php
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';



$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'devconkidsinventory@gmail.com';
$mail->Password = 'yipo vnsj ymki ldrl';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->From = "devconkidsinventory@gmail.com"; //my gmail
$mail->FromName = "Dev Kids Admin -"; //sender name


$mail->addAddress($receiver);

$mail->isHTML(true);
$mail->Subject    = $subj;
$mail->Body    = $msg;
$mail->send();
