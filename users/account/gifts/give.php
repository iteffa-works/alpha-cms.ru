<?php
$account = db::get_string("SELECT `ID` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);   
html::title(lg('Сделать подарок %s', user::login_mini($account['ID'])));
acms_header();
access('users');

if (!isset($account['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

if ($account['ID'] == user('ID')){
  
  error('Вы не можете делать подарки самому себе');
  redirect('/');

}

/*
----------------
Подарить подарок
----------------
*/

if (get('id_gift')){
  
  $gift = db::get_string("SELECT * FROM `GIFTS` WHERE `ID` = ? AND `ACT` = '1' LIMIT 1", [intval(get('id_gift'))]);
  
  if (!isset($gift['ID'])){
    
    error('Неверная директива');
    redirect('/');
  
  }    
  
  if (post('ok_give_gift')){
    
    valid::create(array(
      
      'GIFT_PRIVATE' => ['private', 'number', [0, 10], 'Приватность'],
      'GIFT_MESSAGE' => ['message', 'text', [0, 300], 'Сообщение', 0]
    
    ));
    
    if (ERROR_LOG == 1){
      
      redirect('/account/gifts/give/?id='.$account['ID'].'&id_gift='.$gift['ID']);
    
    }
    
    if ($gift['SUM'] > user('MONEY')){
      
      error('Недостаточно средств на счету');
      redirect('/account/gifts/give/?id='.$account['ID'].'&id_gift='.$gift['ID']);
    
    }
    
    $ID = db::get_add("INSERT INTO `GIFTS_USER` (`PRIVATE`, `USER_ID`, `MY_ID`, `MESSAGE`, `TIME`, `GIFT_ID`) VALUES (?, ?, ?, ?, ?, ?)", [GIFT_PRIVATE, user('ID'), $account['ID'], GIFT_MESSAGE, TM, $gift['ID']]);
    
    if (db::get_column("SELECT COUNT(*) FROM `NOTIFICATIONS_SETTINGS` WHERE `USER_ID` = ? AND `GIFTS` = ? LIMIT 1", [$account['ID'], 1]) == 1){ 
      
      db::get_add("INSERT INTO `NOTIFICATIONS` (`USER_ID`, `OBJECT_ID`, `OBJECT_ID_LIST`, `TIME`, `TYPE`) VALUES (?, ?, ?, ?, ?)", [$account['ID'], user('ID'), $gift['ID'], TM, 'gift']);
    
    }        
    
    db::get_set("UPDATE `USERS` SET `MONEY` = ? WHERE `ID` = ? LIMIT 1", [(user('MONEY') - $gift['SUM']), user('ID')]);
    
    success('Подарок успешно отправлен');
    redirect('/id'.$account['ID']);
    
  }
  
  ?>
  <div class='list'>
  <img class='img' src='/files/upload/gifts/<?=$gift['ID']?>.<?=$gift['EXT']?>' style='max-width: 100px'><br /><br />
  <b><?=lg('Название')?>:</b> <?=tabs($gift['NAME'])?><br />
  <b><?=lg('Цена')?>:</b> <?=money($gift['SUM'], 1)?><br />
  <b><?=lg('Получатель')?>:</b>
  <?=user::login($account['ID'], 0, 1)?><br /><br />
    
  <form method='post' class='ajax-form' action='/account/gifts/give/?id=<?=$account['ID']?>&id_gift=<?=$gift['ID']?>'>
  <b><?=lg('Введите сообщение для получателя')?></b> (<?=lg('до 300 символов')?>):<br />
  <?=html::textarea(null, 'message', 'Введите сообщение', null, 'form-control-textarea', 6, 0)?>
  <br /><br />
  <?=html::select('private', array(
    0 => ['Все', 0], 
    1 => ['Только я и получатель', 1]
  ), 'Кто увидит ваш логин и сообщение', 'form-control-100-modify-select', 'lock')?>  
  <?=html::button('button ajax-button', 'ok_give_gift', 'gift', 'Отправить подарок')?>
  </form>
  </div>
  <?
  
  back('/account/gifts/give/?id='.$account['ID'].'&id_dir='.$gift['ID_DIR']);
  acms_footer();
  
}

/*
------------------
Категория подарков
------------------
*/

if (get('id_dir')){
  
  $dir = db::get_string("SELECT * FROM `GIFTS_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('id_dir'))]);
  
  if (!isset($dir['ID'])){
    
    error('Неверная директива');
    redirect('/');
  
  }
  
  $column = db::get_column("SELECT COUNT(*) FROM `GIFTS` WHERE `ID_DIR` = ? AND `ACT` = '1'", [$dir['ID']]);
  $spage = spage($column, PAGE_SETTINGS);
  $page = page($spage);
  $limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

  if ($column == 0){
  
    html::empty();
  
  }else{
    
    ?>
    <div class='list-body'>
    <div class='list-menu'>
    <center><b><?=lg('Категория')?> "<?=tabs($dir['NAME'])?>"</b></center>  
    </div>
    <div class='list-menu'>
    <b><?=lg('Выберите подарок')?>:</b>  
    </div>
    <?
  
  }
  
  $data = db::get_string_all("SELECT * FROM `GIFTS` WHERE `ID_DIR` = ? AND `ACT` = '1' ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$dir['ID']]);  
  while ($list = $data->fetch()){
    
    ?>
    <div class='list-menu'>
    <img class='img' src='/files/upload/gifts/<?=$list['ID']?>.<?=$list['EXT']?>' style='max-width: 80px'><br /><br />
    <b><?=lg('Название')?>:</b> <?=tabs($list['NAME'])?><br />
    <b><?=lg('Цена')?>:</b> <?=money($list['SUM'], 1)?><br /><br />
    <a href='/account/gifts/give/?id=<?=$account['ID']?>&id_gift=<?=$list['ID']?>' class='btn'><?=icons('gift', 15, 'fa-fw')?> <?=lg('Подарить')?></a>
    </div>
    <?
    
  }
  
  if ($column > 0){
  
    ?></div><?
  
  }
  
  get_page('/account/gifts/give/?id='.$account['ID'].'&id_dir='.$dir['ID'].'&', $spage, $page, 'list');
  
  back('/account/gifts/give/?id='.$account['ID']);
  acms_footer();
  
}

/*
-------------------------
Список категории подарков
-------------------------
*/

$column = db::get_column("SELECT COUNT(*) FROM `GIFTS_DIR`");
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty();
  
}else{
  
  ?>
  <div class='list-body'>
  <div class='list-menu'> 
  <b><?=lg('Выберите категорию')?>:</b>  
  </div>
  <?
  
}

$data = db::get_string_all("SELECT * FROM `GIFTS_DIR` ORDER BY `ID` DESC LIMIT ".$limit.", ".PAGE_SETTINGS);
while ($list = $data->fetch()){
  
  $count = db::get_column("SELECT COUNT(*) FROM `GIFTS` WHERE `ID_DIR` = ? AND `ACT` = '1'", [$list['ID']]);
  
  ?>
  <a href='/account/gifts/give/?id=<?=$account['ID']?>&id_dir=<?=$list['ID']?>'>
  <div class='list-menu hover'>
  <span class='icons'><?=icons('gift', 20, 'fa-fw')?></span> <?=tabs($list['NAME'])?> <span class='count'><?=$count?></span>
  </div>
  </a>
  <?
  
}

if ($column > 0){
  
  ?></div><?
  
}

get_page('/account/gifts/give/?id='.$account['ID'].'&', $spage, $page, 'list');

back('/id'.$account['ID']);
acms_footer();