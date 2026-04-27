<?php if ($bb > 0) : ?>

<div style='position: relative; margin-left: -19px'>
<?=attachments_result()?>
</div>

<div style='position: relative; margin-top: 15px; margin-left: -2px; margin-right: -2px'>
<?=smiles_show()?>
<?=bb_show()?>
<?php if ($bb != 2) : ?>
<?=attachments(TYPE, ACTION, ID)?>
<?php endif ?>
<?=hooks::challenge('papp_show', 'papp_show')?>
<?=hooks::run('papp_show')?>  
</div>

<?php if ($bb != 2) : ?>
<a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/attachments/photos.php?type=<?=TYPE?>&id=<?=ID?>&url=<?=base64_encode(ACTION)?>&<?=TOKEN_URL?>', 'attachments_upload')" class="textarea-attachments"><i class="fa fa-paperclip fa-fw"></i></a>
<?php endif ?>
<a ajax='no' onclick="open_or_close('smiles')" id='smile_up' action='/system/AJAX/php/smiles.php?id=<?=db::get_column("SELECT `ID` FROM `SMILES_DIR` ORDER BY `ID` DESC LIMIT 1")?>' class='textarea-attachments'><i class='fa fa-smile-o fa-fw'></i></a>
<a ajax='no' onclick="open_or_close('bb')" class='textarea-attachments'><i class='fa fa-font fa-fw'></i></a>

<?=hooks::challenge('papp', 'papp')?>
<?=hooks::run('papp')?>
  
<?php endif ?>