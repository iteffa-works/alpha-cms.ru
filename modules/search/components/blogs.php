<?php if (config('PRIVATE_BLOGS') == 1) : ?>
<a href='/m/search/?type=blogs'><div class='list-menu hover'>
<?=m_icons('book', 12)?> <?=lg('Блоги')?> <span class='count'><?=db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ?) AND `PRIVATE` = '0'", ['%'.SEARCH.'%', '%'.SEARCH.'%'])?></span>
</div></a>
<?php endif ?>