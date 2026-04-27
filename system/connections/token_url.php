<?php
  
$token_id = csrf::token_id();
$token_value = csrf::token($token_id);
$token_url = $token_id."=".$token_value;

define('TOKEN_URL', $token_url);