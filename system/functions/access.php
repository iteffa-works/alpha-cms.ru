<?php
  
/*
--------------------------------
Функция настроек доступа к сайту
--------------------------------
*/
  
function panel_access() {
  
  if (get('base') == 'panel' && user('ID') > 0) {
    
    if (url_request_validate('/admin/system/access_param') == false) {
      
      if (str(config('PASSWORD')) > 0 && cookie('PANEL_PASSWORD') != config('PASSWORD') || config('ACCESS') == 2 && user('PANEL_IP') != IP){
        
        redirect('/admin/system/access_param/');
        
      }
      
    }
    
  }else{
    
    if (get('base') != 'main' && get('section') != 'access' && user('ID') > 0) {
      
      if (str(config('PASSWORD')) > 0 && cookie('PANEL_PASSWORD') != config('PASSWORD') || config('ACCESS') == 2 && user('PANEL_IP') != IP){
        
        redirect('/access/');
        
      }
      
    }
    
  }
  
}
  
function access($param, $link = '/?', $blocked = 0) {
  
  //$param - параметр доступа
  //$link - ссылка на редирект
  //$blocked - блокировка доступа
  
  //Доступ по привилегиям
  if ($param != 'all' && $param != 'guests' && $param != 'users' && $param != 'management') {
    
    $access_list = db::get_string("SELECT `ID` FROM `PANEL_ACCESS_LIST` WHERE `NAME` = ? LIMIT 1", [esc($param)]);
    $access = db::get_column("SELECT COUNT(`ID`) FROM `PANEL_ACCESS_USER_LIST` WHERE `ID_ACCESS_LIST` = ? AND `ID_ACCESS` = ? LIMIT 1", [$access_list['ID'], user('ACCESS')]);
    
    $modules_access = db::get_string("SELECT `ID` FROM `PANEL_ACCESS_LIST` WHERE `NAME` = ? LIMIT 1", ['administration_show']);
    $modules = db::get_column("SELECT COUNT(`ID`) FROM `PANEL_ACCESS_USER_LIST` WHERE `ID_ACCESS_LIST` = ? AND `ID_ACCESS` = ? LIMIT 1", [$modules_access['ID'], user('ACCESS')]);
    
    if (isset($access_list['ID']) && $access > 0 && MANAGEMENT == 0){
      
      if (user('ID') == 0) { redirect('/'); }
      
      if ($blocked == 0) {
        
        if (isset($modules_access['ID']) && $modules > 0 && MANAGEMENT == 0) {
          
          panel_access();
          
        }
      
      }
      
      return true;
    
    }else{
      
      if (MANAGEMENT == 1) {
        
        if ($blocked == 0) {
          
          panel_access();
        
        }
        
        return true;
      
      }else{
        
        if ($link == null){
          
          return false;
        
        }
        
        if ($link != null){
          
          redirect($link);
        
        }
      
      }
    
    }
    
  }
  
  //Доступ только гостям
  if ($param == 'guests') {
    
    if (user('ID') > 0){
      
      if ($link == null){
        
        return false;
        
      }
      
      if ($link != null){

        redirect($link);
        
      }
      
    }else{
      
      return true;
      
    }
    
  }
  
  //Доступ только пользователям
  if ($param == 'users') {
    
    if (user('ID') == 0){
      
      if ($link == null){
        
        return false;
        
      }
      
      if ($link != null){

        redirect($link);
        
      }
      
    }else{
      
      return true;

    }
    
  }
  
  //Доступ только системным администраторам
  if ($param == 'management') {
    
    if (MANAGEMENT == 0){
      
      if ($link == null){
        
        return false;
        
      }
      
      if ($link != null){

        redirect($link);
        
      }
      
    }else{
      
      return true;

    }
    
  }
  
}