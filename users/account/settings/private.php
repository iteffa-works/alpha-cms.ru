<?php  
html::title('Настройки приватности');
acms_header();
access('users');

?><div class='list-body'><?
direct::components(ROOT.'/users/account/settings/components/private/', 0);
?></div><?

back('/account/settings/');
acms_footer();