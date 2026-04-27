<?php

/*
------------------------------------------------
функция отправки сообщений на электронные адреса
------------------------------------------------
*/

function email($email_cont, $title_cont, $message_cont, $email_us = null) {
  
  require_once ROOT.'/system/PHPMailer/Exception.php';
  require_once ROOT.'/system/PHPMailer/PHPMailer.php';
  require_once ROOT.'/system/PHPMailer/SMTP.php';

  $mail = new PHPMailer\PHPMailer\PHPMailer();
  $mail->CharSet = 'UTF-8';  
  $mail->isSMTP();
  $mail->isHTML(true);
  $mail->SMTPAuth = true;
  $mail->SMTPDebug = 0;
  if (config('EMAIL_PROTOCOL') == 'ssl' || config('EMAIL_PROTOCOL') == 'tls') { $mail->SMTPSecure = tabs(config('EMAIL_PROTOCOL')); } 
  $mail->Host = tabs(config('EMAIL_HOST'));
  $mail->Port = tabs(config('EMAIL_PORT'));
  $mail->Username = tabs(config('EMAIL'));
  $mail->Password = tabs(config('EMAIL_PASSWORD'));
  
  //От кого
  $mail->setFrom(tabs(config('EMAIL')), tabs(config('EMAIL_NAME')));	
  
  //Кому
  $mail->addAddress($email_cont, 'Пользователю '.HTTP_HOST);
  
  //Тема письма
  $mail->Subject = $title_cont;
  
  //Тело письма
  $body = $message_cont;
  $mail->msgHTML($body);
  
  //Отправляем
  if (!$mail->send()) { $mail->ErrorInfo; }

}