<?php
html::title('Разделы форума');
acms_header(); 

if (config('PRIVATE_FORUM') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}
  
?> 
<div class='menu-nav-content'>
  
<a class='menu-nav' href='/m/forum/?'>
<?=lg('Все')?>
</a>
    
<a class='menu-nav h' href='/m/forum/sc/'>
<?=lg('Разделы')?>
</a>
  
<a class='menu-nav' href='/m/forum/?get=act'>
<?=lg('Актуальные')?>
</a>  
    
<a class='menu-nav' href='/m/forum/?get=rating'>
<?=lg('ТОП')?>
</a>
    
<a class='menu-nav' href='/m/forum/?get=new'>
<?=lg('Новые')?>
</a>
  
<?php if (user('ID') > 0) { ?>  
<a class='menu-nav' href='/m/forum/users/?id=<?=user('ID')?>'>
<?=lg('Мои')?>
</a>
<?php } ?>  
  
</div>
<?
  
/*
----------------
Показ подраздела
----------------
*/ 
  
if (get('id_sub')){
  
  $scsub = db::get_string("SELECT * FROM `FORUM_SUB_SECTION` WHERE `ID` = ? LIMIT 1", [intval(get('id_sub'))]);
  
  if (isset($scsub['ID'])){
    
    require_once (ROOT.'/modules/forum/plugins/private_sub_section.php');
    
    if (access('forum', null) == true){
      
      /*
      -------------------
      Удаление подраздела
      -------------------
      */
      
      if (get('get') == 'delete_sub_ok'){
        
        get_check_valid();
        
        $data = db::get_string_all("SELECT * FROM `FORUM_THEM` WHERE `SUB_SECTION_ID` = ?", [$scsub['ID']]);
        while ($list = $data->fetch()){
          
          $data2 = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'forum_comments']);
          while ($list2 = $data2->fetch()) {
            
            db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list2['ID'], 'forum_comments']);
            db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list2['ID'], 'forum_comments']);
            db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list2['ID']]);
          
          }
          
          db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'forum']);
          db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$list['ID'], 'forum']);
          db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'forum']);
          db::get_set("DELETE FROM `FORUM_THEM` WHERE `ID` = ?", [$list['ID']]); 
        
        }
        
        db::get_set("DELETE FROM `FORUM_SUB_SECTION` WHERE `ID` = ?", [$scsub['ID']]);
        
        logs('Форум - удаление подраздела', user('ID'));
        
        success('Удаление прошло успешно');
        redirect('/m/forum/sc/?id='.$scsub['SECTION_ID']);
      
      }
      
      if (get('get') == 'delete_sub'){
        
        get_check_valid();
        
        ?>
        <div class='list'>
        <?=lg('Вы действительно хотите удалить подраздел')?> <b><?=tabs($scsub['NAME'])?></b>?<br /><br />
        <a href='/m/forum/sc/?id_sub=<?=$scsub['ID']?>&get=delete_sub_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
        <a href='/m/forum/sc/?id_sub=<?=$scsub['ID']?>' class='button-o'><?=lg('Отмена')?></a>
        </div>
        <?
  
      }
      
      /*
      --------------------
      Редактировать раздел
      --------------------
      */
      
      if (get('get') == 'edit_sub'){
        
        get_check_valid();
        
        if (post('ok_forum_edit_sc_sub')){
          
          valid::create(array(
            
            'SC_SUB_NAME' => ['name', 'text', [3, 120], 'Название', 0],
            'SC_SUB_MESSAGE' => ['message', 'text', [0, 200], 'Описание', 0],
            'SC_SUB_RATING' => ['rating', 'number', [0, 999999], 'Рейтинг', 0],
            'SC_SUB_PRIVATE' => ['private', 'number', [0, 5], 'Приватность'],
            'SC_SUB_SC' => ['sc', 'number', [0, 999999], 'Раздел']
          
          ));
          
          if ($scsub['NAME'] != SC_SUB_NAME && db::get_column("SELECT COUNT(*) FROM `FORUM_SUB_SECTION` WHERE `NAME` = ? AND `SECTION_ID` = ? LIMIT 1", [SC_SUB_NAME, $scsub['ID']]) == 1){
            
            error('Подраздел с таким названием уже существует');
            redirect('/m/forum/sc/?id_sub='.$scsub['ID'].'&get=edit_sub&'.TOKEN_URL);
          
          }
          
          if (ERROR_LOG == 1){
            
            redirect('/m/forum/sc/?id_sub='.$scsub['ID'].'&get=edit_sub&'.TOKEN_URL);
          
          }
          
          db::get_set("UPDATE `FORUM_SUB_SECTION` SET `NAME` = ?, `MESSAGE` = ?, `SECTION_ID` = ?, `PRIVATE` = ?, `RATING` = ? WHERE `ID` = ? LIMIT 1", [SC_SUB_NAME, SC_SUB_MESSAGE, SC_SUB_SC, SC_SUB_PRIVATE, SC_SUB_RATING, $scsub['ID']]);
          
          logs('Форум - редактирование [url=/m/forum/sc/?id_sub='.$scsub['ID'].']подраздела[/url]', user('ID'));
          
          success('Подраздел успешно создан');
          redirect('/m/forum/sc/?id_sub='.$scsub['ID']);
        
        }
        
        ?>
        <div class='list'>
        <form method='post' class='ajax-form' action='/m/forum/sc/?id_sub=<?=$scsub['ID']?>&get=edit_sub&<?=TOKEN_URL?>'>
        <?          
        html::input('name', 'Название', null, null, tabs($scsub['NAME']), 'form-control-100', 'text', null, 'list');
        html::textarea(tabs($scsub['MESSAGE']), 'message', 'Введите описание (не обязательно)', null, 'form-control-textarea', 9, 0);  
        ?><br /><br /><?
        html::select('rating', array(
          0 => ['0', ($scsub['RATING'] == 0 ? "selected" : null)], 
          5 => ['5', ($scsub['RATING'] == 5 ? "selected" : null)], 
          10 => ['10', ($scsub['RATING'] == 10 ? "selected" : null)], 
          15 => ['15', ($scsub['RATING'] == 15 ? "selected" : null)], 
          20 => ['20', ($scsub['RATING'] == 20 ? "selected" : null)], 
          25 => ['25', ($scsub['RATING'] == 25 ? "selected" : null)], 
          30 => ['30', ($scsub['RATING'] == 30 ? "selected" : null)], 
          35 => ['35', ($scsub['RATING'] == 35 ? "selected" : null)], 
          40 => ['40', ($scsub['RATING'] == 40 ? "selected" : null)], 
          45 => ['45', ($scsub['RATING'] == 45 ? "selected" : null)], 
          50 => ['50', ($scsub['RATING'] == 50 ? "selected" : null)], 
          60 => ['60', ($scsub['RATING'] == 60 ? "selected" : null)], 
          70 => ['70', ($scsub['RATING'] == 70 ? "selected" : null)], 
          80 => ['80', ($scsub['RATING'] == 80 ? "selected" : null)], 
          90 => ['90', ($scsub['RATING'] == 90 ? "selected" : null)], 
          100 => ['100', ($scsub['RATING'] == 100 ? "selected" : null)], 
          120 => ['120', ($scsub['RATING'] == 120 ? "selected" : null)], 
          140 => ['140', ($scsub['RATING'] == 140 ? "selected" : null)], 
          160 => ['160', ($scsub['RATING'] == 160 ? "selected" : null)], 
          180 => ['180', ($scsub['RATING'] == 180 ? "selected" : null)], 
          200 => ['200', ($scsub['RATING'] == 200 ? "selected" : null)], 
          250 => ['250', ($scsub['RATING'] == 250 ? "selected" : null)], 
          300 => ['300', ($scsub['RATING'] == 300 ? "selected" : null)], 
          350 => ['350', ($scsub['RATING'] == 350 ? "selected" : null)], 
          400 => ['400', ($scsub['RATING'] == 400 ? "selected" : null)], 
          450 => ['450', ($scsub['RATING'] == 450 ? "selected" : null)], 
          500 => ['500', ($scsub['RATING'] == 500 ? "selected" : null)], 
          1000 => ['1000', ($scsub['RATING'] == 1000 ? "selected" : null)], 
          2000 => ['2000', ($scsub['RATING'] == 2000 ? "selected" : null)], 
          3000 => ['3000', ($scsub['RATING'] == 3000 ? "selected" : null)]
        ), 'Уровень рейтинга для доступа', 'form-control-100-modify-select', 'line-chart');
        $array = array();
        $data = db::get_string_all("SELECT * FROM `FORUM_SECTION` ORDER BY `ID` DESC");  
        while ($list = $data->fetch()){
          
          $array[$list['ID']] = [$list['NAME'], ($scsub['ID'] == $list['ID'] ? "selected" : null)];
        
        }
        html::select('sc', $array, 'Раздел', 'form-control-100-modify-select', 'list');
        html::select('private', array(
          0 => ['Всем', ($scsub['PRIVATE'] == 0 ? "selected" : null)], 
          1 => ['Только администрации', ($scsub['PRIVATE'] == 1 ? "selected" : null)]
        ), 'Доступ', 'form-control-100-modify-select', 'lock');
        html::button('button ajax-button', 'ok_forum_edit_sc_sub', 'save', 'Сохранить');        
        ?>
        <a class='button-o' href='/m/forum/sc/?id_sub=<?=$scsub['ID']?>'><?=lg('Отмена')?></a>
        </form>
        </div>
        <?
        
        back('/m/forum/sc/?id_sub='.$scsub['ID']);
        acms_footer();
        
      }
      
    }
      
    if (user('ID') > 0){
      
      ?>
      <div class='list'>
      <a href='/m/forum/add_them/?id=<?=$scsub['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15)?> <?=lg('Создать тему')?></a>
      <?
        
      if (access('forum', null) == true){
        
        ?>
        <a href='/m/forum/sc/?id_sub=<?=$scsub['ID']?>&get=edit_sub&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15)?> <?=lg('Редактировать')?></a>
        <a href='/m/forum/sc/?id_sub=<?=$scsub['ID']?>&get=delete_sub&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15)?> <?=lg('Удалить')?></a>  
        <?
          
      }
      
      ?>
      </div>
      <div class='list'>
      <?=lg('Темы подраздела')?> "<b><?=lg(tabs($scsub['NAME']))?></b>"
      </div>
      <?
        
    }
    
    $column = db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE `SUB_SECTION_ID` = ?", [$scsub['ID']]);
    $spage = spage($column, PAGE_SETTINGS);
    $page = page($spage);
    $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
    
    if ($column == 0){ 
      
      html::empty('Пока пусто');
    
    }else{
      
      ?><div class='list-body'><?
      
    }

    $data = db::get_string_all("SELECT * FROM `FORUM_THEM` WHERE `SUB_SECTION_ID` = ? ORDER BY `SECURE` = '1' DESC, `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$scsub['ID']]);
    while ($list = $data->fetch()) {
      
      require (ROOT.'/modules/forum/plugins/list.php');
      echo $forum_list;
    
    }
    
    if ($column > 0){ 
      
      ?></div><?
      
    }
    
    get_page('/m/forum/sc/?id_sub='.$scsub['ID'].'&', $spage, $page, 'list'); 
    
    back('/m/forum/sc/?id='.$scsub['SECTION_ID']);
    acms_footer();
    
  }
  
}
  
