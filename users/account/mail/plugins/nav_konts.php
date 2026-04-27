<form method='post'>
<span class='panel-top-search-icons'><?=icons('search')?></span>
<input type='text' name='search' class='panel-top-search' placeholder='<?=lg('Поиск собеседника')?>' autocomplete='off' action='/system/AJAX/php/messages/mail_search.php?<?=TOKEN_URL?>'>
</form>