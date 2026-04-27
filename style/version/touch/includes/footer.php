</div></div>
  
<?php require_once (ROOT.'/style/version/'.version('DIR').'/includes/panel-bottom.php'); ?> 
  
</body>

<?php 
  
require_once (ROOT.'/system/connections/footer_data.php'); 

if (config('AJAX') == 1){
  
  ?><script type="text/javascript" src="/system/AJAX/change/change.js"></script><?
  
}

?>
 
<script type="text/javascript" src="/style/version/<?=version('DIR')?>/sidebar.js"></script>
<script type="text/javascript" src="/system/AJAX/AlphaPlayer/audio/player.js"></script>