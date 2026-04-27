<?php  
html::title('Генерация страниц');
acms_header();
access('management');  
?>

<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Генерация страниц')?>
</div>
  
<?php
  
if (get('get') == 'settings'){
  
  if (post('interval')){
    
    db_filter();
    post_check_valid();
    
    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'SPEED_INTERVAL', intval(post('interval')));
    
    success('Изменения успешно приняты');
    redirect('/admin/system/speed/?get=settings');
    
  }
  
  ?>    
  <div class='list-body6'>  
  <div class='list-menu list-title'> 
  <?=lg('Интервал обновления данных о генерации главной страницы')?>
  </div>  
  <div class='list-menu'>
  <form method='post' class='ajax-form' action='/admin/system/speed/?get=settings'>    
  <?=html::select('interval', array(
  60 => ['1 '.lg('минута'), (config('SPEED_INTERVAL') == 60 ? "selected" : null)], 
  120 => ['2 '.lg('минуты'), (config('SPEED_INTERVAL') == 120 ? "selected" : null)],
  180 => ['3 '.lg('минуты'), (config('SPEED_INTERVAL') == 180 ? "selected" : null)], 
  240 => ['4 '.lg('минуты'), (config('SPEED_INTERVAL') == 240 ? "selected" : null)],
  300 => ['5 '.lg('минут'), (config('SPEED_INTERVAL') == 300 ? "selected" : null)], 
  360 => ['6 '.lg('минут'), (config('SPEED_INTERVAL') == 360 ? "selected" : null)],
  420 => ['7 '.lg('минут'), (config('SPEED_INTERVAL') == 420 ? "selected" : null)], 
  480 => ['8 '.lg('минут'), (config('SPEED_INTERVAL') == 480 ? "selected" : null)],
  540 => ['9 '.lg('минут'), (config('SPEED_INTERVAL') == 540 ? "selected" : null)], 
  600 => ['10 '.lg('минут'), (config('SPEED_INTERVAL') == 600 ? "selected" : null)]  
  ), 'Интервал обновления данных', 'form-control-100-modify-select', 'clock-o')?>  
  <?=html::button('ajax-button button', 'ok', 'save', 'Сохранить изменения')?>
  </form>
  </div>
  </div>
  <br />
  <?
  
  back('/admin/system/speed/');
  acms_footer();
  
}

?>
  
<div class='list-body6'>  
<div class='list-menu list-title'> 
<?=lg('Измерить страницы/сайт')?>
</div>  
<div class='list-menu'>
  
<?php
  
if (post('domain')){ 
  
  valid::create(array('DOMAIN' => ['domain', 'link', [1,500], 'URL адрес']));
  
  if (ERROR_LOG == 1){
    
    redirect('?');
    
  }

  $speed_site = speed_size(DOMAIN);
  $speed = speed($speed_site);
  
?>

<div class="rang" style="margin-top: 20px;">
<div class="rang-title">    
<input class="rang-number" id="show" type="text" value="<?=lg('Скорость')?> <?=$speed?>%" disabled="disabled"/>
</div>
<svg class="meter">
<circle class="meter-left" r="96" cx="135" cy="142"></circle>
<circle class="meter-center" r="96" cx="136" cy="142"></circle>
<circle class="meter-right" r="96" cx="138" cy="142"></circle>
<polygon class="meter-clock" style="transform: rotate(<?=speed_clock($speed)?>deg)" points="129,145 137,90 145,145"></polygon>
<circle class="meter-circle" r="10" cx="137" cy="145"></circle>    
</svg>  
</div>
  
<center>
<span class='rang-sec'><?=$speed_site?> <?=lg('сек.')?></span>
<?=speed_comment(config('SPEED'))?>
</center>
  
<br /><center><b><?=lg('Результаты страницы:')?> <?=DOMAIN?></b></center><br />
  
<?php } ?>
  
<form method='post' class='ajax-form' action='/admin/system/speed/'>
<?=html::input('domain', 'Адрес страницы с http или https', 'Введите адрес сайта:', null, null, 'form-control-100', 'text', null, 'link')?>
<?=html::button('ajax-button button', 'ok', 'tachometer', 'Измерить скорость')?>
</form> 
  
<a href='/admin/system/speed/?get=settings' class='button3' style='position: absolute; bottom: 15px; right: 15px;'><?=icons('gear', 15)?></a>
  
</div>
</div>
  
<div class='list-body6'>  
<div class='list-menu list-title'> 
<?=HTTP_HOST?>
</div>  
<div class='list-menu'>    
<div class="rang" style="margin-top: 20px;">
<div class="rang-title">    
<input class="rang-number" id="show" type="text" value="<?=lg('Скорость')?> <?=speed(config('SPEED_SEC'))?>%" disabled="disabled"/>
</div>   
<svg class="meter">
<circle class="meter-left" r="96" cx="135" cy="142"></circle>
<circle class="meter-center" r="96" cx="136" cy="142"></circle>
<circle class="meter-right" r="96" cx="138" cy="142"></circle>
<polygon class="meter-clock" style="transform: rotate(<?=speed_clock(speed(config('SPEED_SEC')))?>deg)" points="129,145 137,90 145,145"></polygon>
<circle class="meter-circle" r="10" cx="137" cy="145"></circle>    
</svg>  
</div> 
<center>
<span class='rang-sec'><?=config('SPEED_SEC')?> <?=lg('сек.')?></span>
<?=speed_comment(config('SPEED'))?>
<span class='rang-upd'><?=lg('Посл. обновление')?>: <?=stime(config('SPEED_TIME_UPDATE'))?></span>
</center>  
</div>  
</div>    
<?

back('/admin/system/');
acms_footer();