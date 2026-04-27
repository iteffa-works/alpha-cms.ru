<?php
  
db::get_set("UPDATE `TAPE` SET `READ` = '0' WHERE `USER_ID` = ?", [user('ID')]);