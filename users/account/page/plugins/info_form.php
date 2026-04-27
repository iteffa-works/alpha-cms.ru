<div class='profile_info'>
  
<?php
$c_f = db::get_string("SELECT `ID` FROM `COUNTRY` WHERE `NAME` = ? LIMIT 1", [esc($settings['COUNTRY'])]);

if ($account['SEX'] == 2){
  
  if ($settings['FAMILY'] == 1){
    
    $family = lg('не важно');
  
  }elseif ($settings['FAMILY'] == 2){
    
    $family = lg('не замужем');
  
  }elseif ($settings['FAMILY'] == 3){
    
    $family = lg('разведена');
  
  }elseif ($settings['FAMILY'] == 4){
    
    $family = lg('влюблена');
  
  }elseif ($settings['FAMILY'] == 5){
    
    $family = lg('помолвлена');
  
  }elseif ($settings['FAMILY'] == 6){
    
    $family = lg('всё сложно');
  
  }elseif ($settings['FAMILY'] == 7){
    
    $family = lg('замужем');
  
  }else{
    
    $family = lg('не указано');
    
  }

}else{
  
  if ($settings['FAMILY'] == 1){
    
    $family = lg('не важно');
  
  }elseif ($settings['FAMILY'] == 2){
    
    $family = lg('не женат');
  
  }elseif ($settings['FAMILY'] == 3){
    
    $family = lg('разведен');
  
  }elseif ($settings['FAMILY'] == 4){
    
    $family = lg('влюблен');
  
  }elseif ($settings['FAMILY'] == 5){
    
    $family = lg('помолвлен');
  
  }elseif ($settings['FAMILY'] == 6){
    
    $family = lg('всё сложно');
  
  }elseif ($settings['FAMILY'] == 7){
    
    $family = lg('женат');
  
  }else{
    
    $family = lg('не указано');
    
  }
  
}

if ($settings['G_R'] == 0 || $settings['M_R'] == 0 || $settings['D_R'] == 0){

  $date = lg('не указан');
  
}else{
  
  $date = (str($settings['D_R']) > 1 ? $settings['D_R'] : '0'.$settings['D_R']).".".(str($settings['M_R']) > 1 ? $settings['M_R'] : '0'.$settings['M_R']).".".$settings['G_R'];
  
}

?>
  
<div class='profile_info_list'>  
<?=icons('map-marker', 20, 'fa-fw')?> <span><?=lg('Проживание')?>: <?=(str($settings['COUNTRY'].$settings['CITY']) > 0 ? tabs($settings['CITY']).', '.tabs($settings['COUNTRY']).' <img src="/style/country/'.$c_f['ID'].'.png">' : lg('не указано'))?></span>
</div>
  
<div class='profile_info_list'>  
<?=icons('heart-o', 20, 'fa-fw')?> <span><?=lg('Положение')?>: <?=$family?></span>
</div>
  
<div class='profile_info_list'>  
<?=icons('gift', 20, 'fa-fw')?> <span><?=lg('День рождения')?>: <?=$date?></span>
</div>
  
<a href='/account/form/?id=<?=$account['ID']?>'>
<div class='profile_info_list_'>  
<?=icons('info-circle', 20, 'fa-fw')?> <span><?=lg('Подробная информация')?></span>
</div>
</a>  
  
</div>