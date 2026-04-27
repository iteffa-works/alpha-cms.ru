<div class='player-play-phone'>
  
<div class='player-close' onclick='player()'><?=icons('times', 18)?></div>
  
<br /><br /><br />
<center>
<div class='player-img'><img src='/files/upload/music/no_image.png' id='player-img' style='max-width: 120px;'></div>
</center>
<br />
  
<div class='player-bg' id='player-play'>
<div class='player-bar' width='<?=PLAYER_BAR_WIDTH?>'>
</div>
</div>
  
<div id='player-data' array='' play_id='' id_post='' id_key=''></div>

<div style='position: relative;'>
<div id='timer' class='player-timer'>0:00</div>  
<div class='duration player-duration'>0:00</div>
<div class='player-list-count'>
<span id='play_count1'>0</span> <?=lg('из')?> <span id='play_count2'>0</div>
</div>
</div>
  
<div class='player-music-info'>
<div id='play_name' class='player-name'><?=lg('Нет песни')?></div>
<div id='play_artist' class='player-artist'><?=lg('Нет артиста')?></div> 
</div>
  
<div class='player-music-menu'>
<div class='player-back' onclick='player_backward()'><?=icons('backward', 18)?></div>
<div class="player-play_or_pause" style="display: none;" id="play3" onclick="PlayPause('play')"><?=icons('play', 25)?></div>
<div class="player-play_or_pause" style="" id="pause3" onclick="PlayPause('pause')"><span style='position: relative; right: 2px;'><?=icons('pause', 25)?></span></div>  
<div class='player-forward' onclick='player_forward()'><?=icons('forward', 18)?></div> 
</div> 
  
<div class='player-modal-button-op'>
  
<button style="display: none;" class="player-modal-button" id="volumep" onclick="volume()"><?=icons('volume-off', 21)?></button><button class="player-modal-button" id="volumem" onclick="volume()"><?=icons('volume-up', 21)?></button><a class="player-modal-button" ajax="no" id="player-music-download" href=""><?=icons('download', 21)?></a><a class="player-modal-button" id="player-music-comments" onclick="player()" href=""><?=icons('comment', 21)?></a>  
  
</div>  
  
</div>