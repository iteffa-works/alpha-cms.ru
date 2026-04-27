<?php  
acms_header('Транзакции пользователей', 'management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/site/'><?=lg('Настройки сайта')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Транзакции пользователей')?>
</div>
<?
  
if (post('search')){
  
  session('tsearch', esc(post('search')));
  redirect(REQUEST_URI);

}

if (get('get') == 'search_no'){
  
  session('tsearch', null);
  redirect('/admin/site/transactions/');

}

$search = tabs(session('tsearch'));

$cheque = 0;
if (strpos($search, '#') !== false) { $cheque = 1; }
  
$sql = null;    
if (get('type') == 'plus') { $sql = "AND `TYPE` = '1'"; }
if (get('type') == 'minus') { $sql = "AND `TYPE` = '0'"; }
if (str($search) > 0) { $sql = $sql." AND (`INFO` LIKE ? OR `USER_ID` = ? OR `ID` = ?)"; }
  
$column = db::get_column("SELECT COUNT(*) FROM `TRANSACTIONS` WHERE `USER_ID` > '0' ".$sql, ['%'.esc($search).'%', ($cheque == 1 ? 0 : intval($search)), intval(str_replace("#", "", $search))]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

?>
<div class='list-body'>
  
<div class='list-menu' style='padding: 3px'>
<div class='search-main-optimize'>
<form method='post' class='ajax-form2' action='/admin/site/transactions/?get=search'>
<input type='text' name='search' class='search-main' placeholder='<?=lg('Введите ID пользователя, чек или название платежа')?>' value='<?=$search?>'> 
<button class="search-main-button ajax-button-search"><?=icons('search', 20)?></button>
</form>
</div>
<?php if (str($search) > 0) : ?>
<a href='/admin/site/transactions/?get=search_no' class='button2' style='margin: 10px'><?=icons('times', 15, 'fa-fw')?> <?=lg('Сбросить результаты поиска')?></a>
<?php endif ?>
</div>  
 
<div class='list-menu'>  
<a class='button<?=(get('type') != 'minus' && get('type') != 'plus' ? 3 : null)?>' href='/admin/site/transactions/?'>
<?=lg('Все')?>
</a>    
<a class='button<?=(get('type') == 'plus' ? 3 : null)?>' href='/admin/site/transactions/?type=plus'>
<?=lg('Зачисления')?>
</a>
<a class='button<?=(get('type') == 'minus' ? 3 : null)?>' href='/admin/site/transactions/?type=minus'>
<?=lg('Списания')?>
</a>
</div>
<?

if ($column == 0){ 
  
  html::empty('Нет транзакций'); 

}

$data = db::get_string_all("SELECT * FROM `TRANSACTIONS` WHERE `USER_ID` > '0' ".$sql." ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, ['%'.esc($search).'%', ($cheque == 1 ? 0 : intval($search)), intval(str_replace("#", "", $search))]);
while ($list = $data->fetch()) {
  
  ?>
  <div class='list-menu'>
  <font size='+1' color='<?=($list['TYPE'] != 1 ? "#FF3E77" : "#29D49C")?>'><?=($list['TYPE'] != 1 ? '-' : '+')?><?=money($list['SUM'], 2)?></font><br />
  <b><?=text($list['INFO'])?></b><br /><br />
  <?=lg('Пользователь')?>: <?=user::login($list['USER_ID'], 0, 1)?><br /><br />
  <small>
  <?=lg('Виртуальный чек')?>: <b>#<?=$list['ID']?></b><br />
  <?=lg('Время')?>: <?=ftime($list['TIME'])?>
  </small>
  </div>
  <?
  
}

get_page('/admin/site/transactions/?type='.(get('type') != 'minus' && get('type') != 'plus' ? 'all' : tabs(get('type'))).'&', $spage, $page, 'list-menu');

?></div><?
  
acms_footer();