<form method='post'>
<span class='panel-top-search-icons'><?=icons('search')?></span>
<input type='text' name='search' class='panel-top-search' placeholder='<?=lg('Поиск друзей')?>' autocomplete='off' action='/system/AJAX/php/friends_search.php?id=<?=intval(get('id'))?>&<?=TOKEN_URL?>'>
</form>