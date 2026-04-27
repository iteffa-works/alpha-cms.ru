<div class='list-body'>
<div class='list-menu list-menu-title'><?=icons('address-card', 20, 'fa-fw')?> <?=lg('О себе')?></div>
  
<div class='profile_info'>

<div class='profile_info_list'>
<?=icons('address-card', 20, 'fa-fw')?> <span><b><?=lg('О себе')?>:</b> <?=($settings['ABOUT_ME'] != null ? tabs($settings['ABOUT_ME']) : lg('не указано'))?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('soccer-ball-o', 20, 'fa-fw')?> <span><b><?=lg('Мои интересы')?>:</b> <?=($settings['MY_INTERESTS'] != null ? tabs($settings['MY_INTERESTS']) : lg('не указаны'))?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('music', 20, 'fa-fw')?> <span><b><?=lg('Любимая музыка')?>:</b> <?=($settings['MY_MUSIC'] != null ? tabs($settings['MY_MUSIC']) : lg('не указана'))?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('film', 20, 'fa-fw')?> <span><b><?=lg('Любимые фильмы')?>:</b> <?=($settings['MY_FILMS'] != null ? tabs($settings['MY_FILMS']) : lg('не указаны'))?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('book', 20, 'fa-fw')?> <span><b><?=lg('Любимые книги')?>:</b> <?=($settings['MY_BOOKS'] != null ? tabs($settings['MY_BOOKS']) : lg('не указаны'))?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('wrench', 20, 'fa-fw')?> <span><b><?=lg('Профессия')?>:</b> <?=($settings['MY_PROFESSION'] != null ? tabs($settings['MY_PROFESSION']) : lg('не указана'))?></span>
</div>
  
<?php
  
if ($settings['MY_POLITIC'] == 1){
  
  $politic = lg('Не важно');

}elseif ($settings['MY_POLITIC'] == 2){
  
  $politic = lg('Коммунистические');

}elseif ($settings['MY_POLITIC'] == 3){
  
  $politic = lg('Социалистические');

}elseif ($settings['MY_POLITIC'] == 4){
  
  $politic = lg('Умеренные');

}elseif ($settings['MY_POLITIC'] == 5){
  
  $politic = lg('Либеральные');

}elseif ($settings['MY_POLITIC'] == 6){
  
  $politic = lg('Консервативные');

}elseif ($settings['MY_POLITIC'] == 7){
  
  $politic = lg('Монархические');

}elseif ($settings['MY_POLITIC'] == 8){
  
  $politic = lg('Ультраконсеративные');

}elseif ($settings['MY_POLITIC'] == 9){
  
  $politic = lg('Либертарианские');

}elseif ($settings['MY_POLITIC'] == 10){
  
  $politic = lg('Индифферентные');

}else{
  
  $politic = lg('не указаны');

}

if ($settings['MY_FAITH'] == 1){
  
  $faith = lg('Не важно');

}elseif ($settings['MY_FAITH'] == 2){
  
  $faith = lg('Православие');

}elseif ($settings['MY_FAITH'] == 3){
  
  $faith = lg('Католицизм');

}elseif ($settings['MY_FAITH'] == 4){
  
  $faith = lg('Иудаизм');

}elseif ($settings['MY_FAITH'] == 5){
  
  $faith = lg('Ислам');

}elseif ($settings['MY_FAITH'] == 6){
  
  $faith = lg('Протестантизм');

}elseif ($settings['MY_FAITH'] == 7){
  
  $faith = lg('Буддизм');

}elseif ($settings['MY_FAITH'] == 8){
  
  $faith = lg('Конфуцианство');

}elseif ($settings['MY_FAITH'] == 9){
  
  $faith = lg('Светский гуманизм');

}elseif ($settings['MY_FAITH'] == 10){
  
  $faith = lg('Атеизм');

}elseif ($settings['MY_FAITH'] == 11){
  
  $faith = lg('Пастафарианство');

}elseif ($settings['MY_FAITH'] == 12){
  
  $faith = lg('Агностицизм');

}else{
  
  $faith = lg('не указано');

}

