<?php
  
if (config('PRIVATE_NEWS') == 1) {
  
  $list = db::get_string("SELECT `ID`,`NAME`,`MAIN_TIME`,`TIME`,`USER_ID` FROM `NEWS` ORDER BY `TIME` DESC LIMIT 1");
  
  if (get('close') == 'news'){
    
    setcookie('news_close', $list['ID'], TM + 60 * 60 * 24 * 365, '/');
    success('Новость успешно скрыта');
    redirect('/');
  
  }
  
  if ($list['MAIN_TIME'] > TM && cookie('news_close') != $list['ID']){
    
    ?>
    <div class="menu-info">
    <?=lg('Последние новости')?> 
    <a href='/?close=news' style='float: right; position: relative; bottom: 5px' class='btn-o'><?=lg('Скрыть')?> <?=icons('times', 17)?></a>
    </div>
    <?
    
    ?><div class='list-body'><?
      
    require (ROOT.'/modules/news/plugins/list.php');
    echo $news_list;
    
    ?>
    <a href='/m/news/'>
    <div class='list-menu' style='color: #5CB3F9'>
    <b><?=lg('Все новости')?></b>
    <span style='float: right'><?=icons('chevron-right', 14)?></span>
    </div>
    </a>
    </div>
    <?
    
  }
  
}