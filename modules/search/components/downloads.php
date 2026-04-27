<?php if (config('PRIVATE_DOWNLOADS') == 1) : ?>
<a href='/m/search/?type=downloads'><div class='list-menu hover'>
<?=m_icons('download', 12)?> <?=lg('Загрузки')?> <span class='count'><?=db::get_column("SELECT COUNT(*) FROM `DOWNLOADS` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%'])?></span>
</div></a>
<?php endif ?>