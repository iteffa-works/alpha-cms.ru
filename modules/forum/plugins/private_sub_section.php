<?php
  
if (access('forum', null) == false && $scsub['PRIVATE'] == 1){
  
  ?>
  <div class='list'><center>
  <?=icons('lock', 50)?><br />
  <font size='+1'><?=lg('Данный подраздел может просматривать только администрация')?></font>
  </center></div>
  <?
  
  back('/m/forum/sc/?id='.$scsub['SECTION_ID']);
  acms_footer();
  
}  

if (access('forum', null) == false && $scsub['RATING'] > user('RATING')){
  
  ?>
  <div class='list'><center>
  <?=icons('lock', 50)?><br />
  <font size='+1'><?=lg('Необходимо иметь рейтинг не меньше %d, чтобы получить доступ к этому подразделу и просматривать темы в ней', $scsub['RATING'])?></font>
  </center></div>
  <?
  
  back('/m/forum/sc/?id='.$scsub['SECTION_ID']);
  acms_footer();
  
}