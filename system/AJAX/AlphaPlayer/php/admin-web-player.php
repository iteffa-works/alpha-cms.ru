<?php
define('PLAYER_TOP_HEIGHT', 55);
define('PLAYER_TOP_HEIGHT_OPTIMIZE', 35);
define('PLAYER_BAR_WIDTH', 320);
?>
  
<audio><source></audio>
  
<div id='player-mini' style='height: <?=PLAYER_TOP_HEIGHT_OPTIMIZE?>px; display: none;'></div>
<div id='player-mini2' style='top: <?=PLAYER_TOP_HEIGHT?>px; display: none;' class='admin-web-player-content'>
<div class='admin-web-player-content2'>
<div class="admin-web-player-play" style="display: none;" id="play" onclick="PlayPause('play')"><i class="fa fa-play fa-lg"></i></div>
<div class="admin-web-player-play" style="" id="pause" onclick="PlayPause('pause')"><i class="fa fa-pause fa-lg"></i></div>   
<div class='admin-web-player-list' onclick='player()'>
<span id='play_mini_artist'><b><?=lg('Нет артиста')?></b></span> - 
<span id='play_mini_name'><?=lg('Нет песни')?></span>
</div>  
<div class='admin-web-player-exit' onclick='mini_player_hide()' id='pause2'><i class='fa fa-times fa-lg'></i></div> 
</div>  
</div>
  
<div id='player_phone' style='display: none;' class='admin-web-player-modal-phone' onclick='player()'></div>    
<div id='player_с' style='display: none;' class='admin-web-player-modal'>      
<?php require (ROOT.'/system/AJAX/AlphaPlayer/php/player_show.php'); ?>     
</div>