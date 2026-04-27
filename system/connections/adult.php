<?php if (isset($adult_set) && $adult_set == 1) : ?>
<?php $adult = age(user('ID'), settings('G_R'), settings('M_R'), settings('D_R')); ?>
<?php if (settings('D_R') == 0 || $adult < 18) : ?>
<?php $root = 'photos'; ?>  
<?php if (get('path') == 'videos') : ?>
<?php $root = 'videos'; ?>
<?php endif ?>  
<?php if (get('path') == 'music') : ?>
<?php $root = 'music'; ?>
<?php endif ?>  
<?php if (get('path') == 'files') : ?>
<?php $root = 'files'; ?>
<?php endif ?>  
<div class="list-menu">
<center>
<span class="adult_big">18+</span><br /><br />
<?php if ($adult < 18 && settings('D_R') > 0) : ?>
<font size='+1'><?=lg('Этот файл для взрослых. Вы не можете просмотреть содержимое, так как вы моложе 18')?></font>
<?php else : ?>
<font size='+1'><?=lg('Этот файл для взрослых. Для просмотра подтвердите возраст')?></font>
<?php endif ?>
<br /><br />
<?php if (user('ID') == 0) : ?>
<a href='/login/' class='btn'><?=lg('Войти')?></a>
<a href='/registration/' class='btn'><?=lg('Зарегистрироваться')?></a>
<?php elseif (settings('D_R') == 0) : ?>
<a href='/account/form/?id=<?=user('ID')?>&get=general_info&<?=TOKEN_URL?>' class='btn'><?=lg('Подтвердить возраст')?></a>
<?php endif ?>
</center>
</div>
</div>
<?=back('/m/'.$root.'/')?>
<?=forward('/', 'На главную')?>
<?=acms_footer()?>
<?php endif ?>
<?php endif ?>