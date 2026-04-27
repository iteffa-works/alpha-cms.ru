<?php
  
header("Content-type: text/html");
echo '<?xml version="1.0" encoding="utf-8"?>'; 

?>

  <!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd"        
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
    
    <head>
    
    <title><?=config('TITLE')?></title>
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="/install/style/style.css?id=1" type="text/css" />
    <link rel="stylesheet" href="/style/fonts/font-awesome.css"> 
    
    </head>
    
    <body>
    
    <div class='title'><img src='/install/style/logo.png' style='max-width: 150px;'></div>
    
    <div class='title2'><?=LG("Установка")?> <?=tabs(config('ACMS_NAME'))?></div>    
      
<?