<?php
html::title('Правила');
acms_header();

if (access('rules', null) == true){
  
  ?>
  <div class='list'>
  <a href='/m/rules/add/' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Добавить раздел правил')?></a>
  </div>
  <?
  
}

$column = db::get_column("SELECT COUNT(*) FROM `RULES`");

if ($column == 0){ 
  
  html::empty('Пока пусто');
  
}else{
  
  ?><div class='list-body'><? 
  
}

$data = db::get_string_all("SELECT * FROM `RULES` ORDER BY `TIME` DESC");
while ($list = $data->fetch()) {
  
  ?>
  <a href='/m/rules/show/?id=<?=$list['ID']?>'><div class='list-menu hover'>
  <?=icons('angle-double-right', 17, 'fa-fw')?> <?=lg(tabs($list['NAME']))?>
  </div></a>
  <?

}

if ($column > 0){

  ?></div><?
  
}

back('/', 'На главную');
acms_footer();