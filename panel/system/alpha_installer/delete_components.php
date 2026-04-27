<?php

if (get('delete_components')){
  
  $archive = db::get_string("SELECT * FROM `PANEL_ALPHA_INSTALL` WHERE `ACT` = '1' AND `ID` = ? LIMIT 1", [intval(get('delete_components'))]);
  
  if (!isset($archive['ID']) || $archive['SYSTEM'] == 1){
    
    error('Неверная директива');
    redirect('/admin/system/alpha_installer/');
  
  }
  
  ?>
  <div class='list-body6'><div class='list-menu'>
  <?=lg('Вы действительно хотите удалить компонент')?> <b><?=tabs($archive['NAME'])?></b>? <?=lg('Данное действие невозможно будет отменить')?>.<br /><br />
  <a href='/admin/system/alpha_installer/?delete_components=<?=$archive['ID']?>&get=delete_components_ok' class='button2'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Да, удалить')?></a>
  <a href='/admin/system/alpha_installer/' class='button3'><?=icons('times', 15, 'fa-fw')?> <?=lg('Отменить')?></a>
  </div>
  </div><br />
  <?
    
  if (get('get') == 'delete_components_ok'){
    
    //Выполняем удаление строк/таблиц/столбцов компонента из базы данных, если они есть
    if (is_file(ROOT.'/files/upload/alpha_installer/sql/delete/delete_'.$archive['FACT_NAME'].'.sql')){
      
      db::get_sql_file(ROOT.'/files/upload/alpha_installer/sql/delete/delete_'.$archive['FACT_NAME'].'.sql');
    
    }
    
    $data = db::get_string_all("SELECT `DIR` FROM `PANEL_ALPHA_INSTALL_DATA` WHERE `ID_AI` = ?", [$archive['ID']]);
    while ($list = $data->fetch()){
      
      @unlink(ROOT.'/'.$list['DIR']);
    
    }
    
    db::get_set("DELETE FROM `PANEL_ALPHA_INSTALL_DATA` WHERE `ID_AI` = ?", [$archive['ID']]);
    db::get_set("DELETE FROM `PANEL_ALPHA_INSTALL` WHERE `ID` = ?", [$archive['ID']]);    
    @unlink(ROOT.'/files/upload/alpha_installer/sql/delete/delete_'.$archive['FACT_NAME'].'.sql');
    
    success('Компонент успешно удален из системы');
    redirect('/admin/system/alpha_installer/');
  
  }
  
  back('/admin/system/alpha_installer/');
  acms_footer();
  
}