<?php
$title = config('TITLE');
html::title('Мета теги');
acms_header();
access('management');
  
?>
<div class='navigation'>
<a href='/admin/desktop/'><?=icons('home', 25)?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<a href='/admin/system/'><?=lg('Настройки системы')?></a>
<?=icons('angle-right', 25, 'fa-fw')?>
<?=lg('Мета теги')?>
</div>  
<?
  
if (post('ok_meta_set')){
  
  valid::create(array(

    'TITLE' => ['title', 'text', [1, 200], 'Заголовок', 0],
    'KEYWORDS' => ['keywords', 'text', [1, 250], 'Описание', 0],
    'DESCRIPTION' => ['description', 'text', [1, 250], 'Ключевые слова', 0]
  
  ));
  
  if (ERROR_LOG == 1) {
    
    redirect('/admin/system/meta_tags/');
    
  }
  
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'TITLE', ini_data_check(TITLE));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'KEYWORDS', ini_data_check(KEYWORDS));
  ini::upgrade(ROOT.'/system/config/global/settings.ini', 'DESCRIPTION', ini_data_check(DESCRIPTION));
  
  success('Изменения успешно приняты');
  redirect('/admin/system/meta_tags/');

}

?>
<div class='list-body6'>

<div class='list-menu'>
<form method='post' action='/admin/system/meta_tags/' class='ajax-form'>

<?=html::input('title', 'Заголовок', 'Заголовок главной страницы. От 1 до 200 символов:', null, tabs($title), 'form-control-100', null, 'text', 'code')?>
<?=html::input('description', 'Описание', 'Описание сайта. От 1 до 250 символов:', null, tabs(config('DESCRIPTION')), 'form-control-100', null, 'text', 'code')?>
<?=html::input('keywords', 'Ключевые слова', 'Ключевые слова. От 1 до 250 символов:', null, tabs(config('KEYWORDS')), 'form-control-100', null, 'text', 'code')?>  

<?=html::button('button ajax-button', 'ok_meta_set', 'save', 'Сохранить изменения')?>

</form>
</div>
</div>
<br />
<?
  
back('/admin/system/');
acms_footer();