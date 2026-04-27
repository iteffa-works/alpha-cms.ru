<?php
acms_header();

if (direct::e_file('style/version/'.version('DIR').'/includes/main.php') == true) {
  
  require (ROOT.'/style/version/'.version('DIR').'/includes/main.php');
  acms_footer();
  
}

if (config('MAIN_ONLINE') == 1) { require (ROOT.'/modules/users/plugins/main.php'); }
if (config('MAIN_SEARCH') == 1) { require (ROOT.'/modules/search/plugins/form/main.php'); }
if (config('MAIN_NEWS') == 1) { require (ROOT.'/modules/news/plugins/main.php'); }
direct::components(ROOT.'/main/components/main_top/', 0);
?>

<?php if (config('MAIN_MENU') == 1) : ?>
<div class="menu-info"><?=lg('Главные разделы')?></div>
<div class="menu-container">
<div class="menu-wrapper-center">
<?=direct::components(ROOT.'/main/components/main_menu_big/', 0)?>
</div>
</div>
<?php endif ?>

<?php 
require (ROOT.'/modules/photos/plugins/main.php');
require (ROOT.'/modules/blogs/plugins/main.php'); 
require (ROOT.'/modules/videos/plugins/main.php');
require (ROOT.'/modules/forum/plugins/main.php');
require (ROOT.'/modules/communities/plugins/main.php');
require (ROOT.'/modules/games/plugins/main.php');
?>

<?php if (config('MAIN_MENU2') == 1) : ?>
<div class="menu-info"><?=lg('Другие разделы')?></div>  
<div class="menu-wrapper-info">
<div class="menu-container">
<div class="menu-wrapper-center">
<?=direct::components(ROOT.'/main/components/main_menu_info/', 0)?>
</div>
</div>
</div>
<?php endif ?>

<?php
direct::components(ROOT.'/main/components/main_bottom/', 0);
acms_footer();