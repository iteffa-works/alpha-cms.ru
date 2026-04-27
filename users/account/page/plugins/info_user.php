<div class="list">
<div class="user-avatar">
<?=user::avatar($id, 50, 1)?>
</div>
<div class="user-login">
<?=user::login($id, 0, 1)?>
<br />
<span class="user-login-age"><?=lg('Страница в интернете')?><br />ID: <?=$id?></span>
</div>  
</div>