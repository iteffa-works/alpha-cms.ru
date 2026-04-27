<?php if (config('PRIVATE_PHOTOS') == 1) : ?>
<a href='/m/search/?type=photos'><div class='list-menu hover'>
<?=m_icons('image', 12)?> <?=lg('Фото')?> <span class='count'><?=db::get_column("SELECT COUNT(DISTINCT `PHOTOS`.`ID`) AS `count_photos` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`PHOTOS`.`NAME` LIKE ? OR `PHOTOS`.`MESSAGE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%'])?></span>
</div></a>
<?php endif ?>