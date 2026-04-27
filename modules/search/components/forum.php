<?php if (config('PRIVATE_FORUM') == 1) : ?>
<a href='/m/search/?type=forum'><div class='list-menu hover'>
<?=m_icons('comments', 12)?> <?=lg('Форум')?> <span class='count'><?=db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ?) AND `BAN` = '0'", ['%'.SEARCH.'%', '%'.SEARCH.'%'])?></span>
</div></a>
<?php endif ?>