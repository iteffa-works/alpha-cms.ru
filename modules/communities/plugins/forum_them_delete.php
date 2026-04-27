<?php
  
if (get('get') == 'delete_ok'){
  
  get_check_valid();
  
  db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$them['ID'], 'communities_forum_them']);
  db::get_set("DELETE FROM `EYE` WHERE `OBJECT_ID` = ? AND `TYPE` = ?", [$them['ID'], 'communities_forum_them']);
  db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$them['ID'], 'communities_forum_them']);
  
  $data = db::get_string_all("SELECT * FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$them['ID'], 'communities_forum_them']);
  while ($list = $data->fetch()) {
    
    db::get_set("DELETE FROM `LIKES` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'communities_forum_them']);
    db::get_set("DELETE FROM `ATTACHMENTS` WHERE `ID_POST` = ? AND `TYPE_POST` = ?", [$list['ID'], 'communities_forum_them']);
    db::get_set("DELETE FROM `COMMENTS` WHERE `ID` = ? LIMIT 1", [$list['ID']]);
  
  }
  
  db::get_set("DELETE FROM `COMMUNITIES_FORUM_THEM` WHERE `ID` = ? LIMIT 1", [$them['ID']]);
  
  success('Удаление прошло успешно');
  redirect('/m/communities/forum/?id='.$them['COMMUNITY_ID'].'&id_sc='.$them['SECTION_ID']);

}

if (get('get') == 'delete'){
  
  get_check_valid();
  
  ?>
  <div class='list'>
  <?=lg('Вы действительно хотите удалить тему')?> <b><?=tabs($them['NAME'])?></b>?<br /><br />
  <a href='/m/communities/forum/?id=<?=$them['COMMUNITY_ID']?>&id_them=<?=$them['ID']?>&get=delete_ok&<?=TOKEN_URL?>' class='button'><?=icons('trash', 17, 'fa-fw')?> <?=lg('Удалить')?></a>
  <a href='/m/communities/forum/?id=<?=$them['COMMUNITY_ID']?>&id_them=<?=$them['ID']?>' class='button-o'><?=lg('Отмена')?></a>
  </div>
  <?
  
}