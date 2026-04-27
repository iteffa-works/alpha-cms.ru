<?php

/*
-------------
Выход с сайта
-------------
*/ 
  
setcookie('USER_ID', 0, - TM + 60 * 60 * 24 * 365, '/');
setcookie('PASSWORD', 0, - TM + 60 * 60 * 24 * 365, '/');
setcookie('DOUBLE', 1, TM + 60 * 60 * 24 * 365, '/');
session_destroy();

redirect('/?');