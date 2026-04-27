<?php
  
/*
-----------------
Смена языка сайта
-----------------
*/
  
setcookie('LANGUAGE', tabs(get('lang')), TM + 60*60*24*365, '/');    
redirect('/');