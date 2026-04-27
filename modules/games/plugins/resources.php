<?php if (user('ID') > 0) : ?>
<div class='list'>
<span class='resources_info'><?=lg('Баланс')?>:
<?php if (config('SHOP_REP') == 1) : ?>
<a href='/shopping/rep/' style='float: right; font-size: 13px'><?=lg('Пополнить счет')?></a>
<?php endif ?>
<br />
<b><?=icons('money', 14, 'fa-fw')?> <?=money(user('MONEY'), 2)?></b>
<?php if (config('BALLS') == 1) : ?>
<b><?=icons('database', 14, 'fa-fw')?> <?=num_decline(user('BALLS'), ['балл', 'балла', 'баллов'], 1)?></b>
<?php endif ?>
</span>
</div>
<?php endif ?>