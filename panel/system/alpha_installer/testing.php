<?php
  
@unlink(ROOT.'/files/upload/alpha_installer/set/set.php');

/*
-------------------------------------
Проверка доступен ли архив для чтения
-------------------------------------
*/
  
$zip = new ZipArchive();
  
if ($zip->open(ROOT.'/files/upload/alpha_installer/'.$archive['ID'].'.'.$archive['EXT']) === false){
  
  error('Не удалось открыть архив с установкой');
  redirect('/admin/system/alpha_installer/');

}

$zip->extractTo(ROOT.'/', array('files/upload/alpha_installer/set/set.php'));

/*
-----------------------------------
Небольшая проверка на совместимость
-----------------------------------
*/

if (!is_file(ROOT.'/files/upload/alpha_installer/set/set.php')){
  
  error('Критическая ошибка: данный компонент не подходит для AlphaCMS текущей версии. Его установка приведет к ошибкам на сайте');
  redirect('/admin/system/alpha_installer/');

}

require_once (ROOT.'/files/upload/alpha_installer/set/set.php');

if (VERSION_COMPONENTS < intval(str_replace('.', '', config('ACMS_VERSION')))) {
  
  error('Критическая ошибка: данный компонент устарел и не подходит для AlphaCMS текущей версии. Его установка может привести к ошибкам на сайте. Скачайте более свежую версию компонента с официального сайта');
  redirect('/admin/system/alpha_installer/');
  
}

$zip->close();

/*
--------------------------------------------------
Если в архиве есть файлы которые уже есть на сайте
--------------------------------------------------
*/

if (intval(INSTALL_CHECK_FILES_DOUBLE) == 1){
  
  ?>
  <div class='list-body6'>
  <div class='list-menu'>  
    
  <font size='+1'><?=lg('В ходе установки нашлись совпадения по файлам')?></font><br /><br />
  <div class='alpha-installer-container' style='height: 250px; border: 1px #D6DFE3 solid; padding: 5px'>    
  <small>
    
  <?php
    
  $zip->open(ROOT.'/files/upload/alpha_installer/'.$archive['ID'].'.'.$archive['EXT']);
  $i = 0;
  $s = 0;
  
  while ($name = $zip->getNameIndex($i)) {
    
    $i++;
    
    if (is_file(ROOT.'/'.$name)) {
      
      $s++;
      
      ?>
      <font color='#C62828'>/<?=$name?></font><br />
      <?
      
    }
    
    /*
    if (get('go') == 'yes'){
      
      //Удаляем старый файл перед заменой
      @unlink(ROOT.'/'.$name);
    
    }
    */
    
  }
    
  ?>
  
  </small>
  </div> 
    
  <br /><?=lg('Вы желаете их заменить? После замены отменить действие уже будет невозможно.')?><br />
  <br /><font color='#C62828'><?=icons('exclamation-triangle', 15, 'fa-fw')?> <?=lg('После замены компонент будет невозможно удалить из системы')?></font><br /><br />
    
  <a href='/admin/system/alpha_installer/?id=<?=$archive['ID']?>&install=ok&go=yes' class='button'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Да, продолжить')?></a>
  <a href='/admin/system/alpha_installer/' class='button-o'><?=lg('Отменить')?></a>
    
  </div>
  </div><br />
  <?
    
  if ($s > 0 && get('go') != 'yes'){
    
    back('/admin/system/alpha_installer/');
    acms_footer();
    
  }
  
  $zip->close();
  
}