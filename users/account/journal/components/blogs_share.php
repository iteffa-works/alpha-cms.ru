<?php  
$account = db::get_string("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
?>
<div class='notif-avatar'>
<a href='/id<?=$account['ID']?>'>
<?=user::avatar($account['ID'], 50)?>
</a>
<span class='notif-icon' style='background-color: #45A6E6'><?=icons('mail-forward', 11, 'fa-fw')?></span>  
<?php   
if ($list['TIME'] + 3600 > TM){
  
  ?><br /><span class='notif-count'><?=lg('новое')?></span><?
  
}
?>  
</div>
  
<div class='notif-info'>
  
<a href='/id<?=$account['ID']?>'><b><?=$account['LOGIN']?></b></a> <?=lg('поделился(-ась) вашей записью')?> -   
  
<?php

$blog = db::get_string("SELECT `ID`,`NAME`,`COMMUNITY` FROM `BLOGS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID_LIST']]);  
if (!isset($blog['ID'])){
  
  ?><font color='red'><?=lg('объект был удален')?></font><?
  
}else{
  
  ?>
  <a href="<?=($blog['COMMUNITY'] == 0 ? '/m/blogs/show/?id='.$blog['ID'] : '/m/communities/show_blog/?id='.$blog['ID'])?>">
  <?=tabs(crop_text($blog['NAME'], 0, 40))?>  
  </a>
  <?
  
}
  
?>
  
<br /><br />
<span class='time'><?=ftime($list['TIME'])?></span>
  
</div>