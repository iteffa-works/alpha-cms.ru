<?php
  
$mess = db::get_column("SELECT COUNT(*) FROM `MAIL_MESSAGE` WHERE `USER_ID` = ? AND `USER` = ? AND `READ` = '0'", [user('ID'), user('ID')]);

if ($mess > 99){
  
  $c_mess = "<small class='count-mess'>99+</small>";

}elseif ($mess > 0){
  
  $c_mess = "<small class='count-mess'>".$mess."</small>";

}else{
  
  $c_mess = null;

}

$notif = db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS` WHERE `USER_ID` = ? AND `READ` = '1'", [user('ID')]);

if ($notif > 99){
  
  $c_notif = "<small class='count-mess'>99+</small>";

}elseif ($notif > 0){
  
  $c_notif = "<small class='count-mess'>".$notif."</small>";

}else{
  
  $c_notif = null;

}

$ta = db::get_column("SELECT COUNT(*) FROM `TAPE` WHERE `USER_ID` = ? AND `READ` = '1'", [user('ID')]);

if ($ta > 99){
  
  $c_ta = "<small class='count-mess'>99+</small>";

}elseif ($ta > 0){
  
  $c_ta = "<small class='count-mess'>".$ta."</small>";

}else{
  
  $c_ta = null;

}  
  
?>
<div onclick="dialog_modal('close')" id='dialog_close' style='display: none'></div>  
  
<div class='panel-top-optimize'>
<div class='panel-top-optimize2'>

<a href='/' class='panel-top-logo'>
<img src='/style/version/<?=version('DIR')?>/logo/<?=version('LOGO')?>' style='max-width: <?=version('LOGO_MAX')?>px'>
</a> 
  
<div class='panel-top-middle'>
<?php require (ROOT.'/style/version/'.version('DIR').'/includes/panel-top-nav.php'); ?>
</div>
  
<div class='panel-top-right'>
  
<?php if (user('ID') > 0) { ?>
  
<span class='ptr-a' onclick="dialog_modal('open')">
<o id='count_mail'><?=$c_mess?></o>  
<?=icons('envelope', 21)?>
</span>
  
<a href='/account/journal/' class='ptr-a'>
<o id='count_notif'><?=$c_notif?></o>  
<?=icons('bell', 21)?>
</a>
  
<a href='/account/tape/' class='ptr-a'>
<o id='count_tape'><?=$c_ta?></o>  
<?=icons('feed', 21)?>
</a>
  
<button onclick="open_or_close('panel-top-modal')">
<span><?=user::avatar(user('ID'), 30)?></span>
<div id='panel-top-modal-c'><?=icons('angle-down', 20)?></div> 
</button>
  
<div id='panel-top-modal' style='display: none;'>
<a href='/id<?=user('ID')?>'>
<?=icons('user', 20, 'fa-fw')?> <?=lg('Профиль')?>
</a>
  
<a href='/account/cabinet/'>
<?=icons('th-large', 20, 'fa-fw')?> <?=lg('Кабинет')?>
</a>  
  
<a href='/shopping/'>
<?=icons('shopping-basket', 20, 'fa-fw')?> <?=lg('Магазин услуг')?>
</a>  
  
<a href='/account/settings/'>
<?=icons('gear', 20, 'fa-fw')?> <?=lg('Настройки')?>
</a>
  
<a href='/exit/' ajax='no'>
<?=icons('power-off', 20, 'fa-fw')?> <?=lg('Выход')?>
</a>  
</div> 

<div id='dialog_modal' style='display: none'></div>
  
<?php } ?>  
  
</div>
  
</div>
</div>