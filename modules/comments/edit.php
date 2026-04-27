<?php
acms_header('Редактировать комментарий', 'users');
get_check_valid();

$comments = db::get_string("SELECT `ID`,`MESSAGE`,`USER_ID` FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]);
$action2 = base64_decode(tabs(get('action')));
$type = tabs(get('type'));
$action = '/m/comments/edit/?id='.$comments['ID'].'&action='.tabs(get('action')).'&type='.$type.'&'.TOKEN_URL;

if (isset($comments['ID']) && str($action2) > 0) {
  
  if ($comments['USER_ID'] == user('ID') || access('comments', null) == true) {
    
    if (post('ok_comm')){
      
      $at = db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? LIMIT 1", [$type, $comments['ID']]);
      $limit = ($at > 0 ? 0 : 1);
      
      valid::create(array(
        
        'COMMENTS' => ['message', 'text', [$limit, 5000], 'Сообщение', 0]
      
      ));
      
      if (ERROR_LOG == 1){
        
        redirect($action);
      
      }
      
      db::get_set("UPDATE `COMMENTS` SET `MESSAGE` = ?, `EDIT_TIME` = ?, `EDIT_USER_ID` = ? WHERE `ID` = ? LIMIT 1", [COMMENTS, TM, user('ID'), $comments['ID']]);
      db::get_set("UPDATE `ATTACHMENTS` SET `ID_POST` = ?, `ACT` = '1' WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ?", [$comments['ID'], user('ID'), $type]);
      
      if (access('comments', null) == true){
        
        logs('Комментарии - редактирование комментария', user('ID'));
      
      }
      
      redirect($action2);
    
    }
  
    define('ACTION', $action);
    define('TYPE', $type);
    define('ID', $comments['ID']);
  
    ?>    
    <div class='list'>
    <form method='post' class='ajax-form' action='<?=$action?>'>
    <?=html::textarea(tabs($comments['MESSAGE']), 'message', 'Введите содержимое', null, 'form-control-textarea', 9)?>  
    <br /><br />
    <?=html::button('button ajax-button', 'ok_comm', 'save', 'Сохранить')?>
    <a class='button-o' href='<?=$action2?>'><?=lg('Отмена')?></a>
    </form>
    </div>
    <?
      
  }else{
    
    error('Неверная директива');
    redirect('/');
    
  }
  
}else{
  
  error('Неверная директива');
  redirect('/');
  
}

back($action2, 'Назад');
acms_footer();