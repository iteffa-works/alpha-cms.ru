<?php
  
if (get('id')){
  
  $archive = db::get_string("SELECT * FROM `PANEL_ALPHA_INSTALL` WHERE `ID` = ? AND `ACT` = '0' LIMIT 1", [intval(get('id'))]);
  
  if (!isset($archive['ID'])){
    
    error('Неверная директива');
    redirect('/admin/system/alpha_installer/');
    
  }
  
  //Проверка содержимого архива
  require ROOT.'/panel/system/alpha_installer/testing.php';
  
  //Установка компонента
  require ROOT.'/panel/system/alpha_installer/install_components.php';
  
  ?>
  <div class='list-body6'>    
  <div class='list-menu list-title'> 
  <?=lg('Установка нового компонента')?>
  </div>    
  <div class='list-menu'>      
  <div class='alpha-installer-icons'>
  <?=file::ext($archive['EXT'])?>
  </div>  
  <div class='alpha-installer-info'>
  <div><b><?=tabs($archive['NAME'])?></b></div>
  <?=icons('clock-o', 15, 'fa-fw')?>
  <?=ftime($archive['TIME'])?>
  <span class='count'><?=size_file($archive['SIZE'])?></span>
  </div>
  </div>
    
  <div class='list-menu'>
  <font size='+1'><?=lg('Ознакомление')?></font><br /><br />
  <div class='alpha-installer-container' style='height: 250px; border: 1px #D6DFE3 solid; padding: 5px;'>    
  <small>
    
  1. <?=lg('Скачанные компоненты из открытого доступа в сети интернет могут содержать в себе вредоносные коды для взлома и кражи данных с вашего сервера, а так же прочие коды, например для удаления всех пользователей вашего сайта. Настоятельно рекомендуется брать компоненты только с официального сайта')?> - <a href='https://alpha-cms.ru' ajax='no'>Alpha-CMS.Ru</a>.<br /><br />
    
  2. <?=lg('Некачественное содержимое внутри архива от неизвестного разработчика может привести к сбою системы или поломке некоторых компонентов движка.')?><br /><br />
    
  3. <?=lg('Настоятельно не рекомендуется экспериментировать с установкой компонентов от других движков или вовсе - загружать в альфа установщик архивы с абсурдным с точки зрения программирования содержимым.')?><br /><br />
    
  4. <?=lg('Рекомендуется устанавливать исключительно те компоненты, которые есть на официальном сайте от разработчика движка')?> - <a ajax='no' href='https://alpha-cms.ru'>Alpha-CMS.Ru</a>. <?=lg('Установка компонентов от разных разработчиков со временем может привести к технической деградации движка и тупику в развитии. Дальшейшая техническая поддержка движка может стать невозможной.')?><br /><br />
    
  5. <?=lg('При появлении ошибок после окончания установки компонента, рекомендуется обратиться в службу')?> <a ajax='no' href='https://alpha-cms.ru/m/support/'><?=lg('технической поддержки')?></a>.
    
  <br /><br />
  
  </small>
  </div>
    
  <br /><br />
    
  <?=lg('Содержимое архива будет распаковано в корневую директорию')?>:
  <b><?=ROOT?>/<?=lg('здесь')?></b>
    
  <br /><br />
    
  <a href='/admin/system/alpha_installer/?id=<?=$archive['ID']?>&install=ok' class='button'><?=ICONS('plus', 15, 'fa-fw')?> <?=LG('Установить')?></a>
    
  </div>
    
  </div><br />
  <?
    
  back('/admin/system/alpha_installer/');
  acms_footer();

}