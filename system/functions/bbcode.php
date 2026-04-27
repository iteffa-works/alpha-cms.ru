<?php
  
function bb_show() {
  
  ?>    
  <div id='bb' class='bb-show' style='display: none;'>
    
  <a class='bb-show-ob ssop2' id='bbs_back' data-factor='-1' ajax='no' style='left: 0;'>  
  <?=icons('chevron-left', 25)?>
  </a> 
    
  <a class='bb-show-ob ssop2' id='bbs_for' data-factor='1' ajax='no' style='right: 50px;'>  
  <?=icons('chevron-right', 25)?>
  </a>
    
  <a class='bb-show-ob ssop2' ajax='no' style='right: 0;' onclick="open_or_close('bb')">  
  <?=icons('times', 28)?>
  </a>
    
  <div class='bbs_op bb-show-ob-op'>    
  <a class='bbs bb-show-ob' ajax='no' alt='[b]<?=lg('текст')?>[/b]'>  
  <?=icons('bold', 21)?>
  </a> 
  <a class='bbs bb-show-ob' ajax='no' alt='[i]<?=lg('текст')?>[/i]'>  
  <?=icons('italic', 21)?>
  </a>
  <a class='bbs bb-show-ob' ajax='no' alt='[u]<?=lg('текст')?>[/u]'>  
  <?=icons('underline', 21)?>
  </a>
  <a class='bbs bb-show-ob' ajax='no' alt='[s]<?=lg('текст')?>[/s]'>  
  <?=icons('strikethrough', 21)?>
  </a>
  <a class='bbs bb-show-ob' ajax='no' alt='[quote]<?=lg('текст')?>[/quote]'>  
  <?=icons('comment', 21)?>
  </a>
  <a class='bbs bb-show-ob' ajax='no' alt='[url=<?=lg('ссылка')?>]<?=lg('имя ссылки')?>[/url]'>  
  <?=icons('link', 21)?>
  </a>
    
  <?php
  hooks::challenge('bb', 'bb');  
  hooks::run('bb'); 
  ?>
    
  </div>
  
  </div>
  <?
  
}
  
function bb_code($msg, $param = 1) {
  
  $bbcode = array(
    
    '/\[b\](.+)\[\/b\]/isU' => '<b>$1</b>',
    '/\[u\](.+)\[\/u\]/isU' => '<u>$1</u>',
    '/\[i\](.+)\[\/i\]/isU' => '<i>$1</i>',
    '/\[s\](.+)\[\/s\]/isU' => '<s>$1</s>',
    '/\[quote\](.+)\[\/quote\]/isU' => '<div style="padding: 7px; border-radius: 4px; background-color: #DBE7EC; color: #6C7275;">$1</div>'
  
  );
  
  hooks::challenge('bb_set', 'bb_set');  
  hooks::run('bb_set');
  
  $msg = preg_replace(array_keys($bbcode), ($param == 1 ? array_values($bbcode) : '$1'), $msg);
  
  return $msg;

}