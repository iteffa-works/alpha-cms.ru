<?php
$account = db::get_string("SELECT * FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$settings = db::get_string("SELECT * FROM `USERS_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [$account['ID']]);  

acms_header(lg('Личная информация %s', user::login_mini($account['ID'])));

if (!isset($account['ID'])){
  
  error('Неверная директива');
  redirect('/');

}

require_once (ROOT.'/users/account/page/plugins/block.php');

if (get('get')) {
  
  get_check_valid();
  
  if (is_file(ROOT.'/users/account/form/edit/'.direct::get('get').'.php') && $account['ID'] == user('ID')) {
    
    require_once (ROOT.'/users/account/form/edit/'.direct::get('get').'.php');
    back('/account/form/?id='.$account['ID']);
    
  }else{
    
    redirect('/account/form/?id='.$account['ID']);
    
  }
  
}else{
  
  $id = $account['ID'];
  require_once (ROOT.'/users/account/page/plugins/info_user.php');
  direct::components(ROOT.'/users/account/form/components/');
  back('/id'.$id, 'К странице');
  
}

acms_footer();