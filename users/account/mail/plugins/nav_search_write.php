<form method='post'>
<span class='panel-top-search-icons'><?=icons('search')?></span>
<input type='text' name='search' class='panel-top-search' placeholder='<?=lg('Найти человека по логину')?>' autocomplete='off' action='/system/AJAX/php/messages/mail_search_write.php?<?=TOKEN_URL?>'>
</form>