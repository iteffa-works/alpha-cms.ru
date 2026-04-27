<?php
  
/*
---------------------------------------
Функция проверки поддержки JavaScript
для работы AJAX и всех скриптов в целом
---------------------------------------
*/
  
function js_check() {
  
  if (config('JAVASCRIPT') == 1){
    
    ?>
    <script type="text/javascript">document.write('<style>.noscript{ display: none; }</style>');</script>
    <div class='noscript'>
    <div id='modal-fixed2'></div>    
    <div id='modal-fixed'>
    <div class='modal-fixed-content'><center>
    <?=icons('times-circle-o', 50, 'fa-fw')?><br /><br />
    <font size='+1'><?=lg('Необходимо включить поддержку JavaScript в настройках вашего браузера для работы сайта')?></font><br /><br />
    </center></div>  
    </div>
    </div>
    <?
      
  }
  
}  

/*
-----------------------------
Функция проверки AJAX запроса
-----------------------------
*/
  
function ajax() {
  
  if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    
    return true;
  
  }else{
    
    return false;
    
  }
  
}

/*
---------------------------------------
Единая функция динамического обновления 
контента на сайте по интервалу
---------------------------------------
*/

function ajax_interval() {
  
  if (config('AJAX_INTERVAL_SET') == 1){
    
    ?>
    <script>   
    var IDLE_TIMEOUT = <?=config('AJAX_TIMEOUT')?>;
    var _idleSecondsCounter = 0;
    
    document.onclick = function() {
      
      _idleSecondsCounter = 0;
    
    };
    
    document.onmousemove = function() {
      
      _idleSecondsCounter = 0;
    
    };
    
    document.onkeypress = function() {
      
      _idleSecondsCounter = 0;
    
    };
    
    window.setInterval(CheckIdleTime, 1000);
    
    var tout = 1;
    
    function CheckIdleTime() {
      
      var m_ajax = document.getElementById('modal-fixed');
      var m_ajax2 = document.getElementById('modal-fixed2');
      _idleSecondsCounter++;
      
      if (_idleSecondsCounter >= IDLE_TIMEOUT) {
        
        m_ajax.style.display = 'block';
        m_ajax2.style.display = 'block';
        tout = 0;

      }
    
    }  
      
    function upgrade(){
      
      if (tout == 1){
        
        comments();
        message();
        <?php direct::components(ROOT.'/system/AJAX/interval/', 0); ?>
          
      }
      
    }
    
    function js_hooks(){
      
      <?php direct::components(ROOT.'/system/AJAX/hooks/', 0); ?>
      
    }
    
    $(document).ready(function(){
      
      setInterval('upgrade()', <?=config('AJAX_INTERVAL')?>);
    
    });
    
    </script>
    
    <div id='modal-fixed2' style='display: none;'></div>    
    <div id='modal-fixed' style='display: none;'>
    <div class='modal-fixed-content'><center>
    <?=icons('plug', 50, 'fa-fw')?><br /><br />
    <font size='+1'><?=lg('Некоторое время вы оставались неактивными. Нажмите на кнопку "Обновить страницу", чтобы возобновить работу приложения')?></font><br /><br />
    <a href='<?=REQUEST_URI?>' class='button' ajax='no' data='<?=lg('Ждите')?>...'><?=icons('refresh', 15, 'fa-fw')?> <?=lg('Обновить страницу')?></a>
    </center></div>  
    </div>
      
    <?
    
  }
    
}