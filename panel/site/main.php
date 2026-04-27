<?php  
acms_header('Главная страница', 'management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/site/'><?=lg('Настройки сайта')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Главная страница')?>
</div>
  
<div class='list-body'>  
<div class='list-menu'><b><?=lg('Показ на главной странице сайта')?>:</b></div>  
  
<?php $type = 'main_blogs'; ?>
<?php $type_tumb = 'mb'; ?>
<?php $type_ini = 'MAIN_BLOGS'; ?>
<?php $type_text = "Виджет записей блогов"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('book', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_photos'; ?>
<?php $type_tumb = 'mp'; ?>
<?php $type_ini = 'MAIN_PHOTOS'; ?>
<?php $type_text = "Виджет популярных фото"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('image', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_videos'; ?>
<?php $type_tumb = 'mv'; ?>
<?php $type_ini = 'MAIN_VIDEOS'; ?>
<?php $type_text = "Виджет популярных видео"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('film', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_forum'; ?>
<?php $type_tumb = 'mf'; ?>
<?php $type_ini = 'MAIN_FORUM'; ?>
<?php $type_text = "Виджет тем форума"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('comments', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_communities'; ?>
<?php $type_tumb = 'mc'; ?>
<?php $type_ini = 'MAIN_COMMUNITIES'; ?>
<?php $type_text = "Виджет популярных сообществ"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('users', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_games'; ?>
<?php $type_tumb = 'mg'; ?>
<?php $type_ini = 'MAIN_GAMES'; ?>
<?php $type_text = "Виджет популярных игр"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('gamepad', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_news'; ?>
<?php $type_tumb = 'mn'; ?>
<?php $type_ini = 'MAIN_NEWS'; ?>
<?php $type_text = "Виджет главных новостей сайта"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('feed', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_search'; ?>
<?php $type_tumb = 'msr'; ?>
<?php $type_ini = 'MAIN_SEARCH'; ?>
<?php $type_text = "Виджет поиска по сайту"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('search', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_online'; ?>
<?php $type_tumb = 'mo'; ?>
<?php $type_ini = 'MAIN_ONLINE'; ?>
<?php $type_text = "Виджет пользователей и гостей онлайн"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('user', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_menu'; ?>
<?php $type_tumb = 'mm'; ?>
<?php $type_ini = 'MAIN_MENU'; ?>
<?php $type_text = "Главные разделы"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('th', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
<?php $type = 'main_menu2'; ?>
<?php $type_tumb = 'mm2'; ?>
<?php $type_ini = 'MAIN_MENU2'; ?>
<?php $type_text = "Другие разделы"; ?>
<div id='<?=$type?>'>  
<?php if (config($type_ini) == 0) : ?>
<?php $value = 1; ?>
<?php $tumb = "tumb"; ?>
<?php else : ?>
<?php $value = 0; ?>
<?php $tumb = "tumb2"; ?>
<?php endif ?>
<?php if (get('private') == $type) : ?>
<?php ini::upgrade(ROOT.'/system/config/global/settings.ini', $type_ini, $value); ?>
<?php endif ?>
</div>
<div class='list-menu'> 
<b><?=icons('list', 18, 'fa-fw')?> <?=lg($type_text)?></b>
<input onclick="request('/admin/site/main/?get=main&mod=index&private=<?=$type?>', '#<?=$type?>')" class="input-tumb" type="checkbox" id="<?=$type_tumb?>"><label class="<?=$tumb?> thumb-optimize" for="<?=$type_tumb?>"></label></input>  
</div>
  
</div>
<?
  
back('/admin/site/');
acms_footer();