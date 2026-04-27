<?php  
$account = db::get_string("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
?>
<div class='notif-avatar'>
<a href='/id<?=$account['ID']?>'>
<?=user::avatar($account['ID'], 50)?>
</a>
<span class='notif-icon' style='background-color: #C679D3;'><?=icons('gift', 11, 'fa-fw')?></span>  
<?php   
if ($list['TIME'] + 3600 > TM){
  
  ?><br /><span class='notif-count'><?=lg('новое')?></span><?
  
}
?>  
</div>
  
<div class='notif-info'>
  
<a href='/id<?=$account['ID']?>'><b><?=$account['LOGIN']?></b></a> <?=lg('подарил(-а) вам подарок')?>   
<br /><br />
<a href='/account/gifts/?id=<?=user('ID')?>' class='btn'><?=icons('gift', 16, 'fa-fw')?> <?=lg('Список подарков')?></a>
<br /><br />
<span class='time'><?=ftime($list['TIME'])?></span>
  
</div>