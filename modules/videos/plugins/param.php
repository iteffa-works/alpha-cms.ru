<?php

if (db::get_column("SELECT COUNT(*) FROM `VIDEOS_DIR` WHERE `USER_ID` = ? AND `PRIVATE` = '3' AND `NAME` = 'Вложения' AND `ID_DIR` = '0' LIMIT 1", [user('ID')]) == 0) {
  
  db::get_add("INSERT INTO `VIDEOS_DIR` (`USER_ID`, `PRIVATE`, `NAME`, `ID_DIR`) VALUES (?, ?, ?, ?)", [user('ID'), 3, 'Вложения', 0]);
  
}

if (db::get_column("SELECT COUNT(*) FROM `VIDEOS_DIR` WHERE `USER_ID` = '0' AND `ID_DIR` = '0' LIMIT 1") == 0) {
  
  db::get_add("INSERT INTO `VIDEOS_DIR` (`USER_ID`, `PRIVATE`, `NAME`, `ID_DIR`) VALUES (?, ?, ?, ?)", [0, 0, 'Системная папка', 0]);
  
}