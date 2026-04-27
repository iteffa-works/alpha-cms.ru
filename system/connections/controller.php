<?php
  
/*
---------------------------------
Основной файл контроля и вызова 
страниц
Категорически запрещается трогать
данный файл
---------------------------------
*/
  
define('BASE', direct::get('base'));
define('PATH', direct::get('path'));
define('SUBPATH', direct::get('subpath'));
define('SECTION', direct::get('section'));

if (strpos(REQUEST_URI, 'base=') !== false || strpos(REQUEST_URI, 'path=') !== false || strpos(REQUEST_URI, 'subpath=') !== false || strpos(REQUEST_URI, 'section=') !== false){

  redirect('/');
  
}

if (direct::e_dir('/'.BASE.'/') == true){
  
  if (direct::e_dir('/'.BASE.'/'.PATH.'/'.SUBPATH.'/') == true){
    
    if (direct::e_file('/'.BASE.'/'.PATH.'/'.SUBPATH.'/'.SECTION.'.php') == true){
      
      require_once (ROOT.'/'.BASE.'/'.PATH.'/'.SUBPATH.'/'.SECTION.'.php');
    
    }else{
      
      if (direct::e_file('/'.BASE.'/'.PATH.'/'.SUBPATH.'/index.php') == true){
        
        require_once (ROOT.'/'.BASE.'/'.PATH.'/'.SUBPATH.'/index.php');
      
      }else{
        
        require_once (ROOT.'/main/index.php');
      
      }
    
    }
    
  }elseif (direct::e_dir('/'.BASE.'/'.PATH.'/') == true){
    
    if (direct::e_file('/'.BASE.'/'.PATH.'/'.SECTION.'.php') == true){
      
      require_once (ROOT.'/'.BASE.'/'.PATH.'/'.SECTION.'.php');
    
    }else{
      
      if (direct::e_file('/'.BASE.'/'.PATH.'/index.php') == true){

        require_once (ROOT.'/'.BASE.'/'.PATH.'/index.php');
      
      }else{

        require_once (ROOT.'/main/index.php');
      
      }
    
    }
  
  }else{
    
    if (direct::e_file('/'.BASE.'/'.SECTION.'.php') == true){

      require_once (ROOT.'/'.BASE.'/'.SECTION.'.php');
    
    }else{
      
      if (direct::e_file('/'.BASE.'/index.php') == TRUE){

        require_once (ROOT.'/'.BASE.'/index.php');
      
      }else{

        require_once (ROOT.'/main/index.php');
      
      }
    
    }
  
  }

}else{

  require_once (ROOT.'/main/index.php');

}