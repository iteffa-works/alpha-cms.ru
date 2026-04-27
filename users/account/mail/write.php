<?php 
html::title('Написать');
acms_header();
access('users');

?>  
<div id="search_close">
<div class="search_result"></div>
<div id="search-phone" style="display: none"></div>
</div>
  
<div class='message'> 
<div class='mess-circle1'></div> 
<div class='mess-circle2'></div>                             
<span><?=lg('Начните общение')?></span>
</div>
<div class='message2'><div>
<?=icons('envelope-o', 80)?><br /><br />
<span>
    
<?=lg('Воспользуйтесь поиском и найдите человека, чтобы написать ему')?>
      
<br /><br />
</span>
</div></div>
<?

back('/account/mail/');
acms_footer();