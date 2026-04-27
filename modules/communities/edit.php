<?php  
$comm = db::get_string("SELECT * FROM `COMMUNITIES` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$par = db::get_string("SELECT * FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `USER_ID` = ? AND `ACT` = ? LIMIT 1", [$comm['ID'], user('ID'), 1]);
acms_header(lg('Редактировать сообщество %s', communities::name($comm['ID'])), 'users');
communities::blocked($comm['ID']);
is_active_module('PRIVATE_COMMUNITIES');

if (!isset($comm['ID'])) {
  
  error('Неверная директива');
  redirect('/m/communities/');

}

if (isset($par['ID']) && $par['ADMINISTRATION'] == 1 || access('communities', null) == true){
  
  if (post('ok_edit_comm')){
    
    valid::create(array(
    
      'COMM_NAME' => ['name', 'text', [5, 40], 'Название', 0],
      'COMM_MESSAGE' => ['message', 'text', [0, 80], 'Описание', 0],
      'COMM_RULES' => ['rules', 'text', [0, 250], 'Правила', 0],
      'COMM_INTERESTS' => ['interests', 'text', [0, 250], 'Интересы', 0],
      'COMM_MOTTO' => ['motto', 'text', [0, 250], 'Девиз', 0],
      'COMM_ID_CATEGORY' => ['id_cat', 'number', [0, 99999], 'Категория'],
      'COMM_TYPE' => ['type', 'number', [0, 5], 'Тип сообщества'],
      'COMM_URL' => ['url', 'text', [5, 30], 'Адрес сообщества', 0]
    
    ));
    
    if ($comm['NAME'] != COMM_NAME && db::get_column("SELECT COUNT(*) FROM `COMMUNITIES` WHERE `NAME` = ? LIMIT 1", [COMM_NAME]) > 0){
      
      error('Сообщество с таким именем уже существует');
      redirect('/m/communities/edit/?id='.$comm['ID']);
    
    }
    
    if (!preg_match("#^([A-z0-9\-_])+$#ui", COMM_URL)) {
      
      error('В адресе сообщества присутствуют запрещенные символы. Только латиница, символы "_-" и цифры');
      redirect('/m/communities/edit/?id='.$comm['ID']);
    
    }
    
    if (!preg_match("~([A-z])~", COMM_URL)){
      
      error('В адресе сообщества должна содержаться хотябы одна буква (латиница)');
      redirect('/m/communities/edit/?id='.$comm['ID']);
    
    }
    
    if (COMM_URL != $comm['URL'] && db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES` WHERE `URL` = ? LIMIT 1", [COMM_URL]) > 0) {      

      error('Такой адрес сообщества уже занят. Придумайте другой');
      redirect('/m/communities/edit/?id='.$comm['ID']);
    
    }
    
    if (ERROR_LOG == 1){
      
      redirect('/m/communities/edit/?id='.$comm['ID']);
    
    }
    
    db::get_set("UPDATE `COMMUNITIES` SET `ID_CATEGORY` = ?, `NAME` = ?, `MESSAGE` = ?, `PRIVATE` = ?, `RULES` = ?, `INTERESTS` = ?, `MOTTO` = ?, `URL` = ? WHERE `ID` = ? LIMIT 1", [COMM_ID_CATEGORY, COMM_NAME, COMM_MESSAGE, COMM_TYPE, COMM_RULES, COMM_INTERESTS, COMM_MOTTO, strtolower(COMM_URL), $comm['ID']]);
    
    if (access('communities', null) == true){
      
      logs('Сообщества - редактирование сообщества [url=/public/'.$comm['URL'].']'.$comm['NAME'].'[/url]', user('ID'));
    
    }
    
    success('Изменения успешно приняты');
    redirect('/public/'.strtolower(COMM_URL));
  
  }
  
  ?>    
  <div class='list'>
  <form method='post' class='ajax-form' action='/m/communities/edit/?id=<?=$comm['ID']?>'>
  <?
  html::input('name', 'Название сообщества', null, null, tabs($comm['NAME']), 'form-control-100', 'text', null, 'text-width');
  html::input('message', 'Описание сообщества', null, null, tabs($comm['MESSAGE']), 'form-control-100', 'text', null, 'text-width');
  html::input('url', 'Адрес сообщества', null, null, tabs($comm['URL']), 'form-control-100', 'text', null, 'link'); 
  $array = array();
  $array[0] = ['Без категории', ($comm['ID_CATEGORY'] == 0 ? "selected" : null)];
  $data = db::get_string_all("SELECT * FROM `COMMUNITIES_CATEGORIES` ORDER BY `ID` DESC");  
  while ($list = $data->fetch()){
  
    $array[$list['ID']] = [$list['NAME'], ($comm['ID_CATEGORY'] == $list['ID'] ? "selected" : null)];

  }
  html::select('id_cat', $array, 'Категория', 'form-control-100-modify-select', 'list-ul'); 
  html::select('type', array(
    0 => ['Открытое сообщество', ($comm['PRIVATE'] == 0 ? "selected" : null)], 
    1 => ['Анонимное сообщество', ($comm['PRIVATE'] == 1 ? "selected" : null)], 
    2 => ['Сообщество по интересам', ($comm['PRIVATE'] == 2 ? "selected" : null)]
  ), 'Тип сообщества', 'form-control-100-modify-select', 'users');
  html::input('motto', 'Девиз сообщества', null, null, tabs($comm['MOTTO']), 'form-control-100', 'text', null, 'text-width');
  html::input('interests', 'Интересы сообщества', null, null, tabs($comm['INTERESTS']), 'form-control-100', 'text', null, 'text-width');
  html::input('rules', 'Правила сообщества', null, null, tabs($comm['RULES']), 'form-control-100', 'text', null, 'text-width');
  html::button('button ajax-button', 'ok_edit_comm', 'save', 'Сохранить');  
  ?>
  <a class='button-o' href='/public/<?=$comm['URL']?>'><?=lg('Отмена')?></a>
  </form>
  </div>
    
  <div class='list'>
  <?=lg('Вы можете безвозвратно удалить сообщество')?><br /><br />
  <a href='/m/communities/delete/?id=<?=$comm['ID']?>&<?=TOKEN_URL?>' class='button'><?=lg('Удалить сообщество')?></a>
  </div>
  <?
    
}else{
  
  error('Нет прав');
  redirect('/public/'.$comm['URL']);
  
}

back('/public/'.$comm['URL']);
acms_footer();