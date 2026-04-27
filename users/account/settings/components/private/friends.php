<div id='friends'>
<?php
  
if (settings('FRIENDS_PRIVATE') == 0){
  
  $value = 1;
  $tumb = "tumb";
  
}else{
  
  $value = 0;
  $tumb = "tumb2";
  
}
  
if (get('get') == 'friends'){
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `FRIENDS_PRIVATE` = ? WHERE `USER_ID` = ? LIMIT 1", [$value, user('ID')]);
  
}

?>
</div>   
<div class='list-menu'>
<b><?=lg('Видимость друзей для всех')?></b>
<input onclick="request('/account/settings/private/?get=friends', '#friends')" class="input-tumb" type="checkbox" id="fr"><label class="<?=$tumb?>" style="float: right; position: relative; bottom: 4px" for="fr"></label></input>
</div>