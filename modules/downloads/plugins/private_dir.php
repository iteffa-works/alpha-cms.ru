<?php

if (access('downloads', null) == false && $dir['RATING'] > user('RATING')){
  
  ?>
  <div class='list'><center>
  <?=icons('lock', 50)?><br />
  <font size='+1'><?=lg('Необходимо иметь рейтинг не меньше %d, чтобы получить доступ к категории', $dir['RATING'])?></font>
  </center></div>
  <?
  
  back('/m/downloads/?id='.$dir['ID_DIR']);
  acms_footer();
  
}