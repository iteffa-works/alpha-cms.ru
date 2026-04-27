<?php
  
if (MANAGEMENT == 1) {
  
  if (config('DEVELOPER') == 0 && get('developer') == 'active') {
    
    get_check_valid();
    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'DEVELOPER', 1);
    
    success('Режим разработчика успешно включен');
    redirect('/admin/');
  
  }
  
  if (config('DEVELOPER') == 1 && get('developer') == 'no_active') {
    
    get_check_valid();
    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'DEVELOPER', 0);
    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'FRONT_HASH', TM);
    
    success('Режим разработчика успешно отключен');
    redirect('/admin/');
  
  }
  
  ?>
  <div class='desktop-vidget'>  
  <div class='desktop-vidget-title'><?=icons('code', 25, 'fa-fw')?> <?=lg('Режим разработчика')?></div>  
  <div class='list-menu'>
  <b><?=lg('Статус')?>:</b>
  <?php if (config('DEVELOPER') == 0) : ?>
  <span class='info gray'><?=lg('Не активен')?></span>
  <?php else : ?>
  <span class='info red'><?=lg('Активен')?></span>
  <?php endif ?>
  <br /><br />
  <?=lg('Отключение кеширования файлов JavaScript или CSS')?>
  <br /><br />
  <?php if (config('DEVELOPER') == 0) : ?> 
  <a href='/admin/?developer=active&<?=TOKEN_URL?>' class='button3'><?=icons('lock', 15, 'fa-fw')?> <?=lg('Включить')?></a>  
  <?php else : ?>
  <font color='#FF3365'><?=lg('Внимание! Активное состояние режима разработчика увеличивает нагрузку на сервер. Не забудьте отключить его после завершения работ')?></font>
  <br /><br />
  <a href='/admin/?developer=no_active&<?=TOKEN_URL?>' class='button2'><?=icons('unlock', 15, 'fa-fw')?> <?=lg('Отключить')?></a>
  <?php endif ?>
  <br />  
  </div>
  </div>
  <?
    
}