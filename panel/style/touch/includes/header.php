<?php
  
/*
----------------------------------------
Шапка версии Touch для панели управления
----------------------------------------
*/  
  
header("Content-type: text/html");
echo '<?xml version="1.0" encoding="utf-8"?>'; 

?>
  
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd"        
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
  
<head>
    
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?=tabs(config('DESCRIPTION'))?>">
<meta name="keywords" content="<?=tabs(config('KEYWORDS'))?>">
  
<link rel="shortcut icon" href="/panel/style/touch/favicon.ico?v=<?=front_hash()?>" />
<link rel="stylesheet" href="/panel/style/touch/style.css?v=<?=front_hash()?>" type="text/css" />
<link rel="stylesheet" href="/style/font-awesome/font-awesome.css?v=<?=front_hash()?>">
<link rel="stylesheet" href="/style/css/css.css?v=<?=front_hash()?>">
<link rel="stylesheet" href="/system/AJAX/AlphaPlayer/audio/style.css?v=<?=front_hash()?>">  
<link rel="stylesheet" href="/panel/style/touch/sidebar.css?v=<?=front_hash()?>" type="text/css" /> 
  
</head>
<body> 
  
<?php
require_once (ROOT.'/system/AJAX/AlphaPlayer/php/admin-touch-player.php');
?>
  
<div id="content"> 
  
<?php
direct::components(ROOT.'/system/functions/panel/', 0);
require_once (ROOT.'/panel/style/touch/includes/panel-left.php');
require_once (ROOT.'/panel/style/touch/includes/panel-top.php');
?>
  
<title><?=config('TITLE')?></title>

<?php 
require_once (ROOT.'/system/connections/panel/admin_info.php');
?>

<div class="panel-middle">
<div class="ajax_indication" data="<?=lg('Ждите')?>..."></div>
<div id="ajax_load">