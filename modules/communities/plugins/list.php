<?php

$count = db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ? LIMIT 1", [$list['ID'], 1]);

if (isset($dop)){
  
  $dop = $dop;
  
}else{
  
  $dop = null;  
  
}
  
$comm_list = '
<div class="list-menu">

<div class="user-avatar">
<a href="/public/'.$list['URL'].'">'.communities::avatar($list['ID'], 55).'</a>
</div>

<div class="user-login">
<a href="/public/'.$list['URL'].'" style="color: #424B4F">
<b>'.communities::name($list['ID']).'</b>
<div style="margin-top: 7px; color: #8197A2; font-size: 13px">'.communities::mess($list['ID']).'</div>
<div style="margin-top: 7px; margin-bottom: 10px; color: #8197A2; font-size: 12px; font-weight: 500">'.icons('user', 14, 'fa-fw').' '.num_format($count, 2).' '.num_decline($count, ['участник', 'участника', 'участников'], 0).'</div>
</a>
</div>
'.$dop.'
</div>
';