<?php if (config('PRIVATE_COMMUNITIES') == 1) : ?>
<a href='/m/search/?type=communities'><div class='list-menu hover'>
<?=m_icons('users', 12)?> <?=lg('Сообщества')?> <span class='count'><?=db::get_column("SELECT COUNT(*) FROM `COMMUNITIES` WHERE (`NAME` LIKE ? OR `MESSAGE` LIKE ? OR `URL` LIKE ? OR `RULES` LIKE ? OR `INTERESTS` LIKE ? OR `MOTTO` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%'])?></span>
</div></a>
<?php endif ?>