<?php
define('PLAYER_TOP_HEIGHT', 52);
define('PLAYER_TOP_HEIGHT_OPTIMIZE', 37);
define('PLAYER_BAR_WIDTH', 302);
?>
  
<audio><source></audio>
  
<div id='player-mini' style='height: <?=PLAYER_TOP_HEIGHT_OPTIMIZE?>px; display: none;'></div>
<div id='player-mini2' style='top: <?=PLAYER_TOP_HEIGHT?>px; display: none;' class='admin-touch-player-content'>
<div class='admin-touch-player-content2'>
<div class="admin-touch-player-play" style="display: none;" id="play" onclick="PlayPause('play')"><i class="fa fa-play fa-lg"></i></div>
<div class="admin-touch-player-play" style="" id="pause" onclick="PlayPause('pause')"><i class="fa fa-pause fa-lg"></i></div>   
<div class='admin-touch-player-list' onclick='player()'>
<span id='play_mini_artist'><b><?=lg('Нет артиста')?></b></span> - 
<span id='play_mini_name'><?=lg('Нет песни')?></span>
</div>  
<div class='admin-touch-player-exit' onclick='mini_player_hide()' id='pause2'><?=icons('times', 23)?></i></div> 
</div>  
</div>
  
<div id='player_phone' style='display: none;' class='admin-touch-player-modal-phone' onclick='player()'></div>    
<div id='player_с' style='display: none;' class='admin-touch-player-modal'>      
<?php require (ROOT.'/system/AJAX/AlphaPlayer/php/player_show.php'); ?>     
</div>