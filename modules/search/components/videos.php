<?php if (config('PRIVATE_VIDEOS') == 1) : ?>
<a href='/m/search/?type=videos'><div class='list-menu hover'>
<?=m_icons('film', 12)?> <?=lg('Видео')?> <span class='count'><?=db::get_column("SELECT COUNT(DISTINCT `VIDEOS`.`ID`) AS `count_videos` FROM `VIDEOS` LEFT JOIN `VIDEOS_DIR` ON (`VIDEOS_DIR`.`ID` = `VIDEOS`.`ID_DIR` OR `VIDEOS_DIR`.`ID_DIR` = `VIDEOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`VIDEOS`.`NAME` LIKE ? OR `VIDEOS`.`MESSAGE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%'])?></span>
</div></a>
<?php endif ?>