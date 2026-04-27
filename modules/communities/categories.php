<?php
acms_header('Категории сообществ');
is_active_module('PRIVATE_COMMUNITIES');
  
?> 
<div class='menu-nav-content'>  
<a class='menu-nav' href='/m/communities/?'>
<?=lg('Все')?>
</a>    
<a class='menu-nav h' href='/m/communities/categories/'>
<?=lg('Категории')?>
</a>    
<a class='menu-nav' href='/m/communities/?get=rating'>
<?=lg('ТОП')?>
</a>    
<a class='menu-nav' href='/m/communities/?get=new'>
<?=lg('Новые')?>
</a>  
<?php if (user('ID') > 0) : ?>  
<a class='menu-nav' href='/m/communities/users/?id=<?=user('ID')?>'>
<?=lg('Мои')?>
</a>
<?php endif ?>
</div>
<?
  
/*
---------------
Показ категории
---------------
*/
  
if (get('id')){
  
  $id = intval(get('id'));  
  $cat = db::get_string("SELECT `NAME` FROM `COMMUNITIES_CATEGORIES` WHERE `ID` = ? LIMIT 1", [$id]);
  
  if (isset($cat['NAME'])){
    
    if (access('communities', null) == true){
      
      /*
      -----------------------
      Редактировать категорию
      -----------------------
      */
      
      if (get('get') == 'edit'){
        
        get_check_valid();
        
        if (post('ok_comm_edit_cat')){
          
          valid::create(array(
            
            'CAT_NAME' => ['name', 'text', [3, 200], 'Название', 0]
          
          ));
          
          if (CAT_NAME != $cat['NAME'] && db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_CATEGORIES` WHERE `NAME` = ? LIMIT 1", [CAT_NAME]) == 1){
            
            error('Категория с таким названием уже существует');
            redirect('/m/communities/categories/?id='.$id.'&get=edit&'.TOKEN_URL);
          
          }
          
          if (ERROR_LOG == 1){
            
            redirect('/m/communities/categories/?id='.$id.'&get=edit&'.TOKEN_URL);
          
          }
          
          db::get_set("UPDATE `COMMUNITIES_CATEGORIES` SET `NAME` = ? WHERE `ID` = ? LIMIT 1", [CAT_NAME, $id]);
          
          if (access('communities', null) == true){
            
            logs('Сообщества - редактирование [url=/m/communities/categories/?id='.$id.']категории[/url]', user('ID'));
          
          }
          
          success('Изменения успешно приняты');
          redirect('/m/communities/categories/?id='.$id);
        
        }
        
        ?>
        <div class='list'>
        <form method='post' class='ajax-form' action='/m/communities/categories/?id=<?=$id?>&get=edit&<?=TOKEN_URL?>'>
        <?          
        html::input('name', 'Название', null, null, tabs($cat['NAME']), 'form-control-100', 'text', null, 'users');
        html::button('button ajax-button', 'ok_comm_edit_cat', 'plus', 'Добавить');        
        ?>
        <a class='button-o' href='/m/communities/categories/?id=<?=$id?>'><?=lg('Отмена')?></a>
        </form>
        </div>
        <?
        
        back('/m/communities/categories/?id='.$id);
        acms_footer();
        
      }
      
      /*
      ------------------
      Удаление категории
      ------------------
      */
      
      if (get('get') == 'delete_ok'){
        
        get_check_valid();
        
        db::get_set("DELETE FROM `COMMUNITIES_CATEGORIES` WHERE `ID` = ? LIMIT 1", [$id]);
        
        if (access('communities', null) == true){
          
          logs('Сообщества - удаление категории', user('ID'));
        
        }
        
        success('Удаление прошло успешно');
        redirect('/m/communities/categories/');
      
      }
      
      if (get('get') == 'delete'){
        
        get_check_valid();
        
        ?>
        <div class='list'>
        <?=lg('Вы действительно хотите удалить категорию')?> <b><?=tabs($cat['NAME'])?></b>?<br /><br />
        <a href='/m/communities/categories/?id=<?=$id?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
        <a href='/m/communities/categories/?id=<?=$id?>' class='button-o'><?=lg('Отмена')?></a>
        </div>
        <?
  
      }
      
      ?>
      <div class='list'>
      <a href='/m/communities/categories/?id=<?=$id?>&get=edit&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15)?> <?=lg('Редактировать')?></a>
      <a href='/m/communities/categories/?id=<?=$id?>&get=delete&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15)?> <?=lg('Удалить')?></a>  
      </div>
      <?
      
    }
    
    ?>
    <div class='list'>
    <?=lg('Сообщества категории')?> "<b><?=lg(tabs($cat['NAME']))?></b>"
    </div>
    <?
    
    $column = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES` WHERE `ID_CATEGORY` = ? AND `BAN` = '0'", [$id]);
    $spage = spage($column, PAGE_SETTINGS);
    $page = page($spage);
    $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;
    
    if ($column == 0){ 
      
      html::empty('Пока пусто');
    
    }else{
      
      ?><div class='list-body'><?
      
    }

    $data = db::get_string_all("SELECT * FROM `COMMUNITIES` WHERE `ID_CATEGORY` = ? AND `BAN` = '0' ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$id]);
    while ($list = $data->fetch()) {
      
      require (ROOT.'/modules/communities/plugins/list.php');
      echo $comm_list;
    
    }
    
    if ($column > 0){ 
      
      ?></div><?
      
    }
    
    get_page('/m/communities/categories/?id='.$id.'&', $spage, $page, 'list'); 
    
    back('/m/communities/categories/');
    acms_footer();
    
  }
  
}
  
/*
------------------
Добавить категорию
------------------
*/
  
if (access('communities', null) == true && get('get') == 'add'){
  
  get_check_valid();
  
  if (post('ok_comm_cat')){
    
    valid::create(array(
      
      'CAT_NAME' => ['name', 'text', [3, 200], 'Название', 0]
    
    ));
    
    if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_CATEGORIES` WHERE `NAME` = ? LIMIT 1", [CAT_NAME]) == 1){
      
      error('Категория с таким названием уже существует');
      redirect('/m/communities/categories/?get=add&'.TOKEN_URL);
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/m/communities/categories/?get=add&'.TOKEN_URL);
    
    }
    
    $ID = db::get_add("INSERT INTO `COMMUNITIES_CATEGORIES` (`NAME`) VALUES (?)", [CAT_NAME]);
    
    if (access('communities', null) == true){
      
      logs('Сообщества - создание новой [url=/m/communities/categories/?id='.$ID.']категории[/url]', user('ID'));
    
    }
    
    success('Категория успешно добавлена');
    redirect('/m/communities/categories/');
    
  }
  
  ?>
  <div class='list'>
  <form method='post' class='ajax-form' action='/m/communities/categories/?get=add&<?=TOKEN_URL?>'>
  <?
  
  html::input('name', 'Название', null, null, null, 'form-control-100', 'text', null, 'users');
  html::button('button ajax-button', 'ok_comm_cat', 'plus', 'Добавить');
  
  ?>
  <a class='button-o' href='/m/communities/categories/'><?=lg('Отмена')?></a>
  </form>
  </div>
  <?
  
  back('/m/communities/categories/');
  acms_footer();
  
}
  
/*
----------------
Список категорий
----------------
*/
  
require_once (ROOT.'/modules/search/plugins/form/communities.php'); 
  
if (access('communities', null) == true){
  
  ?>
  <div class='list'>
  <a href='/m/communities/categories/?get=add&<?=TOKEN_URL?>' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Добавить категорию')?></a>
  </div>
  <?
  
}

$column = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_CATEGORIES`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Пока нет категорий');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `COMMUNITIES_CATEGORIES` ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  ?>
  <a href='/m/communities/categories/?id=<?=$list['ID']?>'>
  <div class='list-menu hover'>
  <?=icons('users', 17, 'fa-fw')?> <?=lg(tabs($list['NAME']))?> <span class='count'><?=db::get_column("SELECT COUNT(*) FROM `COMMUNITIES` WHERE `ID_CATEGORY` = ? AND `PRIVATE` = ?", [$list['ID'], 0])?></span>
  </div>
  </a>
  <?
  
}

if ($column > 0){ 
  
  ?></div><?
  
}

get_page('/m/communities/categories/?', $spage, $page, 'list'); 

back('/', 'На главную');
acms_footer();