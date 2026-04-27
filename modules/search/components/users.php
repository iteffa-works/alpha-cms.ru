<?php
$ex = explode(' ', SEARCH);
if (isset($ex[0])) { $ex0 = $ex[0]; }else{ $ex0 = 'none'; }
if (isset($ex[1])) { $ex1 = $ex[1]; }else{ $ex1 = 'none'; } 
?>

<a href='/m/search/?type=users'><div class='list-menu hover'>
<?=m_icons('user', 12)?> <?=lg('Пользователи')?> <span class='count'><?=db::get_column("SELECT COUNT(*) FROM `USERS` LEFT JOIN `USERS_SETTINGS` ON (`USERS_SETTINGS`.`USER_ID` = `USERS`.`ID`) WHERE (`USERS`.`LOGIN` LIKE ? OR `USERS_SETTINGS`.`NAME` LIKE ? OR `USERS_SETTINGS`.`SURNAME` LIKE ? OR `USERS_SETTINGS`.`NAME` LIKE ? OR `USERS_SETTINGS`.`SURNAME` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.$ex0.'%', '%'.$ex1.'%'])?></span>
</div></a>