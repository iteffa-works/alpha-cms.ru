<?php  
$account = db::get_string("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
?>
<div class='notif-avatar'>
<a href='/id<?=$account['ID']?>'>
<?=user::avatar($account['ID'], 50)?>
</a>
<span class='notif-icon' style='background-color: #FF4B5E;'><?=icons('thumbs-up', 11, 'fa-fw')?></span>  
<?php   
if ($list['TIME'] + 3600 > TM){
  
  ?><br /><span class='notif-count'><?=lg('новое')?></span><?
  
}
?>  
</div>
  
<div class='notif-info'>
  
<a href='/id<?=$account['ID']?>'><b><?=$account['LOGIN']?></b></a> <?=lg('оценил(-а) ваш комментарий')?> -   
  
<?php

$obj = db::get_string("SELECT `ID`,`MESSAGE` FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID_LIST']]);  
if (!isset($obj['ID'])){
  
  ?><font color='red'><?=lg('объект был удален')?></font><?
  
}else{
  
  ?>
  <a href='/m/guestbook/'>
  <?=(tabs(crop_text($obj['MESSAGE'], 0, 40)) != null ? tabs(crop_text($obj['MESSAGE'], 0, 40)) : lg('Комментарий'))?>  
  </a>
  <?
  
}
  
?>
  
<br /><br />
<span class='time'><?=ftime($list['TIME'])?></span>
  
</div>