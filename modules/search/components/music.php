<?php if (config('PRIVATE_MUSIC') == 1) : ?>
<a href='/m/search/?type=music'><div class='list-menu hover'>
<?=m_icons('music', 12)?> <?=lg('Музыка')?> <span class='count'><?=db::get_column("SELECT COUNT(DISTINCT `MUSIC`.`ID`) AS `count_music` FROM `MUSIC` LEFT JOIN `MUSIC_DIR` ON (`MUSIC_DIR`.`ID` = `MUSIC`.`ID_DIR` OR `MUSIC_DIR`.`ID_DIR` = `MUSIC`.`ID_DIR`) WHERE `PRIVATE` = '0' AND (`MUSIC`.`NAME` LIKE ? OR `MUSIC`.`FACT_NAME` LIKE ? OR `MUSIC`.`ARTIST` LIKE ? OR `MUSIC`.`GENRE` LIKE ?)", ['%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%', '%'.SEARCH.'%'])?></span>
</div></a>
<?php endif ?>