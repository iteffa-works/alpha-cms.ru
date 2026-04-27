<?php
  
if (user('ID') > 0 && str(user('TIMEZONE')) > 0){
  
  //Часовой пояс выбранный пользователем
  date_default_timezone_set(user('TIMEZONE'));
  
}else{
  
  //Часовой пояс сайта по умолчанию
  date_default_timezone_set(config('TIMEZONE'));
  
}