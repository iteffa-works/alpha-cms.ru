<?php
  
//Только друзьям
if ($dir['PRIVATE'] == 1 && $dir['USER_ID'] != user('ID') && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $dir['USER_ID']]) == 0){
  
  ?>
  <div class='list'><center>
  <?=icons('lock', 50)?><br />
  <font size='+1'><?=lg('Доступ только для друзей владельца альбома')?></font>
  </center></div>
  <?
  
  back('/m/files/users/?id='.$account['ID']);
  acms_footer();
  
}

//Только автору + вложения
if ($dir['PRIVATE'] == 2 && $dir['USER_ID'] != user('ID') || $dir['PRIVATE'] == 3 && $dir['USER_ID'] != user('ID')){
  
  ?>
  <div class='list'><center>
  <?=icons('lock', 50)?><br />
  <font size='+1'><?=lg('Доступ только для владельца альбома')?></font>
  </center></div>
  <?
  
  back('/m/files/users/?id='.$account['ID']);
  acms_footer();
  
}

//По паролю
if ($dir['PRIVATE'] == 4 && str($dir['PASSWORD']) > 0 && $dir['USER_ID'] != user('ID') && $dir['PASSWORD'] != session('DIR_PASSWORD')){
  
  if (post('ok_files_pass')){
    
    $password = tabs(md5(esc(post('password'))));
    
    if ($password != $dir['PASSWORD']){
      
      error('Неверный пароль');
      redirect('/m/files/users/?id='.$account['ID'].'&dir='.$dir['ID']);
      
    }
    
    session('DIR_PASSWORD', $password);
    redirect('/m/files/users/?id='.$account['ID'].'&dir='.$dir['ID']);
    
  }
  
  ?>    
  <div class='list'>
  <center>
  <?=icons('key', 50)?><br />
  <font size='+1'><?=lg('Доступ только по паролю')?></font>
  </center>
  <br />
  <form method='post' class='ajax-form' action='/m/files/users/?id=<?=$account['ID']?>&dir=<?=$dir['ID']?>'>
  <?=html::input('password', 'Введите пароль', null, null, null, 'form-control-100', 'password', null, 'lock')?>  
  <?=html::button('button ajax-button', 'ok_music_pass', null, 'Вперед')?>  
  </form>  
  </div>
  <?
  
  back('/m/files/users/?id='.$account['ID']);
  acms_footer();
  
}