if ($settings['ALKOHOL'] == 1){
  
  $alkohol = lg('Не важно');

}elseif ($settings['ALKOHOL'] == 2){
  
  $alkohol = lg('Негативное');

}elseif ($settings['ALKOHOL'] == 3){
  
  $alkohol = lg('Нейтральное');

}elseif ($settings['ALKOHOL'] == 4){
  
  $alkohol = lg('Положительное');

}else{
  
  $alkohol = lg('не указано');

}

if ($settings['SMOKING'] == 1){
  
  $smoking = lg('Не важно');

}elseif ($settings['SMOKING'] == 2){
  
  $smoking = lg('Негативное');

}elseif ($settings['SMOKING'] == 3){
  
  $smoking = lg('Нейтральное');

}elseif ($settings['SMOKING'] == 4){
  
  $smoking = lg('Положительное');

}else{
  
  $smoking = lg('не указано');

}

if ($account['SEX'] == 2){
  
  if ($settings['FAMILY'] == 1){
    
    $family = lg('Не важно');
  
  }elseif ($settings['FAMILY'] == 2){
    
    $family = lg('Не замужем');
  
  }elseif ($settings['FAMILY'] == 3){
    
    $family = lg('Разведена');
  
  }elseif ($settings['FAMILY'] == 4){
    
    $family = lg('Влюблена');
  
  }elseif ($settings['FAMILY'] == 5){
    
    $family = lg('Помолвлена');
  
  }elseif ($settings['FAMILY'] == 6){
    
    $family = lg('Всё сложно');
  
  }elseif ($settings['FAMILY'] == 7){
    
    $family = lg('Замужем');
  
  }else{
    
    $family = lg('не указано');
    
  }

}else{
  
  if ($settings['FAMILY'] == 1){
    
    $family = lg('Не важно');
  
  }elseif ($settings['FAMILY'] == 2){
    
    $family = lg('Не женат');
  
  }elseif ($settings['FAMILY'] == 3){
    
    $family = lg('Разведен');
  
  }elseif ($settings['FAMILY'] == 4){
    
    $family = lg('Влюблен');
  
  }elseif ($settings['FAMILY'] == 5){
    
    $family = lg('Помолвлен');
  
  }elseif ($settings['FAMILY'] == 6){
    
    $family = lg('Всё сложно');
  
  }elseif ($settings['FAMILY'] == 7){
    
    $family = lg('Женат');
  
  }else{
    
    $family = lg('не указано');
    
  }
  
}
  
?>
  
<div class='profile_info_list'>
<?=icons('eye', 20, 'fa-fw')?> <span><b><?=lg('Полит. взгляды')?>:</b> <?=$politic?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('users', 20, 'fa-fw')?> <span><b><?=lg('Мировоззрение')?>:</b> <?=$faith?></span>
</div>
  
<div class='profile_info_list'>
<?=icons('glass', 20, 'fa-fw')?> <span><b><?=lg('Отношение к алкоголю')?>:</b> <?=$alkohol?></span>
</div>  
  
<div class='profile_info_list'>
<?=icons('fire', 20, 'fa-fw')?> <span><b><?=lg('Отношение к курению')?>:</b> <?=$smoking?></span>
</div> 
  
<div class='profile_info_list'>
<?=icons('heart', 20, 'fa-fw')?> <span><b><?=lg('Семейное положение')?>:</b> <?=$family?></span>
</div>  
  
</div>  
  
<?php

if (user('ID') > 0 && $account['ID'] == user('ID')){
  
  ?>
  <div class='list-menu'>
  <a class='btn-o' href='/account/form/?id=<?=$account['ID']?>&get=about_me&<?=TOKEN_URL?>'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Редактировать')?></a>
  </div>
  <?
  
}

?>

</div>