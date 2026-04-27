<div class="list-menu">

<div class="user-avatar">
<?=icons('user-secret', 50)?>
</div>

<div class="user-login">
<b><?=lg('Гость')?></b>
<br />
<span class="user-login-age">
<?=lg('Посл. визит')?>: <?=ftime($list['DATE_VISIT'])?>
<br /><?=lg('Первый визит')?>: <?=ftime($list['DATE_CREATE'])?>
<br /><?=lg('Переходов')?>: <?=$list['TRANSITIONS']?>
  
<?php
if (access('guests_info', null, 1)){
  
  ?>
  <br /><?=lg('IP')?>: <?=$list['IP']?></font>
  <br /><?=lg('Браузер')?>: <?=$list['BROWSER']?></font>
  <?

} 
?>
  
</span>
</div>

</div>