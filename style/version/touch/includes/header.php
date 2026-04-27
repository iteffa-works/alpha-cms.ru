<?php 
header("Content-type: text/html");
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd"        
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
    
<head>
    
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="<?=tabs(config('DESCRIPTION'))?>">
<meta name="keywords" content="<?=tabs(config('KEYWORDS'))?>">
<meta name="theme-color" content="#AFDEFF">  
<link rel="stylesheet" href="/style/version/<?=version('DIR')?>/sidebar.css" type="text/css" />
<link rel="stylesheet" href="/system/AJAX/AlphaPlayer/audio/style.css"> 
    
<?php require_once (ROOT.'/system/connections/header_data.php'); ?>

</head>    
<body> 
  
<?php
require_once (ROOT.'/system/AJAX/AlphaPlayer/php/touch-player.php');
?>
  
<div id="content">

<title><?=lg(config('TITLE'))?></title>  

<?php
require_once (ROOT.'/style/version/'.version('DIR').'/includes/panel-bottom-button-hover.php');
require_once (ROOT.'/style/version/'.version('DIR').'/includes/panel-left.php');
require_once (ROOT.'/style/version/'.version('DIR').'/includes/panel-top.php');
?>
  
<div class="ajax_indication" data="<?=lg('Ждите')?>..."></div>
<div id="ajax_load">