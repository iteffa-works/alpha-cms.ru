<?php
  
if (get('base') != 'panel'){
  
  /*
  ------------------------
  Определение версии сайта
  ------------------------
  */
  
  $ver = esc(tabs(filter_cookie('VERSION')));
  
  if (db::get_column("SELECT COUNT(*) FROM `PANEL_THEMES` WHERE `DIR` = ? AND `ACT` != '0' LIMIT 1", [$ver]) > 0){
    
    define('VERSION', $ver);
  
  }else{
    
    if (type_version()){
      
      $touch = db::get_column("SELECT `DIR` FROM `PANEL_THEMES` WHERE `PRIORITET_TOUCH` = ? LIMIT 1", [1]);      
      define('VERSION', (str($touch) > 0 ? $touch : 'touch'));
      
    }else{
      
      $web = db::get_column("SELECT `DIR` FROM `PANEL_THEMES` WHERE `PRIORITET_WEB` = ? LIMIT 1", [1]);
      define('VERSION', (str($web) > 0 ? $web : 'web'));
      
    }
  
  }
  
  function version($data){
    
    $version = db::get_string("SELECT * FROM `PANEL_THEMES` WHERE `DIR` = ? AND `ACT` >= '1' LIMIT 1", [esc(VERSION)]);  
    return tabs($version[$data]);
  
  }
  
  if (!is_dir(ROOT.'/style/version/'.version('DIR').'/') && url_request_validate('/install') == false){
    
    ?>
    Тема <b><?=VERSION?></b> не найдена<hr><br />
    Параметры ошибки: не найдена директория <b>/style/version/<?=VERSION?>/</b>
    <?
      
    exit;
    
  }
      
  if (!version('DIR') && url_request_validate('/install') == false){ 
    
    ?>
    Тема <b><?=VERSION?></b> не найдена<hr><br />
    Параметры ошибки: не найдена тема с именем директории <b>"<?=VERSION?>"</b> в базе данных
    <?
      
    exit;
    
  }
  
}else{
  
  /*
  ------------------------------------
  Определение версии панели управления
  ------------------------------------
  */
  
  if (cookie('PANEL_VERSION') == 'web' || cookie('PANEL_VERSION') == 'touch'){
    
    define('VERSION', filter_cookie('PANEL_VERSION'));
  
  }else{
    
    if (type_version()){
      
      define('VERSION', 'touch');
      
    }else{
      
      define('VERSION', 'web');
      
    }
  
  }
  
}