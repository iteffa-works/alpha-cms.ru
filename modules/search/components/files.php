<?php if (config('PRIVATE_FILES') == 1) : ?>
<a href='/m/search/?type=files'><div class='list-menu hover'>
<?=m_icons('file', 12)?> <?=lg('Файлы')?> <span class='count'><?=db::get_column("SELECT COUNT(DISTINCT `FILES`.`ID`) AS `count_files` FROM `FILES` LEFT JOIN `FILES_DIR` ON (`FILES_DIR`.`ID` = `FILES`.`ID_DIR` OR `FILES_DIR`.`ID_DIR` = `FILES`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`FILES`.`NAME` LIKE ? OR `FILES`.`MESSAGE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%'])?></span>
</div></a>
<?php endif ?>