<div class='list'>
<center><font size='+1'><?=lg('Шаг')?> 2 <?=lg('из')?> 4</font><br /><br />
<b><font size='+1'><?=lg('Подключение к базе данных')?></font></b></center><br />
  
<?php
if (get('get_s') == 'sql'){
  
  if (db::connect()){
    
    $result = scandir(ROOT.'/install/tables/', SCANDIR_SORT_ASCENDING);
    
    for ($i = 0; $i < count($result); $i++){
      
      if (preg_match('#\.sql$#i',$result[$i])){       
        
        db::get_sql_file(ROOT.'/install/tables/'.$result[$i]);
      
      }
    
    }
    
    redirect('/install/?get=3');
  
  }
  
}

if (post('ok_sql')){
  
  $db_name = esc(post('db_name'));
  $db_pass = esc(post('db_pass'));
  $db_user_name = esc(post('db_user_name'));
  $db_host = esc(post('db_host'));
  $shif = base64_encode(rand(0000,9999).TM);
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'DB_HOST', $db_host);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'DB_NAME', $db_name);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'DB_USER', $db_user_name);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'DB_PASSWORD', $db_pass);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'SHIF', $shif);
  
  redirect('/install/?get=2&get_s=sql');
  
}

?>
<form method='post'>
<input type='text' class='form-control-100' name='db_name' placeholder='Имя базы'><br /><br />
<input type='text' class='form-control-100' name='db_pass' placeholder='Пароль от базы'><br /><br />
<input type='text' class='form-control-100' name='db_user_name' placeholder='Имя пользователя'><br /><br />
<input type='text' class='form-control-100' name='db_host' placeholder='Хост' value='localhost'><br /><br />
<button class='btn2' name='ok_sql' value='go'><?=lg('Дальше')?> <?=icons('arrow-right', 15, 'fa-fw')?></button>
<a class='btn' href='/install/?get=1'><?=icons('arrow-left', 15, 'fa-fw')?> <?=lg('Назад')?></a><br /><br />
</form>
</div>