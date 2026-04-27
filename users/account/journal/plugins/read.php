<?php
  
if (get('type') != 'all') {
  
  db::get_set("UPDATE `NOTIFICATIONS` SET `READ` = '0' WHERE `USER_ID` = ?", [user('ID')]);
  
}