/*
-------------
Показ раздела
-------------
*/
  
if (get('id')){
  
  $id = intval(get('id'));  
  $sc = db::get_string("SELECT `NAME`,`MESSAGE` FROM `FORUM_SECTION` WHERE `ID` = ? LIMIT 1", [$id]);
  
  if (isset($sc['NAME'])){
    
    if (access('forum', null) == true){
      
      /*
      ------------------
      Добавить подраздел
      ------------------
      */
      
      if (get('get') == 'add_sub'){
        
        get_check_valid();
        
        if (post('ok_forum_add_sc_sub')){
          
          valid::create(array(
            
            'SC_SUB_NAME' => ['name', 'text', [3, 120], 'Название', 0],
            'SC_SUB_MESSAGE' => ['message', 'text', [0, 200], 'Описание', 0],
            'SC_SUB_RATING' => ['rating', 'number', [0, 999999], 'Рейтинг', 0],
            'SC_SUB_PRIVATE' => ['private', 'number', [0, 5], 'Приватность'],
            'SC_SUB_SC' => ['sc', 'number', [0, 999999], 'Раздел']
          
          ));
          
          if (db::get_column("SELECT COUNT(*) FROM `FORUM_SUB_SECTION` WHERE `NAME` = ? AND `SECTION_ID` = ? LIMIT 1", [SC_SUB_NAME, $id]) == 1){
            
            error('Подраздел с таким названием уже существует');
            redirect('/m/forum/sc/?id='.$id.'&get=add_sub&'.TOKEN_URL);
          
          }
          
          if (ERROR_LOG == 1){
            
            redirect('/m/forum/sc/?id='.$id.'&get=add_sub&'.TOKEN_URL);
          
          }
          
          $ID = db::get_add("INSERT INTO `FORUM_SUB_SECTION` (`NAME`, `MESSAGE`, `SECTION_ID`, `PRIVATE`, `RATING`) VALUES (?, ?, ?, ?, ?)", [SC_SUB_NAME, SC_SUB_MESSAGE, SC_SUB_SC, SC_SUB_PRIVATE, SC_SUB_RATING]);
          
          logs('Форум - добавление [url=/m/forum/sc/?id_sub='.$ID.']подраздела[/url]', user('ID'));
          
          success('Подраздел успешно создан');
          redirect('/m/forum/sc/?id='.SC_SUB_SC);
        
        }
        
        ?>
        <div class='list'>
        <form method='post' class='ajax-form' action='/m/forum/sc/?id=<?=$id?>&get=add_sub&<?=TOKEN_URL?>'>
        <?          
        html::input('name', 'Название', null, null, null, 'form-control-100', 'text', null, 'list');
        html::textarea(null, 'message', 'Введите описание (не обязательно)', null, 'form-control-textarea', 9, 0);  
        ?><br /><br /><?
        html::select('rating', array(
          0 => ['0', 0], 
          5 => ['5', 5], 
          10 => ['10', 10], 
          15 => ['15', 15], 
          20 => ['20', 20], 
          25 => ['25', 25], 
          30 => ['30', 30], 
          35 => ['35', 35], 
          40 => ['40', 40], 
          45 => ['45', 45], 
          50 => ['50', 50], 
          60 => ['60', 60], 
          70 => ['70', 70], 
          80 => ['80', 80], 
          90 => ['90', 90], 
          100 => ['100', 100], 
          120 => ['120', 120], 
          140 => ['140', 140], 
          160 => ['160', 160], 
          180 => ['180', 180], 
          200 => ['200', 200], 
          250 => ['250', 250], 
          300 => ['300', 300], 
          350 => ['350', 350], 
          400 => ['400', 400], 
          450 => ['450', 450], 
          500 => ['500', 500], 
          1000 => ['1000', 1000], 
          2000 => ['2000', 2000], 
          3000 => ['3000', 3000]
        ), 'Уровень рейтинга для доступа', 'form-control-100-modify-select', 'line-chart');
        $array = array();
        $data = db::get_string_all("SELECT * FROM `FORUM_SECTION` ORDER BY `ID` DESC");  
        while ($list = $data->fetch()){
          
          $array[$list['ID']] = [$list['NAME'], ($id == $list['ID'] ? "selected" : null)];
        
        }
        html::select('sc', $array, 'Раздел', 'form-control-100-modify-select', 'list');
        html::select('private', array(
          0 => ['Всем', 0], 
          1 => ['Только администрации', 1]
        ), 'Доступ', 'form-control-100-modify-select', 'lock');
        html::button('button ajax-button', 'ok_forum_add_sc_sub', 'plus', 'Добавить');        
        ?>
        <a class='button-o' href='/m/forum/sc/?id=<?=$id?>'><?=lg('Отмена')?></a>
        </form>
        </div>
        <?
        
        back('/m/forum/sc/?id='.$id);
        acms_footer();
        
      }
      
      /*
      --------------------
      Редактировать раздел
      --------------------
      */
      
      if (get('get') == 'edit'){
        
        get_check_valid();
        
        if (post('ok_forum_edit_sc')){
          
          valid::create(array(
            
            'SC_NAME' => ['name', 'text', [3, 120], 'Название', 0],
            'SC_MESSAGE' => ['message', 'text', [0, 200], 'Описание', 0]
          
          ));
          
          if (SC_NAME != $sc['NAME'] && db::get_column("SELECT COUNT(*) FROM `FORUM_SECTION` WHERE `NAME` = ? LIMIT 1", [SC_NAME]) == 1){
            
            error('Раздел с таким названием уже существует');
            redirect('/m/forum/sc/?id='.$id.'&get=edit&'.TOKEN_URL);
          
          }
          
          if (ERROR_LOG == 1){
            
            redirect('/m/forum/sc/?id='.$id.'&get=edit&'.TOKEN_URL);
          
          }
          
          db::get_set("UPDATE `FORUM_SECTION` SET `NAME` = ?, `MESSAGE` = ? WHERE `ID` = ? LIMIT 1", [SC_NAME, SC_MESSAGE, $id]);          
          
          logs('Форум - редактирование [url=/m/forum/sc/?id='.$id.']раздела[/url]', user('ID'));
          
          success('Изменения успешно приняты');
          redirect('/m/forum/sc/?id='.$id);
        
        }
        
        ?>
        <div class='list'>
        <form method='post' class='ajax-form' action='/m/forum/sc/?id=<?=$id?>&get=edit&<?=TOKEN_URL?>'>
        <?          
        html::input('name', 'Название', null, null, tabs($sc['NAME']), 'form-control-100', 'text', null, 'list');
        html::textarea(tabs($sc['MESSAGE']), 'message', 'Введите описание (не обязательно)', null, 'form-control-textarea', 9, 0);  
        ?><br /><br /><?
        html::button('button ajax-button', 'ok_forum_edit_sc', 'save', 'Сохранить');        
        ?>
        <a class='button-o' href='/m/forum/sc/?id=<?=$id?>'><?=lg('Отмена')?></a>
        </form>
        </div>
        <?
        
        back('/m/forum/sc/?id='.$id);
        acms_footer();
        
      }
      
      /*
      ----------------
      Удаление раздела
      ----------------
      */
      
      if (get('get') == 'delete_ok'){
        
        get_check_valid();
        
        $data = db::get_string_all("SELECT * FROM `FORUM_SUB_SECTION` WHERE `SECTION_ID` = ?", [$id]);
        while ($list = $data->fetch()){
          
          $data2 = db::get_string_all("SELECT * FROM `FORUM_THEM` WHERE `SUB_SECTION_ID` = ?", [$list['ID']]);
          while ($list2 = $data2->fetch()){
            
            $data3 = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list2['ID'], 'forum_comments']);
            while ($list3 = $data3->fetch()) {
              
              db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list3['ID'], 'forum_comments']);
              db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list3['ID'], 'forum_comments']);
              db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list3['ID']]);
            
            }
            
            db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list2['ID'], 'forum']);
            db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$list2['ID'], 'forum']);
            db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list2['ID'], 'forum']);
            db::get_set("DELETE FROM `FORUM_THEM` WHERE `ID` = ?", [$list2['ID']]); 
          
          }
          
        }
        
        db::get_set("DELETE FROM `FORUM_SUB_SECTION` WHERE `SECTION_ID` = ?", [$id]);
        db::get_set("DELETE FROM `FORUM_SECTION` WHERE `ID` = ? LIMIT 1", [$id]);
        
        logs('Форум - удаление раздела', user('ID'));
        
        success('Удаление прошло успешно');
        redirect('/m/forum/sc/');
      
      }
      
      if (get('get') == 'delete'){
        
        get_check_valid();
        
        ?>
        <div class='list'>
        <?=lg('Вы действительно хотите удалить раздел')?> <b><?=tabs($sc['NAME'])?></b>?<br /><br />
        <a href='/m/forum/sc/?id=<?=$id?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
        <a href='/m/forum/sc/?id=<?=$id?>' class='button-o'><?=lg('Отмена')?></a>
        </div>
        <?
  
      }
      
      ?>
      <div class='list'>
      <a href='/m/forum/sc/?id=<?=$id?>&get=add_sub&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15)?> <?=lg('Добавить подраздел')?></a>
      <a href='/m/forum/sc/?id=<?=$id?>&get=edit&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15)?> <?=lg('Редактировать')?></a>
      <a href='/m/forum/sc/?id=<?=$id?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15)?> <?=lg('Удалить')?></a>  
      </div>
      <?
      
    }
    
    ?>
    <div class='list'>
    <?=lg('Подразделы')?> "<b><?=lg(tabs($sc['NAME']))?></b>"
    </div>
    <?
    
    $column = db::get_column("SELECT COUNT(*) FROM `FORUM_SUB_SECTION` WHERE `SECTION_ID` = ?", [$id]);
    $spage = spage($column, PAGE_SETTINGS);
    $page = page($spage);
    $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
    
    if ($column == 0){ 
      
      html::empty('Пока пусто');
    
    }else{
      
      ?><div class='list-body'><?
      
    }

    $data = db::get_string_all("SELECT * FROM `FORUM_SUB_SECTION` WHERE `SECTION_ID` = ? ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$id]);
    while ($list = $data->fetch()) {

      $thems = db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE `SUB_SECTION_ID` = ?", [$list['ID']]);
      $comments = DB::GET_COLUMN("SELECT COUNT(*) FROM `COMMENTS` WHERE `SUB_OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'forum_comments']);
      
      ?>
      <a href='/m/forum/sc/?id_sub=<?=$list['ID']?>'>
      <div class='list-menu hover'>
      <b><?=lg(tabs($list['NAME']))?></b> <span class='count'><?=$comments?>/<?=$thems?></span><br />
      <font color='#7B8285'><small><?=text(lg($list['MESSAGE']))?></small></font>
      </div>
      </a>
      <?
    
    }
    
    if ($column > 0){ 
      
      ?></div><?
      
    }
    
    get_page('/m/forum/sc/?id='.$id.'&', $spage, $page, 'list'); 
    
    back('/m/forum/sc/');
    acms_footer();
    
  }
  
}
  
