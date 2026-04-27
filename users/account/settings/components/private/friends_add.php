<div id='friends_add'>
<?php
  
if (settings('FRIENDS_PRIVATE_ADD') == 0){
  
  $value = 1;
  $tumb = "tumb";
  
}else{
  
  $value = 0;
  $tumb = "tumb2";
  
}
  
if (get('get') == 'friends_add'){
  
  if (settings('FRIENDS_PRIVATE_ADD') == 1){
    
    db::get_set("UPDATE `USERS_SETTINGS` SET `PRIVATE_ACCOUNT` = ? WHERE `USER_ID` = ? LIMIT 1", [0, user('ID')]);
  
  }
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `FRIENDS_PRIVATE_ADD` = ? WHERE `USER_ID` = ? LIMIT 1", [$value, user('ID')]);
  
}

?>
</div>   
<div class='list-menu'>
<b><?=lg('Предложение дружбы')?></b>
<input onclick="request('/account/settings/private/?get=friends_add', '#friends_add')" class="input-tumb" type="checkbox" id="fr_add"><label class="<?=$tumb?>" style="float: right; position: relative; bottom: 4px" for="fr_add"></label></input>
</div>