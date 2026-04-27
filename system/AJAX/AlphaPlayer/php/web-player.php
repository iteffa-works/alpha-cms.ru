<?php
define('PLAYER_TOP_HEIGHT', 45);
define('PLAYER_BAR_WIDTH', 302);
?>

<audio><source></audio>
  
<div id='player-mini' style='display: none;'></div>
<div id='player-mini2' style='top: <?=PLAYER_TOP_HEIGHT?>px; display: none;' class='web-player-content'>
<div class='web-player-content2'>
<div class="web-player-play" style="display: none;" id="play" onclick="PlayPause('play')"><i class="fa fa-play fa-lg"></i></div>
<div class="web-player-play" style="" id="pause" onclick="PlayPause('pause')"><i class="fa fa-pause fa-lg"></i></div>   
<div class='web-player-list' onclick='player()'>
<span id='play_mini_artist'><b><?=lg('Нет артиста')?></b></span> - 
<span id='play_mini_name'><?=lg('Нет песни')?></span>
</div>  
<div class='web-player-exit' onclick='mini_player_hide()' id='pause2'><i class='fa fa-times fa-lg'></i></div> 
</div>  
</div>
  
<div id='player_phone' style='display: none;' class='web-player-modal-phone' onclick='player()'></div>    
<div id='player_с' style='display: none;' class='web-player-modal'>      
<?php require (ROOT.'/system/AJAX/AlphaPlayer/php/player_show.php'); ?>     
</div>