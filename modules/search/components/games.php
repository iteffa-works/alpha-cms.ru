<?php if (config('PRIVATE_GAMES') == 1) : ?>
<a href='/m/search/?type=games'><div class='list-menu hover'>
<?=m_icons('gamepad', 12)?> <?=lg('Онлайн игры')?> <span class='count'><?=db::get_column("SELECT COUNT(*) FROM `GAMES` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%'])?></span>
</div></a>
<?php endif ?>