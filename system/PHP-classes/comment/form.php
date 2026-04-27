<style>
  
.panel-bottom-optimize {
  
  display: none;
  
}

</style>
  
<?=attachments_result()?>   
  
<div class='panel-bottom-comments'>
  
<?=attachments($type, $action)?> 
<?=smiles_show()?>
<?=bb_show()?>

<?php
  
if (url_request_validate('/account/mail/messages') == true){
  
  $show = intval(get('id'));
  require_once (ROOT.'/users/account/mail/plugins/reply.php'); 
  
}else{
  
  $show = 1;
  reply($action, $o_id);
  
}

?>
  
<form method='post' class='ajax-form-comments' action='<?=$action?>'>

<div class='comments'>
<div>  

<a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/attachments/photos.php?type=<?=$type?>&url=<?=base64_encode($action)?>&show=<?=$show?>&<?=TOKEN_URL?>', 'attachments_upload')" class="comments-attachments"><i class="fa fa-paperclip fa-fw"></i></a>
  
<?php
if ($mp == 1) {
  
  $mp = 'onkeyup="messages_prints()"';
  
}else{
  
  $mp = null;
  
}
?>

<textarea <?=$mp?> id="<?=$id?>" class='comments-textarea count_char' rows="1" name="<?=$name?>" placeholder="<?=lg('Напишите сообщение')?>"><?=$text?></textarea>
 
<?php
$smiles_dir = db::get_string("SELECT `ID` FROM `SMILES_DIR` ORDER BY `ID` DESC LIMIT 1");
?>
<a ajax='no' onclick="open_or_close('smiles')" id='smile_up' action='/system/AJAX/php/smiles.php?id=<?=$smiles_dir['ID']?>' class='comments-smiles'><i class='fa fa-smile-o fa-fw'></i></a>
<a ajax='no' onclick="open_or_close('bb')" class='comments-bb'><i class='fa fa-font fa-fw'></i></a>
</div>

<button id="button-type" name='<?=$type?>' value='go' class='comments-button ajax-button-comments'><i class='fa fa-send fa-fw'></i></button>

</div>

<?php
if (config('CSRF') == 1){
  
  ?><input type="hidden" name="<?=csrf::token_id()?>" value="<?=csrf::token(csrf::token_id())?>"><?

}
?>

<input type="hidden" value="go" name="<?=$type?>">

</form>  
</div>