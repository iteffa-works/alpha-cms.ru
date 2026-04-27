<div class='list'>
<center><font size='+1'><?=lg('Шаг')?> 3 <?=lg('из')?> 4</font><br /><br />
<b><font size='+1'><?=lg('Регистрация администратора')?></font></b></center><br />
  
<?php

if (post('ok_login')){
  
  $login = esc(post('login'));
  $system = esc(post('system'));
  $password = post('password');
  $password2 = post('password2');
  
  if ($password != $password2){
    
    echo "<font color='red'>Пароли не совпадают</font><br /><br />";
  
  }elseif (str($login) <= 2){
    
    echo "<font color='red'>Длина логина создателя не может быть меньше 2 символов</font><br /><br />";
  
  }elseif (str($system) <= 2){
    
    echo "<font color='red'>Длина логина системы сайта не может быть меньше 2 символов</font><br /><br />";
  
  }else{
    
    $ID = db::get_add("INSERT INTO `USERS` (`BROWSER`, `IP`, `DATE_CREATE`, `DATE_VISIT`, `LOGIN`, `PASSWORD`, `MANAGEMENT`, `ACCESS`) VALUES ('".BROWSER."', '".IP."', '".TM."', '".TM."', '".$login."', '".shif($password)."', '1', '99')");
    
    $us = db::get_string("SELECT * FROM `USERS` WHERE `ID` = '".$ID."' AND `LOGIN` = '".$login."' AND `PASSWORD` = '".shif($password)."' LIMIT 1");
    
    $hash = user_hash($us['ID']);
    
    db::get_set("UPDATE `USERS` SET `HASH` = '".$hash."' WHERE `ID` = '".$us['ID']."' LIMIT 1");
    
    setcookie('DOUBLE', 1, TM + 60 * 60 * 24 * 365, '/');
    setcookie('USER_ID', user_shif($us['ID']), TM + 60 * 60 * 24 * 365, '/');
    setcookie('PASSWORD', cencrypt(post('password'), $us['ID']), TM + 60 * 60 * 24 * 365, '/');
    session('salt', base64_encode(user_shif($us['ID']).','.cencrypt(post('password'), $us['ID'])));
    
    $_SESSION['no_install'] = 1;
    
    $avatar_rand_param = array('#EB6156', '#FD8B2C', '#72C375', '#B970C5', '#31ACB8', '#5498CE', '#997445', '#4EA771', '#828D92', '#F55448');
    $avatar_rand = array_rand($avatar_rand_param, 1);
    $avatar = $avatar_rand_param[$avatar_rand];
    
    db::get_add("INSERT INTO `NOTIFICATIONS_SETTINGS` (`USER_ID`) VALUES (?)", [$us['ID']]);
    db::get_add("INSERT INTO `MAIL_SETTINGS` (`USER_ID`) VALUES (?)", [$us['ID']]);
    db::get_add("INSERT INTO `USERS_SETTINGS` (`USER_ID`, `AVATAR_PHONE`) VALUES (?, ?)", [$us['ID'], $avatar]);
    
    /*
    -------------------
    Регистрация системы
    -------------------
    */
    
    $IDs = db::get_add("INSERT INTO `USERS` (`BROWSER`, `IP`, `DATE_CREATE`, `DATE_VISIT`, `LOGIN`, `PASSWORD`) VALUES ('".BROWSER."', '".IP."', '".TM."', '".TM."', '".$system."', '".shif($password)."')");
    
    $avatar_rand_param = array('#EB6156', '#FD8B2C', '#72C375', '#B970C5', '#31ACB8', '#5498CE', '#997445', '#4EA771', '#828D92', '#F55448');
    $avatar_rand = array_rand($avatar_rand_param, 1);
    $avatar = $avatar_rand_param[$avatar_rand];
    
    db::get_add("INSERT INTO `NOTIFICATIONS_SETTINGS` (`USER_ID`) VALUES (?)", [$IDs]);
    db::get_add("INSERT INTO `MAIL_SETTINGS` (`USER_ID`) VALUES (?)", [$IDs]);
    db::get_add("INSERT INTO `USERS_SETTINGS` (`USER_ID`, `AVATAR_PHONE`) VALUES (?, ?)", [$IDs, $avatar]);    
    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'SYSTEM', $IDs);
    
    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'DEVELOPER', 0);
    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'FRONT_HASH', TM);
    
    redirect('?get=4');
  
  }
  
}

?>
<form method='post'>
<input type='text' class='form-control-100' name='login' placeholder='Логин создателя сайта (id1)'><br /><br />
<input type='password' class='form-control-100' name='password' placeholder='Придумайте пароль'><br /><br />
<input type='password' class='form-control-100' name='password2' placeholder='Повторите пароль'><br /><br /> 
<input type='text' class='form-control-100' name='system' placeholder='Логин системы (Например: Администрация сайта)'><br /><br />
на сайт будет зарегистрирована система, оповещающая в почте людей о событиях на сайте. Его можно будет редактировать или менять на другой аккаунт через панель управления<br /><br />
<button class='btn2' name='ok_login' value='go'><?=lg('Дальше')?> <?=icons('arrow-right', 15, 'fa-fw')?></button>
</form>
</div>