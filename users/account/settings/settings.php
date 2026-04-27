<?php  
acms_header('Общие настройки', 'users');

if (post('ok')){
  
  valid::create(array(
    
    'SET_STR' => ['str', 'number', [5, 20], 'Кол-во пунктов на страницу'],
    'SET_TEXT_SIZE' => ['text_size', 'number', [1, 3], 'Размер текста на сайте'],
    'SET_CF' => ['comment_format', 'number', [1, 3], 'Формат комментариев на сайте'],
  
  ));
  
  if (ERROR_LOG == 1){
    
    redirect(REQUEST_URI);
  
  }
  
  db::get_set("UPDATE `USERS_SETTINGS` SET `STR` = ?, `COMMENTS_FORMAT` = ?, `TEXT_SIZE` = ? WHERE `USER_ID` = ? LIMIT 1", [SET_STR, SET_CF, SET_TEXT_SIZE, user('ID')]);
  
  success('Изменения успешно приняты');
  redirect('/account/settings/settings/');

}
  
?>
<div class='list'>  
<form method='post' class='ajax-form' action='/account/settings/settings/'>
<?=html::select('str', array(
  5 => [5, (settings('STR') == 5 ? "selected" : null)], 
  10 => [10, (settings('STR') == 10 ? "selected" : null)], 
  15 => [15, (settings('STR') == 15 ? "selected" : null)], 
  20 => [20, (settings('STR') == 20 ? "selected" : null)]
), 'Кол-во пунктов на страницу', 'form-control-100-modify-select', 'sort-numeric-asc')?>   
<?=html::select('comment_format', array(
  1 => ['Древовидный формат', (settings('COMMENTS_FORMAT') == 1 ? "selected" : null)], 
  2 => ['Обычный формат', (settings('COMMENTS_FORMAT') == 2 ? "selected" : null)]
), 'Формат комментариев на сайте', 'form-control-100-modify-select', 'comments')?>
<?=html::select('text_size', array(
  1 => ['Маленький', (settings('TEXT_SIZE') == 1 ? "selected" : null)], 
  2 => ['Средний (рекомедуется)', (settings('TEXT_SIZE') == 2 ? "selected" : null)], 
  3 => ['Большой', (settings('TEXT_SIZE') == 3 ? "selected" : null)]
), 'Размер текста на сайте', 'form-control-100-modify-select', 'font')?> 
<?=html::button('button ajax-button', 'ok', 'save', 'Сохранить')?><br /><br />
<?php if (config('AJAX') == 1) : ?>
<?=lg('После внесения изменений рекомендуется')?> <a href='<?=REQUEST_URI?>' ajax='no'><?=lg('обновить страницу')?></a>.
<?php endif ?>
</form>
</div>
  
<div class='list'>
<?=lg('Версия сайта')?>: <b><?=version('NAME')?></b><br />
<a ajax="no" onclick="modal_center('version', 'open', '/system/AJAX/php/version.php', 'ver_upload')" class="btn"><?=icons('desktop', 11, 'fa-fw')?> <?=lg('Сменить версию')?></a><br /><br />  
<?=lg('Язык сайта')?>: <b><?=LANGUAGE?></b><br />
<a ajax="no" onclick="modal_center('languages', 'open', '/system/AJAX/php/languages.php', 'lang_upload')" class="btn"><?=icons('globe', 12, 'fa-fw')?> <?=lg('Сменить язык')?></a>
</div>
<?

back('/account/settings/', 'К настройкам аккаунта');
acms_footer();