<?php

$type = 'private_communities';
$type_tumb = 'pcm';
$type_ini = 'PRIVATE_COMMUNITIES';
$type_text = "Сообщества";

?>

<div id='<?=$type?>'>
  
<?php

if (config($type_ini) == 0){
  
  $value = 1;
  $tumb = "tumb";
  
}else{
  
  $value = 0;
  $tumb = "tumb2";
  
}
  
if (get('private') == $type){
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value);
  
}

?>

</div>

<div class='list-menu'>
  
<b><?=icons('users', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/modules/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>
  
</div>