<?php
$account = db::get_string("SELECT `ID` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);   
html::title(lg('Подарки %s', user::login_mini($account['ID'])));
acms_header();

if (!isset($account['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

if (get('delete') && user('ID') > 0 && db::get_column("SELECT COUNT(`ID`) FROM `GIFTS_USER` WHERE `MY_ID` = ? AND `ID` = ? LIMIT 1", [$account['ID'], intval(get('delete'))]) == 1 && $account['ID'] == user('ID')){
  
  get_check_valid();
  
  if (get('get') == 'delete_ok'){
    
    db::get_set("DELETE FROM `GIFTS_USER` WHERE `MY_ID` = ? AND `ID` = ? LIMIT 1", [$account['ID'], intval(get('delete'))]);    
    redirect('/account/gifts/?id='.$account['ID']);
    
  }
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить подарок?')?><br /><br />
  <a href='/account/gifts/?id=<?=$account['ID']?>&delete=<?=intval(get('delete'))?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/account/gifts/?id=<?=$account['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}

$column = db::get_column("SELECT COUNT(`ID`) FROM `GIFTS_USER` WHERE `MY_ID` = ?", [$account['ID']]);
$spage = spage($column, PAGE_SETTINGS);
$page = page($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if (user('ID') > 0 && $account['ID'] != user('ID')){
  
  ?>
  <div class='list'>
  <a href='/account/gifts/give/?id=<?=$account['ID']?>' class='btn'><?=icons('gift', 15, 'fa-fw')?> <?=lg('Подарить подарок')?></a> 
  </div>
  <?
    
}

if ($column == 0){
  
  html::empty(lg('У %s пока нет подарков', user::login_mini($account['ID'])), 'gift');
  
}else{
  
  ?><div class='list-body'><?
  
}

$data = db::get_string_all("SELECT * FROM `GIFTS_USER` WHERE `MY_ID` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [$account['ID']]);
while ($list = $data->fetch()){
  
  $gift = db::get_string("SELECT * FROM `GIFTS` WHERE `ID` = ? LIMIT 1", [$list['GIFT_ID']]);
  
  ?>
  <div class='list-menu'>
  <img class='img' src='/files/upload/gifts/<?=$gift['ID']?>.<?=$gift['EXT']?>'><br /><br /> 
  <b><?=lg('Название')?>:</b> <?=tabs($gift['NAME'])?><br />
  <b><?=lg('Цена')?>:</b> <?=money($gift['SUM'], 1)?><br />
  <b><?=lg('Подарил')?>:</b>
  <?
  
  if ($list['PRIVATE'] == 0){
    
    echo user::login($list['USER_ID'], 0, 1);
    
  }elseif ($list['PRIVATE'] == 1){
    
    if ($list['MY_ID'] != user('ID') && $list['USER_ID'] != user('ID')){
      
      ?>
      <span class='icons'><?=icons('lock', 18, 'fa-fw')?><?=lg('Логин скрыт')?></span>
      <?
    
    }else{
      
      echo user::login($list['USER_ID'], 0, 1);
    
    } 
  
  }
  
  if (str($list['MESSAGE']) > 0){
    
    ?>
    <br /><b><?=lg('Сообщение')?>:</b>
    <?
    
    if ($list['PRIVATE'] == 0){
      
      echo tabs($list['MESSAGE']);
    
    }elseif ($list['PRIVATE'] == 1){
      
      if ($list['MY_ID'] != user('ID') && $list['USER_ID'] != user('ID')){
        
        ?>
        <span class='icons'><?=icons('lock', 18, 'fa-fw')?><?=lg('Сообщение скрыто')?></span>
        <?
        
      }else{
        
        echo tabs($list['MESSAGE']);
        
      }
    
    }
    
  }
  
  if (user('ID') > 0 && $account['ID'] == user('ID')){
    
    ?>
    <br /><br /><a href='/account/gifts/?id=<?=$account['ID']?>&delete=<?=$list['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Удалить')?></a>
    <?
    
  }
  
  ?></div><?
  
}

if ($column > 0){
  
  ?></div><?
  
}

get_page('/account/gifts/?id='.$account['ID'].'&', $spage, $page, 'list');

back('/id'.$account['ID']);
acms_footer();