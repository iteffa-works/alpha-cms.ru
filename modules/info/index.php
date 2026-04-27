<?php
html::title('Информация');
acms_header();

if (access('info', null) == true){
  
  ?>
  <div class='list'>
  <a href='/m/info/add/' class='btn'><?=icons('pencil', 15, 'fa-fw')?> <?=lg('Добавить информацию')?></a>
  </div>
  <?
  
}

$column = db::get_column("SELECT COUNT(*) FROM `INFO`");

if ($column == 0){ 
  
  html::empty('Пока нет информации');
  
}else{
  
  ?><div class='list-body'><? 
  
}

$data = db::get_string_all("SELECT * FROM `INFO` ORDER BY `TIME` DESC");
while ($list = $data->fetch()) {
  
  ?>
  <a href='/m/info/show/?id=<?=$list['ID']?>'><div class='list-menu hover'>
  <?=icons('angle-double-right', 17, 'fa-fw')?> <?=lg(tabs($list['NAME']))?>
  </div></a>
  <?

}

if ($column > 0){

  ?></div><?
  
}

back('/', 'На главную');
acms_footer();