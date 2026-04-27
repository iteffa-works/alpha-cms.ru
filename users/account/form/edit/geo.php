<?php
  
?><div class='list-body'><?
  
/*
-------------------------------
Выбор города/населенного пункта
-------------------------------
*/
  
if (get('type') == 'city' && db::get_column("SELECT COUNT(*) FROM `COUNTRY` WHERE `ID` = ? LIMIT 1", [intval(session('country'))]) == 1 && db::get_column("SELECT COUNT(*) FROM `REGION` WHERE `ID` = ? AND `ID_COUNTRY` = ? LIMIT 1", [intval(session('region')), intval(session('country'))]) == 1){
  
  ?>
  <div class='list-menu'>
  <b><?=lg('Выберите город')?>:</b>
  </div>
  <?
    
  if (get('city_id') && db::get_column("SELECT COUNT(*) FROM `CITY` WHERE `ID` = ? LIMIT 1", [intval(get('city_id'))]) == 1){
    
    $city = db::get_string("SELECT `NAME` FROM `CITY` WHERE `ID` = ? LIMIT 1", [intval(get('city_id'))]);
    $region = db::get_string("SELECT `NAME` FROM `REGION` WHERE `ID` = ? LIMIT 1", [intval(session('region'))]);
    $country = db::get_string("SELECT `NAME` FROM `COUNTRY` WHERE `ID` = ? LIMIT 1", [intval(session('country'))]);
    
    db::get_set("UPDATE `USERS_SETTINGS` SET `CITY` = ?, `REGION` = ?, `COUNTRY` = ? WHERE `USER_ID` = ? LIMIT 1", [esc($city['NAME']), esc($region['NAME']), esc($country['NAME']), $account['ID']]);
    
    success('Изменения успешно приняты');    
    redirect('/account/form/?id='.$account['ID']);
    
  }
  
  $data = db::get_string_all("SELECT * FROM `CITY` WHERE `ID_REGION` = ? ORDER BY `ID` ASC", [intval(session('region'))]);  
  while ($list = $data->fetch()){
    
    ?>
    <a href='/account/form/?id=<?=$account['ID']?>&get=geo&city_id=<?=$list['ID']?>&type=city&<?=TOKEN_URL?>'>
    <div class='list-menu hover'>    
    <?=tabs(lg($list['NAME']))?>   
    </div>
    </a>
    <?
  
  }
  
  ?></div><?
    
  back('/account/form/?id='.$account['ID'].'&get=geo&type=region&'.TOKEN_URL);
  acms_footer();
  
}
  
/*
-------------
Выбор региона
-------------
*/
  
if (get('type') == 'region' && db::get_column("SELECT COUNT(*) FROM `COUNTRY` WHERE `ID` = ? LIMIT 1", [intval(session('country'))]) == 1){
  
  if (get('region_id') && db::get_column("SELECT COUNT(*) FROM `REGION` WHERE `ID` = ? AND `ID_COUNTRY` = ? LIMIT 1", [intval(get('region_id')), intval(session('country'))]) == 1){
    
    session('region', intval(get('region_id')));    
    redirect('/account/form/?id='.$account['ID'].'&get=geo&type=city&'.TOKEN_URL);
    
  }
  
  ?>
  <div class='list-menu'>
  <b><?=lg('Выберите регион')?>:</b>
  </div>
  <?
  
  $data = db::get_string_all("SELECT * FROM `REGION` WHERE `ID_COUNTRY` = ? ORDER BY `ID` ASC", [intval(session('country'))]);  
  while ($list = $data->fetch()){
    
    ?>
    <a href='/account/form/?id=<?=$account['ID']?>&get=geo&type=region&<?=TOKEN_URL?>&region_id=<?=$list['ID']?>'>
    <div class='list-menu hover'>
    <?=tabs(lg($list['NAME']))?>
    </div>
    </a>
    <?
  
  }
  
  ?></div><?
    
  back('/account/form/?id='.$account['ID'].'&get=geo&'.TOKEN_URL);
  acms_footer();
  
}

/*
------------
Выбор страны
------------
*/

?>
<div class='list-menu'>
<b><?=lg('Выберите страну')?>:</b>
</div>
<?

if (get('country_id') && db::get_column("SELECT COUNT(*) FROM `COUNTRY` WHERE `ID` = ? LIMIT 1", [intval(get('country_id'))]) == 1){
  
  session('country', intval(get('country_id')));    
  redirect('/account/form/?id='.$account['ID'].'&get=geo&type=region&'.TOKEN_URL);

}
  
$data = db::get_string_all("SELECT * FROM `COUNTRY` ORDER BY `ID` ASC");
while ($list = $data->fetch()){
  
  ?>
  <a href='/account/form/?id=<?=$account['ID']?>&get=geo&<?=TOKEN_URL?>&country_id=<?=$list['ID']?>'>
  <div class='list-menu hover'>
  <img src='/style/country/<?=$list['ID']?>.png'> <?=tabs(lg($list['NAME']))?>
  </div>
  </a>
  <?
  
}

?></div>