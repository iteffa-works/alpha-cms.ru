<?php
  
/*
-------------------------------
Функция проверки скорости сайта
-------------------------------
*/
  
function speed_size($url) { 
  
  $time_speed = microtime();
  $time_speed = explode(' ', $time_speed);
  $time_speed = $time_speed[1] + $time_speed[0];
  $start = $time_speed;  
  
  // Проверка корректности URL 
  if (filter_var($url, FILTER_VALIDATE_URL)){
    
    $surl = $url; 
    
    // Инициализация cURL
    $curlInit = curl_init($surl);
    
    // Установка параметров запроса
    curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curlInit, CURLOPT_HEADER, true);
    curl_setopt($curlInit, CURLOPT_NOBODY, true);
    curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
    
    // Получение ответа
    $response = curl_exec($curlInit);
    
    // закрываем CURL
    curl_close($curlInit);
    
    $time_speed = microtime();
    $time_speed = explode(' ', $time_speed);
    $time_speed = $time_speed[1] + $time_speed[0];
    $finish = $time_speed;
    $total_time = round(($finish - $start), 2);
    
    //Получаем скорость загрузки страницы
    $speed = $total_time;
    
    return $speed;
  
  }else{
    
    return lg('Ошибка'); 
    
  }

}

/*
---------------------------------------------
Функция оценки скорости генерации страниц в %
---------------------------------------------
*/

function speed($num){
  
  if ($num == "0.00"){
    
    return 100;
    
  }elseif ($num >= "0.01" && $num <= "0.03"){
    
    return 100;
    
  }elseif ($num >= "0.04" && $num <= "0.06"){
    
    return 98;
    
  }elseif ($num >= "0.07" && $num <= "0.09"){
    
    return 96;
    
  }elseif ($num >= "0.10" && $num <= "0.12"){
    
    return 94;
    
  }elseif ($num >= "0.13" && $num <= "0.15"){
    
    return 92;
    
  }elseif ($num >= "0.16" && $num <= "0.18"){
    
    return 90;
    
  }elseif ($num >= "0.19" && $num <= "0.21"){
    
    return 88;
    
  }elseif ($num >= "0.22" && $num <= "0.24"){
    
    return 86;
    
  }elseif ($num >= "0.25" && $num <= "0.27"){
    
    return 84;
    
  }elseif ($num >= "0.28" && $num <= "0.30"){
    
    return 82;
    
  }elseif ($num >= "0.31" && $num <= "0.33"){
    
    return 80;
    
  }elseif ($num >= "0.34" && $num <= "0.36"){
    
    return 78;
    
  }elseif ($num >= "0.37" && $num <= "0.39"){
    
    return 76;
    
  }elseif ($num >= "0.40" && $num <= "0.42"){
    
    return 74;
    
  }elseif ($num >= "0.43" && $num <= "0.45"){
    
    return 72;
    
  }elseif ($num >= "0.46" && $num <= "0.49"){
    
    return 70;
    
  }elseif ($num >= "0.50" && $num <= "0.52"){
    
    return 68;
    
  }elseif ($num >= "0.53" && $num <= "0.55"){
    
    return 66;
    
  }elseif ($num >= "0.56" && $num <= "0.58"){
    
    return 64;
    
  }elseif ($num >= "0.59" && $num <= "1.01"){
    
    return 62;
    
  }elseif ($num >= "1.02" && $num <= "1.04"){
    
    return 60;
    
  }elseif ($num >= "1.05" && $num <= "1.07"){
    
    return 58;
    
  }elseif ($num >= "1.08" && $num <= "1.10"){
    
    return 56;
    
  }elseif ($num >= "1.11" && $num <= "1.13"){
    
    return 54;
    
  }elseif ($num >= "1.14" && $num <= "1.16"){
    
    return 52;
    
  }elseif ($num >= "1.17" && $num <= "1.19"){
    
    return 50;
    
  }elseif ($num >= "1.20" && $num <= "1.23"){
    
    return 48;
    
  }elseif ($num >= "1.24" && $num <= "1.26"){
    
    return 46;
    
  }elseif ($num >= "1.27" && $num <= "1.29"){
    
    return 44;
    
  }elseif ($num >= "1.30" && $num <= "1.32"){
    
    return 42;
    
  }elseif ($num >= "1.33" && $num <= "1.35"){
    
    return 40;
    
  }elseif ($num >= "1.36" && $num <= "1.46"){
    
    return 38;
    
  }elseif ($num >= "1.47" && $num <= "1.57"){
    
    return 36;
    
  }elseif ($num >= "1.58" && $num <= "2.08"){
    
    return 34;
    
  }elseif ($num >= "2.09" && $num <= "2.19"){
    
    return 32;
    
  }elseif ($num >= "2.20" && $num <= "2.30"){
    
    return 30;
    
  }elseif ($num >= "2.31" && $num <= "2.41"){
    
    return 28;
    
  }elseif ($num >= "2.42" && $num <= "2.52"){
    
    return 26;
    
  }elseif ($num >= "2.53" && $num <= "3.13"){
    
    return 24;
    
  }elseif ($num >= "3.14" && $num <= "3.34"){
    
    return 22;
    
  }elseif ($num >= "3.35" && $num <= "3.55"){
    
    return 20;
    
  }elseif ($num >= "3.56" && $num <= "4.26"){
    
    return 18;
    
  }elseif ($num >= "4.27" && $num <= "4.57"){
    
    return 16;
    
  }elseif ($num >= "4.58" && $num <= "5.38"){
    
    return 14;
    
  }elseif ($num >= "5.39" && $num <= "6.29"){
    
    return 12;
    
  }elseif ($num >= "6.30" && $num <= "7.10"){
    
    return 10;
    
  }elseif ($num >= "7.11" && $num <= "7.15"){
    
    return 8;
    
  }elseif ($num >= "7.16" && $num <= "7.20"){
    
    return 6;
    
  }elseif ($num >= "7.21" && $num <= "7.25"){
    
    return 4;
    
  }elseif ($num >= "7.26" && $num <= "7.30"){
    
    return 2;
    
  }else{
    
    return 0;
    
  }

}

