<?php if (config('CTJ') == 1 && MANAGEMENT == 0 && url_request_validate('/login') == false) : ?>
<?=html::empty('На сайте проводятся тех.работы. Подождите немного и обновите страницу', 'gears')?>
<?php exit; endif ?>
  
<?php if (config('CTJ') == 1 && MANAGEMENT == 1) : ?>
<div class='list' style='margin-bottom: 10px'><?=lg('Включен режим тех.работ. Пользователи не имеют доступа к разделам сайта. Отключить режим можно в %s.', '<a ajax="no" href="/admin/system/settings/">'.lg('общих настройках').'</a>')?></div>
<?php endif ?>