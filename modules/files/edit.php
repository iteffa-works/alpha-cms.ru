<?php  
$file = db::get_string("SELECT * FROM `FILES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
acms_header(lg('Редактировать - %s', tabs($file['NAME'])), 'users');
get_check_valid();
is_active_module('PRIVATE_FILES');
attachments_result();

if (!isset($file['ID'])) {
  
  error('Неверная директива');
  redirect('/m/files/');

}

if (access('files', null) == false && $file['USER_ID'] != user('ID')){
  
  error('Нет прав');
  redirect('/m/files/');
  
}

if (post('ok_edit_files')){
  
  valid::create(array(
    
    'FILES_NAME' => ['name', 'text', [2, 200], 'Название', 0],
    'FILES_MESSAGE' => ['message', 'text', [0, 5000], 'Описание', 0],
    'FILES_PRIVATE_COMMENTS' => ['private_comments', 'number', [0, 5], 'Приватность комментариев'],
    'FILES_ID_ALBUM' => ['id_album', 'number', [0, 99999], 'Альбом'],
    'FILES_ADULT' => ['adult', 'number', [0, 1], 'Метка 18+']
  
  ));
  
  if ($file['NAME'] != FILES_NAME && db::get_column("SELECT COUNT(*) FROM `FILES` WHERE `USER_ID` = ? AND `NAME` = ? AND `ID_DIR` = ? LIMIT 1", [$file['USER_ID'], FILES_NAME, FILES_ID_ALBUM]) == 1){
    
    error('Файл с таким названием уже существует в данном альбоме');
    redirect('/m/files/edit/?id='.$file['ID'].'&'.TOKEN_URL);
    
  }
  
  if (ERROR_LOG == 1){
    
    redirect('/m/files/edit/?id='.$file['ID'].'&'.TOKEN_URL);
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `FILES_DIR` WHERE `ID` = ? AND `PRIVATE` != ? LIMIT 1", [FILES_ID_ALBUM, 0]) == 1){
    
    db::get_set("DELETE FROM `DOWNLOADS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$file['ID'], 'files']);
    
  }
  
  db::get_set("UPDATE `FILES` SET `ADULT` = ?, `ID_DIR` = ?, `PRIVATE_COMMENTS` = ?, `NAME` = ?, `MESSAGE` = ? WHERE `ID` = ? LIMIT 1", [FILES_ADULT, FILES_ID_ALBUM, FILES_PRIVATE_COMMENTS, FILES_NAME, FILES_MESSAGE, $file['ID']]);
  
  if (access('files', null) == true){
    
    logs('Файл - редактирование записи [url=/m/files/show/?id='.$file['ID'].']'.$file['NAME'].'[/url]', user('ID'));
    
  }
  
  success('Изменения успешно приняты');
  redirect('/m/files/show/?id='.$file['ID']);
  
}

?>    
<div class='list'>
<form method='post' class='ajax-form' action='/m/files/edit/?id=<?=$file['ID']?>&<?=TOKEN_URL?>'>
<?
$link = '/m/files/edit/?id='.$file['ID'].'&'.TOKEN_URL;  
html::input('name', 'Название', null, null, tabs($file['NAME']), 'form-control-100', 'text', null, 'camera');
html::textarea(tabs($file['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9, 0);  
?><br /><br /><?
$array = array();
$array[0] = ['Без альбома', ($file['ID_DIR'] == 0 ? "selected" : null)];
$data = db::get_string_all("SELECT * FROM `FILES_DIR` WHERE `USER_ID` = ? ORDER BY `ID` DESC", [$file['USER_ID']]);  
while ($list = $data->fetch()){
  
  $array[$list['ID']] = [$list['NAME'], ($file['ID_DIR'] == $list['ID'] ? "selected" : null)];

}
html::select('id_album', $array, 'Альбом', 'form-control-100-modify-select', 'folder'); 
html::select('private_comments', array(
  0 => ['Всем', ($file['PRIVATE_COMMENTS'] == 0 ? "selected" : null)], 
  1 => ['Мне и друзьям', ($file['PRIVATE_COMMENTS'] == 1 ? "selected" : null)], 
  2 => ['Только мне', ($file['PRIVATE_COMMENTS'] == 2 ? "selected" : null)]
), 'Комментирование', 'form-control-100-modify-select', 'comment');
?>

<div style='background-color: #E4F3FF; color: #577287; padding: 10px; border-radius: 10px; margin-bottom: 19px'>
<b><?=lg('Прикрепите скриншоты')?>:</b><br /><br />
<a ajax="no" id="modal_bottom_open_set" onclick="upload('/system/AJAX/php/files_screen.php?id=<?=$file['ID']?>&url=<?=base64_encode($link)?>', 'attachments_upload')" class="btn-o"><?=icons('upload', 15, 'fa-fw')?> <?=lg('Загрузить')?></a><br />
<div id='upload-screen'>
<?  
  
if (get('delete_screen')){
  
  $screen = db::get_string("SELECT `ID` FROM `ATTACHMENTS` WHERE `ID` = ? AND `ID_POST` = ? LIMIT 1", [intval(get('delete_screen')), $file['ID']]);
  
  if (isset($screen['ID'])) {
    
    get_check_valid();
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID` = ? LIMIT 1", [$screen['ID']]);
    @unlink(ROOT.'/files/upload/files/screen/'.$screen['ID'].'.jpg');
    @unlink(ROOT.'/files/upload/files/screen/source/'.$screen['ID'].'.jpg');
  
  }  
  
}
  
$html_screen = null;
$data2 = db::get_string_all("SELECT `ID` FROM `ATTACHMENTS` WHERE `ACT` = ? AND `TYPE_POST` = ? AND `ID_POST` = ? ORDER BY `TIME` DESC LIMIT 30", [1, 'files_screen', $file['ID']]);
while ($list2 = $data2->fetch()){
    
  $html_screen .= '
  <div class="attachments_files_type">
  <span class="attachments_delete" onclick="request(\''.url_request_get($link).'&delete_screen='.$list2['ID'].'\', \'#upload-screen\', \'1\')">'.icons('times', 12).'</span>
  <a ajax="no" href="/files/upload/files/screen/source/'.$list2['ID'].'.jpg">
  <img src="/files/upload/files/screen/'.$list2['ID'].'.jpg">
  </a>
  </div>';
  
}  
  
if (str($html_screen) > 0){
    
  ?>      
  <div class='upload-attachments-result'>
  <div class='attachments_files_type'><?=$html_screen?></div>
  </div>
  <?
    
}
  
?>
</div></div> 
  
<?=html::checkbox('adult', 'Метка <span class="adult">18+</span>', 1, $file['ADULT'])?>
<br /><br />

<?=html::button('button ajax-button', 'ok_edit_files', 'save', 'Сохранить')?>
<a class='button-o' href='/m/files/show/?id=<?=$file['ID']?>'><?=lg('Отмена')?></a>
<form>
</div>
<?

back('/m/files/show/?id='.$file['ID']);
acms_footer();