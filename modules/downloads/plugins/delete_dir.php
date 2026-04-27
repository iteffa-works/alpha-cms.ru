<?php
  
if (get('get') == 'delete_dir_ok'){
  
  get_check_valid();
  
  if (db::get_column("SELECT COUNT(*) FROM `DOWNLOADS_DIR` WHERE `ID_DIR` = ? LIMIT 1", [$id]) > 0){
    
    error('Директория не может быть удалена, пока в ней есть хотябы одна внутренняя директория');
    redirect('/m/downloads/?id='.$id);
  
  }
  
  db::get_set("DELETE FROM `DOWNLOADS` WHERE `ID_DIR` = ?", [$id]);
  db::get_set("DELETE FROM `DOWNLOADS_DIR` WHERE `ID` = ? LIMIT 1", [$id]);
  
  if (access('downloads', null) == true){
    
    logs('Загрузки - удаление категории [url=/m/downloads/?id='.$id.']'.$dir['NAME'].'[/url]', user('ID'));
  
  }
  
  success('Удаление прошло успешно');
  redirect('/m/downloads/?id='.$id_dir);

}

if (get('get') == 'delete_dir'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить категорию')?> <b><?=tabs($dir['NAME'])?></b>?<br /><br />
  <a href='/m/downloads/?id=<?=$id?>&get=delete_dir_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/downloads/?id=<?=$id?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}