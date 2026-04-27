<?php 

if (config('SPEED_TIME') < TM){

  $speed_site = speed_size(SCHEME.HTTP_HOST);
  $speed = speed($speed_site);
  $interval = TM + config('SPEED_INTERVAL');
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'SPEED_TIME', $interval);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'SPEED_SEC', $speed_site);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'SPEED_TIME_UPDATE', TM);
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'SPEED', $speed);
  
  redirect('/admin/desktop/?');
  
}

?>

<div class='desktop-speed'>  
  
<div><?=icons('dashboard', 25, 'fa-fw')?> <?=lg('Генерация страниц')?></div>    
    
<div class="rang">

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
  
<center>
<span class='rang-sec'><?=config('SPEED_SEC')?> <?=lg('сек.')?></span>
<?=speed_comment(config('SPEED'))?>
<span class='rang-upd' style='left: -10px;'><?=lg('Посл. обновление')?>: <?=stime(config('SPEED_TIME_UPDATE'))?></span>
</center>
  
</div>
  
<a href='/admin/system/speed/' class='button3' style='position: absolute; bottom: 9px; left: 9px;'><?=icons('dashboard', 15, 'fa-fw')?> <?=lg('Измерить сайт')?></a>
  
<?php if (access('management', null) == true){ ?>  
<a href='/admin/system/speed/?get=settings' class='button3' style='position: absolute; bottom: 9px; right: 9px;'><?=icons('gear', 15)?></a>
<?php } ?>
  
</div>