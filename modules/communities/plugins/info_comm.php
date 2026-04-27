<div class='profile_info'>
  
<?php

$cat = db::get_string("SELECT `ID`,`NAME` FROM `COMMUNITIES_CATEGORIES` WHERE `ID` = ? LIMIT 1", [$comm['ID_CATEGORY']]);

if (isset($cat['ID'])){
  
  $ct = '<a href="/m/communities/categories/?id='.$cat['ID'].'">'.tabs(lg($cat['NAME'])).'</a>';
  
}else{
  
  $ct = lg('Без категории');
  
}

if ($comm['PRIVATE'] == 0){
  
  $type = lg('Открытое сообщество');

}elseif ($comm['PRIVATE'] == 1){
  
  $type = lg('Анонимное сообщество');

}elseif ($comm['PRIVATE'] == 2){
  
  $type = lg('Сообщество по интересам');

}

?>
  
<div class='profile_info_list'>  
<?=icons('clock-o', 20, 'fa-fw')?> <span><?=lg('Дата основания')?>: <?=ftime($comm['TIME'])?></span>
</div>
  
<div class='profile_info_list'>  
<?=icons('user', 20, 'fa-fw')?> <span><?=lg('Основатель')?>: <?=user::login($comm['USER_ID'], 0, 1)?></span>
</div>
  
<div class='profile_info_list'>  
<?=icons('list', 20, 'fa-fw')?> <span><?=lg('Категория')?>: <?=$ct?></span>
</div>  
  
<div class='profile_info_list'>  
<?=icons('users', 20, 'fa-fw')?> <span><?=lg('Тип')?>: <?=$type?></span>
</div>
  
<a href='/m/communities/info/?id=<?=$comm['ID']?>'>
<div class='profile_info_list_'>  
<?=icons('info-circle', 20, 'fa-fw')?> <span><?=lg('Подробная информация')?></span>
</div>
</a>  
  
</div>