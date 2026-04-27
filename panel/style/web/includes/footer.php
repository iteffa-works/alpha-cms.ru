</div>
</div> 
</div>   
</div>

<?php
require_once (ROOT.'/system/connections/footer_data.php');
?>

</body>
  
<?php if (config('AJAX') == 1){ ?>
  
  <script type="text/javascript" src="/system/AJAX/change/change.js?v=<?=front_hash()?>"></script>
  
<?php } ?>
  
<script type="text/javascript" src="/system/AJAX/AlphaPlayer/audio/player.js?v=<?=front_hash()?>"></script>