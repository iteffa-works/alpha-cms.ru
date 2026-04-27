<?php  
acms_header('История операций', 'users');

if (post('search')){
  
  session('tsearch', esc(post('search')));
  redirect('/shopping/info/');

}

if (get('search')){
  
  session('tsearch', esc(urldecode(get('search'))));
  redirect('/shopping/info/');

}

if (get('get') == 'search_no'){
  
  session('tsearch', null);
  redirect('/shopping/info/');

}

$search = tabs(session('tsearch'));

?> 
<div class='menu-nav-content'>  
<a class='menu-nav <?=(get('type') != 'minus' && get('type') != 'plus' ? 'h' : null)?>' href='/shopping/info/?'>
<?=lg('Все')?>
</a>    
<a class='menu-nav <?=(get('type') == 'plus' ? 'h' : null)?>' href='/shopping/info/?type=plus'>
<?=lg('Зачисления')?>
</a>
<a class='menu-nav <?=(get('type') == 'minus' ? 'h' : null)?>' href='/shopping/info/?type=minus'>
<?=lg('Списания')?>
</a>
</div>
  
<div class='search-main-optimize'>
<form method='post' class='ajax-form2' action='/shopping/info/'>
<input type='text' name='search' class='search-main' placeholder='<?=lg('Введите чек или название транзакции')?>' value='<?=$search?>'> 
<button class="search-main-button ajax-button-search"><?=icons('search', 20)?></button>
</form>
</div>
  
<?php if (str($search) > 0) : ?>
<div class='list'>
<a href='/shopping/info/?get=search_no' class='btn'><?=icons('times', 15, 'fa-fw')?> <?=lg('Сбросить результаты поиска')?></a>
</div>
<?php else : ?>
<div class='list'>
<a href='/shopping/info/?search=<?=urlencode(lg('Перевод пользователю'))?>' class='btn-o'><?=lg('Мои переводы')?></a>
<a href='/shopping/info/?search=<?=urlencode(lg('Перевод от пользователя'))?>' class='btn-o'><?=lg('Переводы мне')?></a>
<a href='/shopping/info/?search=<?=urlencode(lg('Обмен'))?>' class='btn-o'><?=lg('Обмен')?></a>
<a href='/shopping/info/?search=<?=urlencode(lg('Покупка'))?>' class='btn-o'><?=lg('Покупки')?></a>
</div>
<?php endif ?>
  
<?php if (MANAGEMENT == 1) : ?>
<?=message('Сообщение для создателя сайта', lg('Вы можете отслеживать все транзакции пользователей на сайте через панель управления, в разделе')." <a href='/admin/site/transactions/' ajax='no'>".lg('транзакции пользователей')."</a>", 'transactions')?>
<?php endif ?>  
<?

$sql = null;    
if (get('type') == 'plus') { $sql = "AND `TYPE` = '1'"; }
if (get('type') == 'minus') { $sql = "AND `TYPE` != '1'"; }

$column = db::get_column("SELECT COUNT(*) FROM `TRANSACTIONS` WHERE `USER_ID` = ? AND (`INFO` LIKE ? OR `ID` = ?) ".$sql, [user('ID'), '%'.esc($search).'%', intval(str_replace("#", "", $search))]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){ 
  
  html::empty('Нет транзакций'); 

}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `TRANSACTIONS` WHERE `USER_ID` = ? AND (`INFO` LIKE ? OR `ID` = ?) ".$sql." ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID'), '%'.esc($search).'%', intval(str_replace("#", "", $search))]);
while ($list = $data->fetch()) {
  
  ?>
  <div class='list-menu'>
  <font size='+1' color='<?=($list['TYPE'] != 1 ? "#FF3E77" : "#29D49C")?>'><?=($list['TYPE'] != 1 ? '-' : '+')?><?=money($list['SUM'], 2)?></font><br />
  <b><?=text($list['INFO'])?></b><br /><br />
  <small>
  <?=lg('Виртуальный чек')?>: <b>#<?=$list['ID']?></b><br />
  <?=lg('Время')?>: <?=ftime($list['TIME'])?>
  </small>
  </div>
  <?
  
}

if ($column > 0) {
  
  ?></div><?

}

get_page('/shopping/info/?type='.(get('type') != 'minus' && get('type') != 'plus' ? 'all' : tabs(get('type'))).'&', $spage, $page, 'list');

back('/shopping/');
forward('/account/cabinet/', 'В кабинет');
forward(user::url(user('ID')), 'На страницу');
acms_footer();