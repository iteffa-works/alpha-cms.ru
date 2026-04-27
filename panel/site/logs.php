<?php  
html::title('Логи администрации');
acms_header();
access('administration_show');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/site/'><?=lg('Настройки сайта')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Логи администрации')?>
</div> 
<?
  
if (user('ACCESS') == 99) {
  
  ?><div class='list'><?
    
  if (get('delete') && db::get_column("SELECT COUNT(*) FROM `PANEL_LOGS` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]) > 0){
    
    get_check_valid();
    
    db::get_set("DELETE FROM `PANEL_LOGS` WHERE `ID` = ? LIMIT 1", [intval(get('delete'))]);
      
    success('Удаление прошло успешно');
    redirect('/admin/site/logs/');
      
  }
    
  if (get('get') == 'delete_all_ok'){
    
    get_check_valid();
    
    db::get_set("DELETE FROM `PANEL_LOGS`");
      
    success('Удаление прошло успешно');
    redirect('/admin/site/logs/');
      
  }
  
  if (get('get') == 'delete_all'){
    
    get_check_valid();
    
    ?>
    <?=lg('Вы действительно хотите удалить все логи')?>?<br /><br />
    <a href='/admin/site/logs/?get=delete_all_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
    <a href='/admin/site/logs/' class='button-o'><?=lg('Отмена')?></a>
    <?
    
  }else{
    
    ?>
    <a href='/admin/site/logs/?get=delete_all&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить все логи')?></a>
    <?
      
  }
  
  ?></div><?
    
}
  
$column = db::get_column("SELECT COUNT(*) FROM `PANEL_LOGS`");
$spage = SPAGE($column, PAGE_SETTINGS);
$page = PAGE($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

?><div class='list-body'><?

if ($column == 0){ 
  
  html::empty();
  
}

$data = db::get_string_all("SELECT * FROM `PANEL_LOGS` ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()) {
  
  ?>
  <div class='list-menu'>
  <?=text($list['NAME'])?><br />
  <span class='time'><b><?=stime($list['TIME'])?></b></small><br /><br />
  <?=lg('Действие совершил:')?> <a href='/id<?=$list['USER_ID']?>' ajax='no'><?=user::login_mini($list['USER_ID'])?></a>
  <?php if (user('ACCESS') == 99) : ?>
  <br /><br />
  <a href='/admin/site/logs/?delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='button2'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <?php endif ?>
  </div>
  <?
  
}

?></div><?

get_page('/admin/site/logs/?', $spage, $page, 'list');
    
back('/admin/site/');
acms_footer();