/*
---------------
Добавить раздел
---------------
*/
  
if (access('forum', null) == true && get('get') == 'add'){
  
  get_check_valid();
  
  if (post('ok_forum_sc')){
    
    valid::create(array(
      
      'SC_NAME' => ['name', 'text', [3, 120], 'Название', 0],
      'SC_MESSAGE' => ['message', 'text', [0, 200], 'Описание', 0]
    
    ));
    
    if (db::get_column("SELECT COUNT(*) FROM `FORUM_SECTION` WHERE `NAME` = ? LIMIT 1", [SC_NAME]) == 1){
      
      error('Раздел с таким названием уже существует');
      redirect('/m/forum/sc/?get=add&'.TOKEN_URL);
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/m/forum/sc/?get=add&'.TOKEN_URL);
    
    }
    
    $ID = db::get_add("INSERT INTO `FORUM_SECTION` (`NAME`, `MESSAGE`) VALUES (?, ?)", [SC_NAME, SC_MESSAGE]);
    
    if (access('forum', null) == true){
      
      logs('Форум - создание нового [url=/m/forum/sc/?id='.$ID.']раздела[/url]', user('ID'));
    
    }
    
    success('Раздел успешно добавлен');
    redirect('/m/forum/sc/');
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/m/forum/sc/?get=add&<?=TOKEN_URL?>'>
  <?
  
  html::input('name', 'Название', null, null, null, 'form-control-100', 'text', null, 'list');
  html::textarea(null, 'message', 'Введите описание (не обязательно)', null, 'form-control-textarea', 9, 0);  
  ?><br /><br /><?
  html::button('button ajax-button', 'ok_forum_sc', 'plus', 'Добавить');
  
  ?>
  <a class='button-o' href='/m/forum/sc/'><?=lg('Отмена')?></a>
  </form>
  </div>
  <?
  
  back('/m/forum/sc/');
  acms_footer();
  
}
  
/*
---------------
Список разделов
---------------
*/
  
require_once (ROOT.'/modules/search/plugins/form/forum.php'); 
  
if (access('forum', null) == true || user('ID') > 0){
  
  ?>
  <div class='list'>
  <a href='/m/forum/add_them/?get=section&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15)?> <?=lg('Создать тему')?></a>
  <?php if (access('forum', null) == true) : ?>
  <a href='/m/forum/sc/?get=add&<?=TOKEN_URL?>' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить раздел')?></a>
  <?php endif ?>
  </div>
  <?
  
}

$column = db::get_column("SELECT COUNT(*) FROM `FORUM_SECTION`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока нет разделов');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `FORUM_SECTION` ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  ?>
  <a href='/m/forum/sc/?id=<?=$list['ID']?>'>
  <div class='list-menu hover'>
  <?=icons('angle-double-right', 23, 'fa-fw')?> <b><?=lg(tabs($list['NAME']))?></b><br />
  <font color='#7B8285'><small><?=text(lg($list['MESSAGE']))?></small></font>
  </div>
  </a>
  <?
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/forum/sc/?', $spage, $page, 'list'); 

back('/', 'На главную');
acms_footer();