<?php

/*
----------------------
Настройки пользователя
----------------------
*/
  
$settings_data = db::get_string("SELECT * FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [user('ID')]); 
  
function settings($data){
  
  global $settings_data;
  
  if (user('ID') == 0){
    
    return 0;
    
  }else{

    return tabs($settings_data[$data]);
    
  }
  
}

/*
---------------------------------------
Генерация базового аватара для аккаунта
---------------------------------------
*/

function GenAvatar() {
  
  $avatar_rand_param = array('#6E8CCE', '#53C8A9', '#8FA1A9', '#FC8FD8', '#AFBC81', '#B381BC', '#EB6156', '#FD8B2C', '#72C375', '#B970C5', '#31ACB8', '#5498CE', '#997445', '#4EA771', '#828D92', '#F55448', '#2D88BE', '#E85120', '#333E42');
  $avatar_rand = array_rand($avatar_rand_param, 1);
  $avatar = $avatar_rand_param[$avatar_rand];
  
  return $avatar;
  
}

/*
-------------------------------------------
Корректировка текстовых иконок Font-Awesome
-------------------------------------------
*/

if (!is_panel() && is_file(ROOT.'/style/version/'.version('DIR').'/includes/m_icons.php')) {
  
  require (ROOT.'/style/version/'.version('DIR').'/includes/m_icons.php');

}else{
  
  function icons($icons, $size = 15, $fw = null){
    
    return "<i style='font-size: ".$size."px; vertical-align: middle' class='fa fa-".$icons." ".$fw."'></i>";
  
  }
  
}

/*
--------------------------------------
Корректировка иконок расширений файлов
--------------------------------------
*/

function file_icons($ext, $color = '#337AB7', $icons = 'file', $type = 'big'){
  
  if ($type == 'big') {
    
    return "<div class='corner-box' style='background: linear-gradient(to left bottom, transparent 50%, rgba(0,0,0,.4) 0) no-repeat 100% 0 / 15px 15px, linear-gradient(-135deg, transparent 10px, ".$color." 0);'><span class='corner-box-icons'>".icons($icons, 15)."</span><span class='corner-box-ext'>".$ext."</span></div>";
    
  }elseif ($type == 'small') {
    
    return "<span style='color: ".$color."'>".icons($icons, 17, 'fa-fw')."</span>";
    
  }

}

/*
--------------------
Стилизованные иконки
--------------------
*/

if (!is_panel()){
  
  if (is_file(ROOT.'/style/version/'.version('DIR').'/includes/icons.php')) {
    
    require (ROOT.'/style/version/'.version('DIR').'/includes/icons.php');
  
  }else{
    
    if (is_file(ROOT.'/style/version/'.version('DIR').'/includes/param_icons_mini.php')) {
      
      require (ROOT.'/style/version/'.version('DIR').'/includes/param_icons_mini.php');
    
    }else{
      
      define('PARAM_ICONS_MINI_COLOR', null);
      define('PARAM_ICONS_MINI_BACKGROUND', null);
    
    }
  
    function m_icons($icons, $size, $color = '#6E7B80', $i = 1){
    
      if (str(PARAM_ICONS_MINI_COLOR) > 0 && str(PARAM_ICONS_MINI_BACKGROUND) > 0 && $i == 1) {
      
        $color = PARAM_ICONS_MINI_BACKGROUND;
        $tcolor = PARAM_ICONS_MINI_COLOR;
    
      }else{
      
        $color = $color;
        $tcolor = 'white';
    
      }
    
      return "<span class='icons-circle' style='background-color: ".$color."'; color: ".$tcolor.">".icons($icons, $size)."</span>";
  
    }
  
    if (is_file(ROOT.'/style/version/'.version('DIR').'/includes/param_icons.php')) {
    
      require_once (ROOT.'/style/version/'.version('DIR').'/includes/param_icons.php');
  
    }else{
    
      define('PARAM_ICONS_COLOR', null);
      define('PARAM_ICONS_GH_BACKGROUND', null);
      define('PARAM_ICONS_GW_BACKGROUND', null);
      define('PARAM_COUNT_BACKGROUND', null);
  
    }
  
    function b_icons($icons, $count = null, $size, $GHcolor = '#6E7B80', $GWcolor = '#6E7B80'){
    
      if (str(PARAM_ICONS_COLOR) > 0 && str(PARAM_ICONS_GW_BACKGROUND) > 0 && str(PARAM_ICONS_GH_BACKGROUND) > 0 && str(PARAM_COUNT_BACKGROUND) > 0) {

        $ccolor = PARAM_COUNT_BACKGROUND;
        $tcolor = PARAM_ICONS_COLOR;
        $color = 'linear-gradient(to top left, '.PARAM_ICONS_GH_BACKGROUND.', '.PARAM_ICONS_GW_BACKGROUND.')';
    
      }else{
      
        $color = 'linear-gradient(to top left, '.$GHcolor.', '.$GWcolor.')';
        $tcolor = 'white';
        $ccolor = null;
    
      }
    
      return "<div class='menu-container_icons' style='background: ".$color."; color: ".$tcolor."'><div style='".$ccolor."'>".$count."</div>".icons($icons, $size)."</div>";
    
    }
    
  }
    
}