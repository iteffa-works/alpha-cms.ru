<?php

if (url_request_validate('/account/mail/messages') == true){
  
  ?>    
  <div class='panel-top-middle-mail'>
  <?php require(ROOT.'/users/account/mail/plugins/nav_user.php'); ?>
  </div>
    
  <div class='panel-top-menu' onclick="open_or_close('panel-top-modal2')">
  <span><?=lg('Меню')?> <?=icons('ellipsis-v', 22, 'fa-fw')?></span>  
  </div>
  
  <div id='panel-top-modal2' class='panel-top-modal' style='display: none;'> 
  <?php require_once (ROOT.'/users/account/mail/plugins/nav_menu.php'); ?>
  </div>    
  <?
  
}elseif (url_request_validate('/account/mail/write') == true){
  
  ?>    
  <div class='panel-top-middle-search'>
  <?php require(ROOT.'/users/account/mail/plugins/nav_search_write.php'); ?>
  </div>   
  <?
  
}elseif (url_request_validate('/account/friends') == true){
  
  ?>    
  <div class='panel-top-middle-search'>
  <?php require(ROOT.'/users/account/friends/plugins/nav_search_friends.php'); ?>
  </div>   
  <?
  
}elseif (url_request_validate('/account/mail') == true && url_request_validate('/account/mail/edit') == false && url_request_validate('/account/mail/settings') == false){
  
  ?>     
  <div class='panel-top-middle-search'>
  <?php require(ROOT.'/users/account/mail/plugins/nav_konts.php'); ?>
  </div>   
  <?
  
}elseif (url_request_validate('/id') == true && url_request_validate('public/id') == false){
  
  ?>    
  <div class="search-nav">
  <input type='text' placeholder='<?=lg('Поиск по сайту')?>' class='search-nav-text'>
  <button><?=icons('search', 16)?></button>
  </div>
    
  <div class='panel-top-menu' onclick="open_or_close('panel-top-modal2')">
  <span><?=lg('Меню')?> <?=icons('ellipsis-v', 22, 'fa-fw')?></span>
  </div>
    
  <div id='panel-top-modal2' class='panel-top-modal' style='display: none;'> 
  <?php require_once (ROOT.'/users/account/page/plugins/menu_nav.php'); ?>
  </div>  
  <?
  
}elseif (url_request_validate('/public/') == true){
  
  ?>    
  <div class="search-nav">
  <input type='text' placeholder='<?=lg('Поиск по сайту')?>' class='search-nav-text'>
  <button><?=icons('search', 16)?></button>
  </div>
    
  <div class='panel-top-menu' onclick="open_or_close('panel-top-modal2')">
  <span><?=lg('Меню')?> <?=icons('ellipsis-v', 22, 'fa-fw')?></span>  
  </div>
    
  <div id='panel-top-modal2' class='panel-top-modal' style='display: none;'> 
  <?php require_once (ROOT.'/modules/communities/plugins/menu_nav.php'); ?>
  </div>  
  <?
  
}else{
  
  ?> 
  <div class='search-nav'>
  <form method='post' class='ajax-form999' action='/m/search/?go=go'>
  <input type='text' name='search' placeholder='<?=lg('Поиск по сайту')?>' class='search-nav-text'> 
  <button class="ajax-button-search-web"><?=icons('search', 16)?></button>
  </form>
  </div>
  <?

}