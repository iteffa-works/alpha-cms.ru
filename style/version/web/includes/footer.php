<div class='msgop'></div>
<div class='panel-top-optimize3'></div>
  
</div>
</div>

<?php require_once (ROOT.'/style/version/'.version('DIR').'/includes/panel-right.php'); ?>  

</div> 
</div>
</body>

<?php 
  
require_once (ROOT.'/system/connections/footer_data.php'); 

if (config('AJAX') == 1){
  
  ?><script type="text/javascript" src="/system/AJAX/change/change.js?version=<?=config('ACMS_VERSION')?>"></script><?
  
}

?>

<script type="text/javascript" src="/style/version/<?=version('DIR')?>/dialog_modal.js"></script>
<script type="text/javascript" src="/system/AJAX/AlphaPlayer/audio/player.js"></script>