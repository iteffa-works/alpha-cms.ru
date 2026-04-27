<?php  
html::title('Языки');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Языки')?>
</div>  
<?
  
/*
---------------
Удалить перевод
---------------
*/ 
  
if (get('delete_tr')){
  
  get_check_valid();
  
  $delete = db::get_string("SELECT `ID` FROM `LANGUAGES_SHOW` WHERE `ID` = ? LIMIT 1", [intval(get('delete_tr'))]);  
  if (isset($delete['ID'])){
    
    db::get_set("DELETE FROM `LANGUAGES_SHOW` WHERE `ID` = ? LIMIT 1", [$delete['ID']]);   
    
  }
  
}
  
/*
---------------------
Редактировать перевод
---------------------
*/
  
if (get('edit_tr')){
  
  get_check_valid();
  
  $tr = db::get_string("SELECT * FROM `LANGUAGES_SHOW` WHERE `ID` = ? LIMIT 1", [intval(get('edit_tr'))]);
  $lang = db::get_string("SELECT * FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [esc($tr['TYPE'])]);
  
  if (!isset($tr['ID'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/system/languages/');
    
  }
  
  if (post('ok_phrase_edit')){
    
    valid::create(array(
      
      'PHRASE' => ['phrase', 'text', [1, 500], 'Фраза'],
      'TRANSLATE' => ['translate', 'text', [1, 500], 'Перевод']
    
    ));
    
    if (PHRASE != $tr['PHRASE'] && db::get_column("SELECT COUNT(*) FROM `LANGUAGES_SHOW` WHERE `TYPE` = ? AND `PHRASE` = , LIMIT 1", [tabs($tr['TYPE']), PHRASE]) > 0){
      
      error('Такая фраза в данном языковом пакете уже существует');
      redirect('/admin/system/languages/?edit_tr='.$tr['ID'].'&page='.get('page'));
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/system/languages/?edit_tr='.$tr['ID'].'&page='.get('page'));
    
    }
    
    db::get_set("UPDATE `LANGUAGES_SHOW` SET `PHRASE` = ?, `TRANSLATE` = ? WHERE `ID` = ? LIMIT 1", [PHRASE, TRANSLATE, $tr['ID']]);
    success('Изменения успешно приняты');
    redirect('/admin/system/languages/?language='.tabs($lang['FACT_NAME']).'&page='.get('page'));
    
  }
  
  ?>
  <div class='list-body'>
  <div class='list-menu list-title'>
  <?=lg('Языковой пакет')?> - <?=tabs($lang['NAME'])?> (<?=tabs($lang['FACT_NAME'])?>)
  </div>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/admin/system/languages/?edit_tr=<?=$tr['ID']?>&<?=TOKEN_URL?>'>
  <?=html::textarea(tabs($tr['PHRASE']), 'phrase', null, 'Введите фразу на русском языке:', 'form-control-textarea', 5, 0)?><br /><br />
  <?=html::textarea(tabs($tr['TRANSLATE']), 'translate', null, 'Введите перевод фразы:', 'form-control-textarea', 5, 0)?><br /><br />
  <?=html::button('ajax-button button', 'ok_phrase_edit', 'save', 'Сохранить изменения')?>
  <a class='button-o' href='/admin/system/languages/?language=<?=tabs($lang['FACT_NAME'])?>'><?=lg('Отмена')?></a>
  </form>
  </div>
  </div>
  <?
    
  back('/admin/system/languages/?language='.tabs($lang['FACT_NAME']));
  acms_footer();
  
}
  
/*
----------------
Добавить перевод
----------------
*/ 
  
if (get('add')){
  
  $lang = db::get_string("SELECT * FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [esc(get('add'))]);
  
  if (!isset($lang['FACT_NAME'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/system/languages/');
    
  }
  
  if (post('ok_phrase')){
    
    valid::create(array(
      
      'PHRASE' => ['phrase', 'text', [1, 500], 'Фраза'],
      'TRANSLATE' => ['translate', 'text', [1, 500], 'Перевод']
    
    ));
    
    if (db::get_column("SELECT COUNT(*) FROM `LANGUAGES_SHOW` WHERE `TYPE` = ? AND `PHRASE` = ? LIMIT 1", [tabs($lang['FACT_NAME']), PHRASE]) > 0){
      
      error('Такая фраза в данном языковом пакете уже существует');
      redirect('/admin/system/languages/?add='.tabs($lang['FACT_NAME']));
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/system/languages/?add='.tabs($lang['FACT_NAME']));
    
    }
    
    db::get_add("INSERT INTO `LANGUAGES_SHOW` (`PHRASE`, `TRANSLATE`, `TYPE`) VALUES (?, ?, ?)", [PHRASE, TRANSLATE, esc($lang['FACT_NAME'])]);
    success('Перевод успешно добавлен');
    redirect('/admin/system/languages/?language='.tabs($lang['FACT_NAME']));
    
  }
  
  ?>
  <div class='list-body'>
  <div class='list-menu list-title'>
  <?=lg('Языковой пакет')?> - <?=tabs($lang['NAME'])?> (<?=tabs($lang['FACT_NAME'])?>)
  </div>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/admin/system/languages/?add=<?=tabs($lang['FACT_NAME'])?>'>
  <?=html::textarea(null, 'phrase', null, 'Введите фразу на русском языке:', 'form-control-textarea', 5, 0)?><br /><br />
  <?=html::textarea(null, 'translate', null, 'Введите перевод фразы:', 'form-control-textarea', 5, 0)?><br /><br />
  <?=html::button('ajax-button button', 'ok_phrase', 'plus', 'Добавить')?>
  <a class='button-o' href='/admin/system/languages/?language=<?=tabs($lang['FACT_NAME'])?>'><?=lg('Отмена')?></a>
  </form>
  </div>
  </div>
  <?
    
  back('/admin/system/languages/?language='.tabs($lang['FACT_NAME']));
  acms_footer();
  
}

/*
----------------------
Поиск фраз и переводов
----------------------
*/

if (get('search')){
  
  $lang = db::get_string("SELECT * FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [esc(get('search'))]);
  
  if (!isset($lang['FACT_NAME'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/system/languages/');
    
  }
  
  if (post('search')){
    
    session('search', esc(post('search')));
    redirect(REQUEST_URI);
    
  }
  
  $search = tabs(session('search'));
  
  $column = db::get_column("SELECT COUNT(*) FROM `LANGUAGES_SHOW` WHERE (`PHRASE` LIKE ? OR `TRANSLATE` LIKE ?) AND `TYPE` = ?", ['%'.$search.'%', '%'.$search.'%', tabs($lang['FACT_NAME'])]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
  ?><div id='lang'><div class='list-body'><? 
    
  ?>
  <div class='list-menu list-title'>
  <?=lg('Языковой пакет')?> - <?=tabs($lang['NAME'])?> (<?=tabs($lang['FACT_NAME'])?>). <?=lg('Результаты поиска')?> <span class='count'><?=$column?></span>
  </div>
  <div class='list-menu' style='padding: 3px'>
  <div class='search-main-optimize'>
  <form method='post' class='ajax-form2' action='/admin/system/languages/?search=<?=tabs($lang['FACT_NAME'])?>'>
  <input type='text' name='search' class='search-main' placeholder='<?=lg('Введите фразу или перевод')?>' value='<?=$search?>'> 
  <button class="search-main-button ajax-button-search"><?=icons('search', 20)?></button>
  </form>
  </div>
  </div>
  <?
    
  if ($column == 0){ 
    
    html::empty('Поиск не дал результатов');
  
  }
    
  $data = db::get_string_all("SELECT * FROM `LANGUAGES_SHOW` WHERE (`PHRASE` LIKE ? OR `TRANSLATE` LIKE ?) AND `TYPE` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.$search.'%', '%'.$search.'%', tabs($lang['FACT_NAME'])]);
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>
    <div style='width: 80%; display: inline-block'>
    <b><?=lg('Фраза')?></b>:<br /><?=tabs($list['PHRASE'])?><br /><br />
    <b><?=lg('Перевод')?></b>:<br /><?=tabs($list['TRANSLATE'])?>
    </div>
    <div class='button-optimize-div'></div>
    <button title="<?=lg('Удалить')?>" onclick="request('/admin/system/languages/?delete_tr=<?=$list['ID']?>&<?=TOKEN_URL?>&language=<?=tabs($lang['FACT_NAME'])?>', '#lang')" class='button2 button-optimize'><?=icons('trash', 15, 'fa-fw')?></button>
    <a title="<?=lg('Редактировать')?>" href='/admin/system/languages/?edit_tr=<?=$list['ID']?>&<?=TOKEN_URL?>&page=<?=get('page')?>' class='button3 button-optimize'><?=icons('pencil', 15, 'fa-fw')?></a>
    </div>
    <?
    
  }
  
  get_page('/admin/system/languages/?search='.tabs($lang['FACT_NAME']).'&', $spage, $page, 'list-menu');
  
  ?></div></div><?
    
  back('/admin/system/languages/?language='.tabs($lang['FACT_NAME']));
  acms_footer();
  
}
  
/*
-----------------------
Список фраз и переводов
-----------------------
*/
  
if (get('language')){
  
  $lang = db::get_string("SELECT * FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [esc(get('language'))]);
  
  if (!isset($lang['FACT_NAME'])){
    
    error('Неизвестная ошибка');
    redirect('/admin/system/languages/');
    
  }
  
  ?><div id='lang'><div class='list-body'><?
    
  $column = db::get_column("SELECT COUNT(*) FROM `LANGUAGES_SHOW` WHERE `TYPE` = ?", [tabs($lang['FACT_NAME'])]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
    
  ?>
  <div class='list-menu list-title'>
  <?=lg('Языковой пакет')?> - <?=tabs($lang['NAME'])?> (<?=tabs($lang['FACT_NAME'])?>) <span class='count'><?=$column?></span>
  </div>    
  <div class='list-menu'>
  <a href='/admin/system/languages/?add=<?=tabs($lang['FACT_NAME'])?>' class='button'><?=icons('plus', 17, 'fa-fw')?> <?=lg('Добавить перевод')?></a>
  <a href='/admin/system/languages/?search=<?=tabs($lang['FACT_NAME'])?>' class='button'><?=icons('search', 17, 'fa-fw')?> <?=lg('Найти')?></a>
  </div>
  <?
    
  if ($column == 0){ 
    
    html::empty('Начните переводить слова и фразы на сайте');
  
  }
    
  $data = db::get_string_all("SELECT * FROM `LANGUAGES_SHOW` WHERE `TYPE` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [tabs($lang['FACT_NAME'])]);
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>
    <div style='width: 80%; display: inline-block'>
    <b><?=lg('Фраза')?></b>:<br /><?=tabs($list['PHRASE'])?><br /><br />
    <b><?=lg('Перевод')?></b>:<br /><?=tabs($list['TRANSLATE'])?>
    </div>
    <div class='button-optimize-div'></div>
    <button title="<?=lg('Удалить')?>" onclick="request('/admin/system/languages/?delete_tr=<?=$list['ID']?>&<?=TOKEN_URL?>&language=<?=tabs($lang['FACT_NAME'])?>', '#lang')" class='button2 button-optimize'><?=icons('trash', 15, 'fa-fw')?></button>
    <a title="<?=lg('Редактировать')?>" href='/admin/system/languages/?edit_tr=<?=$list['ID']?>&<?=TOKEN_URL?>&page=<?=get('page')?>' class='button3 button-optimize'><?=icons('pencil', 15, 'fa-fw')?></a>
    </div>
    <?
    
  }
  
  get_page('/admin/system/languages/?language='.tabs($lang['FACT_NAME']).'&', $spage, $page, 'list-menu');
  
  ?></div></div><?
    
  back('/admin/system/languages/');
  acms_footer();
  
}
  
/*
---------------------------
Приоритетный языковой пакет
---------------------------
*/
  
if (get('prioritet')){
  
  get_check_valid();
  
  $pr = db::get_string("SELECT `FACT_NAME` FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [esc(get('prioritet'))]);
  if (isset($pr['FACT_NAME'])){
    
    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'LANGUAGE', ini_data_check($pr['FACT_NAME']));
    success('Выбран новый приоритеный языковой пакет');
    
  }
  
  if ('RU' == get('prioritet')){
    
    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'LANGUAGE', ini_data_check(tabs(get('prioritet'))));
    success('Выбран новый приоритеный языковой пакет');
    
  }
  
  redirect('/admin/system/languages/');
  
}
  
/*
---------------------------------
Включить/отключить языковой пакет
---------------------------------
*/

if (get('on')){
  
  get_check_valid();
  
  $on_lang = db::get_string("SELECT `FACT_NAME` FROM `LANGUAGES` WHERE `FACT_NAME` = ? AND `ACT` = '0' LIMIT 1", [esc(get('on'))]);  
  if (isset($on_lang['FACT_NAME'])){
    
    db::get_set("UPDATE `LANGUAGES` SET `ACT` = '1' WHERE `FACT_NAME` = ? AND `ACT` = '0' LIMIT 1", [esc($on_lang['FACT_NAME'])]);
    
  }
  
}

if (get('off')){
  
  get_check_valid();
  
  $off_lang = db::get_string("SELECT `FACT_NAME` FROM `LANGUAGES` WHERE `FACT_NAME` = ? AND `ACT` = '1' LIMIT 1", [esc(get('off'))]);  
  if (isset($off_lang['FACT_NAME'])){
    
    db::get_set("UPDATE `LANGUAGES` SET `ACT` = '0' WHERE `FACT_NAME` = ? AND `ACT` = '1' LIMIT 1", [esc($off_lang['FACT_NAME'])]);
    
  }
  
}

/*
----------------------------
Редактировать языковой пакет
----------------------------
*/

if (get('edit')){
  
  get_check_valid();
  
  $edit = db::get_string("SELECT * FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [esc(get('edit'))]);  
  if (isset($edit['FACT_NAME'])){
    
    if (post('ok_edit')){
      
      valid::create(array(
        
        'LANG_NAME' => ['name', 'text', [1, 100], 'Имя'],
        'LANG_FACT_NAME' => ['fact_name', 'text', [1, 20], 'Значение языкового пакета в международном формате']
      
      ));
      
      if (LANG_NAME != $edit['NAME']){
        
        if (LANG_NAME == 'Русский' && LANG_FACT_NAME == 'RU' || db::get_column("SELECT COUNT(*) FROM `LANGUAGES` WHERE `NAME` = ? AND `FACT_NAME` = ? LIMIT 1", [LANG_NAME, LANG_FACT_NAME]) > 0){
          
          error('Такой языковой пакет уже существует');
          redirect('/admin/system/languages/?edit='.tabs($edit['FACT_NAME']).'&'.TOKEN_URL);
        
        }
        
      }
      
      if (LANG_FACT_NAME != $edit['FACT_NAME']){
        
        if (LANG_FACT_NAME == 'RU' || db::get_column("SELECT COUNT(*) FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [LANG_FACT_NAME]) > 0){
          
          error('Языковой пакет с таким международным значением уже существует');
          redirect('/admin/system/languages/?edit='.tabs($edit['FACT_NAME']).'&'.TOKEN_URL);
        
        }
        
      }
      
      if (ERROR_LOG == 1){
        
        redirect('/admin/system/languages/?edit='.tabs($edit['FACT_NAME']).'&'.TOKEN_URL);
      
      }     
      
      db::get_set("UPDATE `LANGUAGES` SET `NAME` = ?, `FACT_NAME` = ? WHERE `FACT_NAME` = ? LIMIT 1", [LANG_NAME, LANG_FACT_NAME, esc($edit['FACT_NAME'])]);
      
      success('Изменения успешно приняты');
      redirect('/admin/system/languages/');
    
    }
    
    ?>
    <div class='list-body6'>
    <div class='list-menu list-title'><?=lg('Редактировать языковой пакет')?></div>
    <div class='list-menu'>
    <form method='post' class='ajax-form' action='/admin/system/languages/?edit=<?=tabs($edit['FACT_NAME'])?>&<?=TOKEN_URL?>'>  
    <?=html::input('name', 'От 1 до 100 символов', 'Имя языкового пакета, например: "Русский" или "English":', null, tabs($edit['NAME']), 'form-control-100', 'text', null, 'globe')?>
    <?=html::input('fact_name', 'От 1 до 20 символов', 'Значение языкового пакета в международном формате, например: "RU", "EN":', null, tabs($edit['FACT_NAME']), 'form-control-100', 'text', null, 'globe')?>  
    <?=html::button('ajax-button button', 'ok_edit', 'save', 'Сохранить изменения')?> 
    <a href='/admin/system/languages/' class='button-o'><?=lg('Отмена')?></a>
    </form>
    </div>
    </div>
    <?
    
  }
  
}
  
/*
-------------------------
Удаление языкового пакета
-------------------------
*/ 
  
if (get('delete')){
  
  get_check_valid();
  
  $delete = db::get_string("SELECT `FACT_NAME` FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [esc(get('delete'))]);  
  if (isset($delete['FACT_NAME'])){
    
    if (get('get') == 'delete_ok'){
      
      db::get_set("DELETE FROM `LANGUAGES_SHOW` WHERE `TYPE` = ?", [esc($delete['FACT_NAME'])]);   
      db::get_set("DELETE FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [esc($delete['FACT_NAME'])]);
      
      success('Удаление прошло успешно');
      redirect('/admin/system/languages/');
    
    }
    
    ?>
    <div class='list-body6'><div class='list-menu'>
    <?=lg('Вы действительно хотите удалить языковой пакет? Отменить действие будет невозможно.')?><br /><br />
    <a href='/admin/system/languages/?delete=<?=tabs($delete['FACT_NAME'])?>&get=delete_ok&<?=TOKEN_URL?>' class='button2'><?=lg('Удалить')?></a>
    <a href='/admin/system/languages/' class='button-o'><?=lg('Отмена')?></a>
    </div></div>
    <?
    
  }
  
}
  
/*
--------------------
Новый языковой пакет
--------------------
*/
  
if (get('get') == 'add'){
  
  if (post('ok_lang')){
    
    valid::create(array(
      
      'LANG_NAME' => ['name', 'text', [1, 100], 'Имя'],
      'LANG_FACT_NAME' => ['fact_name', 'text', [1, 20], 'Значение языкового пакета в международном формате']
    
    ));
    
    if (LANG_NAME == 'Русский' && LANG_FACT_NAME == 'RU' || db::get_column("SELECT COUNT(*) FROM `LANGUAGES` WHERE `NAME` = ? AND `FACT_NAME` = ? LIMIT 1", [LANG_NAME, LANG_FACT_NAME]) > 0){
      
      error('Такой языковой пакет уже существует');
      redirect('/admin/system/languages/?get=add');
      
    }
    
    if (LANG_FACT_NAME == 'RU' || db::get_column("SELECT COUNT(*) FROM `LANGUAGES` WHERE `FACT_NAME` = ? LIMIT 1", [LANG_FACT_NAME]) > 0){
      
      error('Языковой пакет с таким международным значением уже существует');
      redirect('/admin/system/languages/?get=add');
      
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/admin/system/languages/?get=add');
    
    }
    
    db::get_add("INSERT INTO `LANGUAGES` (`NAME`, `FACT_NAME`) VALUES (?, ?)", [LANG_NAME, LANG_FACT_NAME]);
    
    success('Языковой пакет успешно создан');
    redirect('/admin/system/languages/');
    
  }
  
  ?>
  <div class='list-body6'>
  <div class='list-menu list-title'><?=lg('Создать новый языковой пакет')?></div>
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/admin/system/languages/?get=add'>  
  <?=html::input('name', 'От 1 до 100 символов', 'Имя языкового пакета, например: "Русский" или "English":', null, null, 'form-control-100', 'text', null, 'globe')?>
  <?=html::input('fact_name', 'От 1 до 20 символов', 'Значение языкового пакета в международном формате, например: "RU", "EN":', null, null, 'form-control-100', 'text', null, 'globe')?>  
  <?=html::button('ajax-button button', 'ok_lang', 'plus', 'Добавить')?> 
  <a href='/admin/system/languages/' class='button-o'><?=lg('Отмена')?></a>
  </form>
  </div>
  </div>
  <?
  
}
  
/*
-----------------------
Список языковых пакетов
-----------------------
*/

?><div id='lang'><div class='list-body'><?
  
$column = db::get_column("SELECT COUNT(*) FROM `LANGUAGES`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
  
?>  
<div class='list-menu'>
<a href='/admin/system/languages/?get=add' class='button'><?=icons('plus', 17, 'fa-fw')?> <?=lg('Создать новый языковой пакет')?></a>
</div>
<?
  
if (get('page') >= 0 && get('page') <= 1 && get('page') != 'end'){
  
  ?>
  <div class='list-menu'>
  <?=icons('globe', 20, 'fa-fw')?> Русский <span class='info blue'>RU</span> <?=icons('lock', 15, 'fa-fw')?>
  <?
    
  if ('RU' == config('LANGUAGE')){
    
    ?>
    <span class='info gray'><?=lg('приоритет')?></span>
    <?
    
  }
  
  ?><div class='button-optimize-div'></div><?
  
  if ('RU' != config('LANGUAGE')){
    
    ?>
    <a title="<?=lg('Сделать приоритетным')?>" href="/admin/system/languages/?prioritet=RU&<?=TOKEN_URL?>" class='button3 button-optimize'><?=icons('lightbulb-o', 16, 'fa-fw')?></a>
    <?
    
  }
    
  ?></div><?
    
}
  
$data = db::get_string_all("SELECT * FROM `LANGUAGES` ORDER BY `NAME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()){
  
  ?>
  <div id='lang<?=$list['FACT_NAME']?>'>
  <div class='list-menu'>
  <?=icons('globe', 20, 'fa-fw')?> <a href='/admin/system/languages/?language=<?=tabs($list['FACT_NAME'])?>'><?=tabs($list['NAME'])?></a> <span class='info blue'><?=tabs($list['FACT_NAME'])?></span>
  <?
    
  if ($list['FACT_NAME'] == config('LANGUAGE')){
    
    ?>
    <span class='info gray'><?=lg('приоритет')?></span>
    <?
    
  }
  
  if ($list['ACT'] == 1){
    
    ?>
    <span class='info green'><?=lg('активен')?></span>
    <?
      
  }else{
    
    ?>
    <span class='info red'><?=lg('не активен')?></span>
    <?

  }
    
  ?><div class='button-optimize-div'></div><?
    
  if ($list['FACT_NAME'] != config('LANGUAGE')){
    
    ?>
    <a title="<?=lg('Сделать приоритетным')?>" href="/admin/system/languages/?prioritet=<?=tabs($list['FACT_NAME'])?>&<?=TOKEN_URL?>" class='button3 button-optimize'><?=icons('lightbulb-o', 16, 'fa-fw')?></a>
    <?
    
  }
    
  if ($list['ACT'] == 1){
    
    ?>
    <button title="<?=lg('Отключить')?>" onclick="request('/admin/system/languages/?off=<?=tabs($list['FACT_NAME'])?>&<?=TOKEN_URL?>', '#lang<?=$list['FACT_NAME']?>')" class='button button-optimize'><?=icons('unlock', 15, 'fa-fw')?></button>
    <?
      
  }else{
    
    ?>
    <button title="<?=lg('Включить')?>" onclick="request('/admin/system/languages/?on=<?=tabs($list['FACT_NAME'])?>&<?=TOKEN_URL?>', '#lang<?=$list['FACT_NAME']?>')" class='button2 button-optimize'><?=icons('lock', 15, 'fa-fw')?></button>
    <?

  }
  
  ?>
  <a title="<?=lg('Удалить')?>" href='/admin/system/languages/?delete=<?=tabs($list['FACT_NAME'])?>&<?=TOKEN_URL?>' class='button2 button-optimize'><?=icons('trash', 15, 'fa-fw')?></a>
  <a title="<?=lg('Редактировать')?>" href='/admin/system/languages/?edit=<?=tabs($list['FACT_NAME'])?>&<?=TOKEN_URL?>' class='button3 button-optimize'><?=icons('pencil', 15, 'fa-fw')?></a> 
  <a title="<?=lg('Фразы и переводы')?>" href="/admin/system/languages/?language=<?=tabs($list['FACT_NAME'])?>" class='button3 button-optimize'><?=icons('list', 16, 'fa-fw')?></a>
  </div>
  </div>
  <?
  
}

get_page('/admin/system/languages/?', $spage, $page, 'list-menu');
  
?></div></div><?

back('/admin/system/');
acms_footer();