<div id='subscribers'>
<?php
  
if (settings('SUBSCRIBERS_PRIVATE') == 0){
  
  $value = 1;
  $tumb = "tumb";
  
}else{
  
  $value = 0;
  $tumb = "tumb2";
  
}
  
if (get('get') == 'subscribers'){
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `SUBSCRIBERS_PRIVATE` = ? WHERE `USER_ID` = ? LIMIT 1", [$value, user('ID')]);
  
}

?>
</div>   
<div class='list-menu'>
<b><?=lg('Видимость подписчиков для всех')?></b>
<input onclick="request('/account/settings/private/?get=subscribers', '#subscribers')" class="input-tumb" type="checkbox" id="sub"><label class="<?=$tumb?>" style="float: right; position: relative; bottom: 4px" for="sub"></label></input>
</div>