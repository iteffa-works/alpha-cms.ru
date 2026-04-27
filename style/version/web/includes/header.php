<?php 
header("Content-type: text/html");
?>

<!DOCTYPE html>        
<html lang="ru">
    
<head>
    
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="<?=tabs(config('DESCRIPTION'))?>">
<meta name="keywords" content="<?=tabs(config('KEYWORDS'))?>">
<meta name="theme-color" content="#AFDEFF">  
<link rel="stylesheet" href="/system/AJAX/AlphaPlayer/audio/style.css">
<link rel="stylesheet" href="/style/version/<?=version('DIR')?>/dialog_modal.css" type="text/css" />
    
<?php require_once (ROOT.'/system/connections/header_data.php'); ?>

</head>    
<body>
  
<?php
require_once (ROOT.'/system/AJAX/AlphaPlayer/php/web-player.php');
?>
  
<div class='circle4'></div>
<div class='circle5'></div>  
<div class='circle6'></div> 
  
<div id="content">

<title><?=lg(config('TITLE'))?></title> 
  
<div class='panel-top-optimize3'></div>  

<?php
require_once (ROOT.'/style/version/'.version('DIR').'/includes/panel-left.php');
require_once (ROOT.'/style/version/'.version('DIR').'/includes/panel-top.php');
?>

<div class="middle">
<div class="wrapper">
  
<div class="ajax_indication" data="<?=lg('Ждите')?>..."></div>
<div id="ajax_load">