/*
--------------------------------------------------
Функция комментирования скорости генерации страниц
--------------------------------------------------
*/

function speed_comment($num){
  
  if ($num >= 80){
    
    return "<span class='rang-sec-comment'>".lg('Отлично')."!</span>";
    
  }elseif ($num >= 60 && $num < 80){
    
    return "<span class='rang-sec-comment'>".lg('Хорошо')."!</span>";
    
  }elseif ($num >= 40 && $num < 60){
    
    return "<span class='rang-sec-comment' style='background-color: #FBB443;'>".lg('Слабо')."!</span>";
    
  }else{
    
    return "<span class='rang-sec-comment' style='background-color: #FF6868;'>".lg('Плохо')."!</span>";  
  
  }
  
}

/*
------------------
Стрелка спидометра
------------------
*/

function speed_clock($num){
  
  if ($num >= 95 && $num <= 100){
    
    return 92;
    
  }elseif ($num >= 90 && $num <= 94){
    
    return 85;
    
  }elseif ($num >= 85 && $num <= 89){
    
    return 75;
    
  }elseif ($num >= 80 && $num <= 84){
    
    return 65;
    
  }elseif ($num >= 75 && $num <= 79){
    
    return 60;
    
  }elseif ($num >= 70 && $num <= 74){
    
    return 55;
    
  }elseif ($num >= 65 && $num <= 69){
    
    return 50;
    
  }elseif ($num >= 60 && $num <= 64){
    
    return 45;
    
  }elseif ($num >= 55 && $num <= 59){
    
    return 30;
    
  }elseif ($num >= 50 && $num <= 54){
    
    return 0;
    
  }elseif ($num >= 45 && $num <= 49){
    
    return 0;
    
  }elseif ($num >= 40 && $num <= 44){
    
    return -12;
    
  }elseif ($num >= 35 && $num <= 39){
    
    return -22;
    
  }elseif ($num >= 30 && $num <= 34){
    
    return -32;
    
  }elseif ($num >= 25 && $num <= 29){
    
    return -42;
    
  }elseif ($num >= 20 && $num <= 24){
    
    return -52;
    
  }elseif ($num >= 15 && $num <= 19){
    
    return -62;
    
  }elseif ($num >= 10 && $num <= 14){
    
    return -72;
    
  }elseif ($num >= 5 && $num <= 9){
    
    return -82;
    
  }else{
    
    return -92;
    
  }
  
  /*if ($num >= 80){
    
    return "<span class='rang-sec-comment'>".lg('Отлично')."!</span>";
    
  }elseif ($num >= 60 && $num < 80){
    
    return "<span class='rang-sec-comment'>".lg('Хорошо')."!</span>";
    
  }elseif ($num >= 40 && $num < 60){
    
    return "<span class='rang-sec-comment' style='background-color: #FBB443;'>".lg('Слабо')."!</span>";
    
  }else{
    
    return "<span class='rang-sec-comment' style='background-color: #FF6868;'>".lg('Плохо')."!</span>";  
  
  }*/
  
}