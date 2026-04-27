<?php
  
/*
----------------------------------
Функция вывода виджета с ресурсами 
пользователя в платных услугах с 
информацией об услуге
----------------------------------
*/
  
function resources($title, $sum, $term) {
  
  //$title - наименование услуги
  //$sum - стоимость услуги
  //$term - срок услуги
  
  ?>
  <div class='list'>
  <center><font size='+1'><?=lg($title)?></font></center><br />
  <span class='resources_info'><?=lg('Баланс')?>: <b><?=money(user('MONEY'), 2)?></b>
  <?php if (config('SHOP_REP') == 1) : ?>
  <a href='/shopping/rep/' style='float: right; position: relative; top: 3px'><?=lg('Пополнить счет')?></a>
  <?php endif ?>
  <br />
  <?=lg('Стоимость услуги')?> (<?=lg($term)?>): <b><?=money($sum, 2)?></b>
  </span>
  </div>
  <?
  
} 

/*
----------------------------------------
Функция записи списывания денег со счета
и записи денежных операций
----------------------------------------
*/
  
function money_data($user, $sum, $type, $info, $mess_data = 0) {
  
  //$user - id пользователя
  //$sum - сумма операции
  //$type - тип операции
  //$info - информация об операции
  //$mess_data - отправка сообщения получателю о зачислении средств (если значение 1 и тип - зачисление, если значение 2 - сообщение отправится, но не сработает запрос на зачисление средств)
  
  if ($mess_data != 2) {
    
    db::get_set("UPDATE `USERS` SET `MONEY` = `MONEY` ".($type != 1 ? '-' : '+')." ? WHERE `ID` = ? LIMIT 1", [$sum, $user]);
    
  }
  
  $ID = db::get_add("INSERT INTO `TRANSACTIONS` (`USER_ID`, `SUM`, `TYPE`, `INFO`, `TIME`) VALUES (?, ?, ?, ?, ?)", [$user, $sum, $type, $info, TM]);
  
  if ($mess_data > 0 && $type == 1) {
    
    $mess = lg('Зачисление средств на ваш счет.
    
    Сумма: %s
    Виртуальный чек: %s
    Дата: %s
    
    Информация о транзакции: 
    %s
    
    Подробнее в %s.', '[b]'.money($sum, 2).'[/b]', '[b]#'.$ID.'[/b]', '[b]'.date('j.m.Y').', '.ftime(TM).'[/b]', '[b]'.$info.'[/b]', '[url=/shopping/info/?]'.lg('истории операций').'[/url]');
    messages::get(config('SYSTEM'), $user, $mess);
    
  }
  
}

/*
---------------------------
Функция вывода валюты сайта
---------------------------
*/
  
function money($sum, $type_name = 1) {
  
  //$sum - сумма которую будем выводить в валюте
  //$money - выбранная валюта на сайте
  //$type_name - тип выводимого имени валюты (просто название валюты или международное значение, если 0 то ничего не выводим)
  
  //Российские/Белорусские рубли
  if (config('MONEY') == "RUB" || config('MONEY') == "BYN"){ 
    
    $m1 = 'рубль';
    $m2 = 'рубля';
    $m3 = 'рублей';
    
  //Украинские гривны
  }elseif (config('MONEY') == "UAH"){ 
    
    $m1 = 'гривна';
    $m2 = 'гривны';
    $m3 = 'гривен';
    
  //Казахские тенге
  }elseif (config('MONEY') == "KZT"){ 
    
    $m1 = 'тенге';
    $m2 = 'тенге';
    $m3 = 'тенге';
    
  //Узбекские сумы
  }elseif (config('MONEY') == "UZS"){ 
    
    $m1 = 'сум';
    $m2 = 'сума';
    $m3 = 'сумов';
    
  //Азербайджанские/Туркменские манаты
  }elseif (config('MONEY') == "AZN" || config('MONEY') == "TMT"){ 
    
    $m1 = 'манат';
    $m2 = 'маната';
    $m3 = 'манатов';
    
  //Грузинские лари
  }elseif (config('MONEY') == "GEL"){ 
    
    $m1 = 'лари';
    $m2 = 'лари';
    $m3 = 'лари';
    
  //Армянские драмы
  }elseif (config('MONEY') == "AMD"){ 
    
    $m1 = 'драм';
    $m2 = 'драма';
    $m3 = 'драмов';
    
  //Таджикские сомони
  }elseif (config('MONEY') == "TJS"){ 
    
    $m1 = 'сомони';
    $m2 = 'сомони';
    $m3 = 'сомони';
    
  //Киргизские сомы
  }elseif (config('MONEY') == "KGS"){ 
    
    $m1 = 'сом';
    $m2 = 'сома';
    $m3 = 'сомов';
    
  //Молдавские леи
  }elseif (config('MONEY') == "MDL"){ 
    
    $m1 = 'лей';
    $m2 = 'лея';
    $m3 = 'леев';
    
  //Доллары
  }elseif (config('MONEY') == "USD"){ 
    
    $m1 = 'доллар';
    $m2 = 'доллара';
    $m3 = 'долларов';
    
  //Евро
  }elseif (config('MONEY') == "EUR"){ 
    
    $m1 = 'евро';
    $m2 = 'евро';
    $m3 = 'евро';
    
  //Серебро
  }elseif (config('MONEY') == "SLV"){ 
    
    $m1 = 'серебро';
    $m2 = 'серебра';
    $m3 = 'серебра';
    
  //Рубины
  }elseif (config('MONEY') == "RBN"){ 
    
    $m1 = 'рубин';
    $m2 = 'рубина';
    $m3 = 'рубинов';
    
  //Монеты
  }elseif (config('MONEY') == "MON"){ 
    
    $m1 = 'монета';
    $m2 = 'монеты';
    $m3 = 'монет';
    
  //Голоса
  }elseif (config('MONEY') == "VCS"){ 
    
    $m1 = 'голос';
    $m2 = 'голоса';
    $m3 = 'голосов';
    
  }
  
  //Склоняем имя валюты
  $num = abs($sum) % 100;
  $num_x = $num % 10;
  
  if ($num > 10 && $num < 20){ 
    
    $m = $m3; 
  
  }elseif ($num_x > 1 && $num_x < 5){ 
    
    $m = $m2; 
  
  }elseif ($num_x == 1){ 
    
    $m = $m1; 
  
  }else{
    
    $m = $m3;
  
  }
  
  //Вывод имени валюты
  if ($type_name == 1){ 
    
    $mn = " ".lg($m);
  
  }elseif ($type_name == 2){ 
    
    $mn = " ".config('MONEY'); 
  
  }elseif ($type_name == 3){ 
    
    $mn = " ".lg($m)." (".config('MONEY').")"; 
  
  }else{ 
    
    $mn = NULL; 
  
  }
  
  //Десятичный формат
  if (config('MONEY_SET') == 1){
    
    return tabs($sum).tabs($mn);
    
  //Обычный формат
  }else{
    
    return intval($sum).tabs($mn);  
  
  }
  
}