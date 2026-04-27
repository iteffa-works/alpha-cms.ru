<?php

$type = 'gifts';
$type_tumb = 'gf';
$type_sql = 'GIFTS';
$type_text = "Подарки";

?>

<div id='<?=$type?>'>
  
<?php

if (notif($type_sql) == 0){
  
  $value = 1;
  $tumb = "tumb";
  
}else{
  
  $value = 0;
  $tumb = "tumb2";
  
}
  
if (get('private') == $type){
  
  db::get_set("UPDATE `NOTIFICATIONS_SETTINGS` SET `".$type_sql."` = ? WHERE `USER_ID` = ?", [$value, user('ID')]);
  
}

?>

</div>

<div class='list-menu'>
  
<span class='notif-icon2' style='background-color: #C679D3;'><?=icons('gift', 11, 'fa-fw')?></span> <?=lg($type_text)?>
<input onclick="request('/account/journal/settings/?private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>
  
</div>