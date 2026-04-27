<?php

/*
------------------------------------------------
функция отправки сообщений на электронные адреса
------------------------------------------------
*/

function email($email_cont, $title_cont, $message_cont, $email_us) {
  
  //$email_cont - адрес на который отправлять письмо  
  //$message_cont - сообщение
  //$title_cont - заголовок письма
  //$email_us - адрес с которого отправлять письмо на $email_cont
  
  $to = $email_cont;
  $title = $title_cont;
  $message = $message_cont;
  $headers = "From: $email_us\r\nContent-type: text/html; charset=utf-8\r\n";
  
  mail($to, $title, $message, $headers);

}