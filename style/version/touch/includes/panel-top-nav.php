<?php

if (url_request_validate('/login') == true || url_request_validate('/password') == true || url_request_validate('/registration') == true){
  
  ?>
  <div class="panel-top" id="sidebar_show">
  <?=icons('bars', 23)?>
  </div>  
    
  <div class='panel-top-middle'>
  <div><a href='/'><?=icons('home', 20, 'fa-fw')?></a><?=icons('angle-right', 20, 'fa-fw')?><span><?=tabs(config('TITLE'))?></span></div>
  </div>
    
  <a href='/m/info/' class='panel-top'>
  <?=icons('info-circle', 24)?>  
  </a>
  <?
  
}elseif (url_request_validate('/account/mail/edit') == true || url_request_validate('/account/mail/settings') == true){
  
  ?>
  <div class="panel-top" id="sidebar_show">
  <?=icons('bars', 23)?>
  </div>  
    
  <div class='panel-top-middle'>
  <div><a href='/'><?=icons('home', 20, 'fa-fw')?></a><?=icons('angle-right', 20, 'fa-fw')?><span><?=tabs(config('TITLE'))?></span></div>
  </div>
    
  <a href='/m/search/' class='panel-top'>
  <?=icons('search', 22)?>  
  </a>
  <?  
  
}elseif (url_request_validate('/account/mail/messages') == true){
  
  ?>
  <a href='/account/mail/' class='panel-top'>  
  <?=icons('arrow-left', 22)?>
  </a>  
    
  <div class='panel-top-middle-mail'>
  <?php require(ROOT.'/users/account/mail/plugins/nav_user.php'); ?>
  </div>
    
  <div class='panel-top' onclick="open_or_close('panel-top-modal2')">
  <span style='position: relative; top: 2px;'><?=icons('ellipsis-v', 22)?></span>  
  </div>
  
  <div id='panel-top-modal2' class='panel-top-modal' style='display: none;'> 
  <?php require_once (ROOT.'/users/account/mail/plugins/nav_menu.php'); ?>
  </div>
    
  <?
  
}elseif (url_request_validate('/account/mail/write') == true){
  
  ?>
  <div class="panel-top" id="sidebar_show">
  <?=icons('bars', 23)?>
  </div>  
    
  <div class='panel-top-middle-search'>
  <?php require(ROOT.'/users/account/mail/plugins/nav_search_write.php'); ?>
  </div>   
  <?
  
}elseif (url_request_validate('/account/friends') == true){
  
  ?>
  <div class="panel-top" id="sidebar_show">
  <?=icons('bars', 23)?>
  </div>  
    
  <div class='panel-top-middle-search'>
  <?php require(ROOT.'/users/account/friends/plugins/nav_search_friends.php'); ?>
  </div>   
  <?
  
}elseif (url_request_validate('/account/mail') == true){
  
  ?>
  <div class="panel-top" id="sidebar_show">
  <?=icons('bars', 23)?>
  </div>  
    
  <div class='panel-top-middle-search'>
  <?php require(ROOT.'/users/account/mail/plugins/nav_konts.php'); ?>
  </div>   
  <?
  
}elseif (url_request_validate('/id') == true && url_request_validate('public/id') == false){
  
  ?>
  <a href='/account/cabinet/' class='panel-top'>  
  <?=icons('arrow-left', 22)?>
  </a>  
    
  <div class='panel-top-middle'>
  <div><a href='/'><?=icons('home', 20, 'fa-fw')?></a><?=icons('angle-right', 20, 'fa-fw')?><span><?=tabs(config('TITLE'))?></span></div>
  </div>
    
  <div class='panel-top' onclick="open_or_close('panel-top-modal2')">
  <span style='position: relative; top: 2px;'><?=icons('ellipsis-v', 22)?></span>  
  </div>
    
  <div id='panel-top-modal2' class='panel-top-modal' style='display: none;'> 
  <?php require_once (ROOT.'/users/account/page/plugins/menu_nav.php'); ?>
  </div>  
  <?
  
}elseif (url_request_validate('/public/') == true){
  
  ?>
  <div class="panel-top" id="sidebar_show">
  <?=icons('bars', 23)?>
  </div>  
    
  <div class='panel-top-middle'>
  <div><a href='/'><?=icons('home', 20, 'fa-fw')?></a><?=icons('angle-right', 20, 'fa-fw')?><span><?=tabs(config('TITLE'))?></span></div>
  </div>
    
  <div class='panel-top' onclick="open_or_close('panel-top-modal2')">
  <span style='position: relative; top: 2px;'><?=icons('ellipsis-v', 22)?></span>  
  </div>
    
  <div id='panel-top-modal2' class='panel-top-modal' style='display: none;'> 
  <?php require_once (ROOT.'/modules/communities/plugins/menu_nav.php'); ?>
  </div>  
  <?
  
}else{
  
  ?>
  <div class="panel-top" id="sidebar_show">
  <?=icons('bars', 23)?>
  </div>  
    
  <div class='panel-top-middle'>
  <div><a href='/'><?=icons('home', 20, 'fa-fw')?></a><?=icons('angle-right', 20, 'fa-fw')?><span><?=tabs(config('TITLE'))?></span></div>
  </div>
    
  <a href='/m/search/' class='panel-top'>
  <?=icons('search', 22)?>  
  </a>
  <?

}