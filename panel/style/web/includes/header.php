<!DOCTYPE html>
<html lang="ru">

<head>
  
<meta charset="UTF-8">
  
<link rel="shortcut icon" href="/panel/style/web/favicon.ico?v=<?=front_hash()?>" />
<link rel="stylesheet" href="/panel/style/web/style.css?v=<?=front_hash()?>" type="text/css" />
<link rel="stylesheet" href="/style/font-awesome/font-awesome.css?v=<?=front_hash()?>">
<link rel="stylesheet" href="/style/css/css.css?v=<?=front_hash()?>"> 
<link rel="stylesheet" href="/system/AJAX/AlphaPlayer/audio/style.css?v=<?=front_hash()?>">  
  
</head>
  
<body>
  
<?=direct::components(ROOT.'/system/functions/panel/', 0)?>
  
<?php
require_once (ROOT.'/system/AJAX/AlphaPlayer/php/admin-web-player.php');
?> 
  
<div id="content"> 
  
<?php
require_once (ROOT.'/panel/style/web/includes/panel-top.php');
require_once (ROOT.'/panel/style/web/includes/panel-left.php');
?>
  
<title><?=config('TITLE')?></title>

<?php 
require_once (ROOT.'/system/connections/panel/admin_info.php');
?>

<div class="panel-middle">
<div class="ajax_indication" data="<?=lg('Ждите')?>..."></div>
<div id="ajax_load">