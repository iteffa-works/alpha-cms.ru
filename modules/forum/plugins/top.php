<?php
  
if (user('ID') == $them['USER_ID']) {

  if (get('get') == 'top'){
  
    get_check_valid();
    
    if (db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE `TOP` > ? AND `ID` = ? LIMIT 1", [TM, $them['ID']]) > 0) {
      
      error('Тема уже размещена в ТОПе');
      redirect('/m/forum/show/?id='.$them['ID']);
      
    }
    
    if (db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE `TOP` > ? LIMIT 4", [TM]) >= 3) {
      
      ?>
      <div class='list'>
      <?=lg('К сожалению, на данный момент все места в ТОПе заняты. Ближайшее время освобождения места')?>: <b><?=ftime(db::get_column("SELECT `TOP` FROM `FORUM_THEM` WHERE `TOP` > ? ORDER BY `TOP` ASC", [TM]))?></b>
      </div>
      <?
      
    }else{
      
      if (post('ok')){
        
        valid::create(array(
          
          'FT_DAY' => ['top', 'number', [1, 9999999999999999999], 'Срок', 0]
        
        ));
        
        if (ERROR_LOG == 1){
          
          redirect('/m/forum/show/?id='.$them['ID'].'&get=top&'.TOKEN_URL);
        
        }
        
        $top = TM + (FT_DAY * 86400);
        $money = FT_DAY * config('FORUM_TOP_SUM');
        
        if (user('MONEY') < $money) {
          
          error('Недостаточно денег на вашем счету');
          redirect('/m/forum/show/?id='.$them['ID'].'&get=top&'.TOKEN_URL);
        
        }
        
        db::get_set("UPDATE `FORUM_THEM` SET `TOP` = ? WHERE `USER_ID` = ? AND `ID` = ? LIMIT 1", [$top, user('ID'), $them['ID']]);
        db::get_set("UPDATE `USERS` SET `MONEY` = `MONEY` - ? WHERE `ID` = ? LIMIT 1", [$money, user('ID')]);
        
        success('Тема успешно размещена в ТОП');
        redirect('/m/forum/show/?id='.$them['ID']);
        
      }
      
      ?>
      <div class='list'>
      <?=lg('Тема будет поднята в ТОП на главной странице на выбранный срок')?><br /><br />
      <?=lg('Стоимость 1 дня')?>: <b><?=money(config('FORUM_TOP_SUM'), 2)?></b><br /><br />
      <form method='post' class='ajax-form' action='/m/forum/show/?id=<?=$them['ID']?>&get=top&<?=TOKEN_URL?>'>
      <?=html::input('top', 'Укажите кол-во дней', null, null, null, 'form-control-50', 'number', null, 'clock-o')?>
      <?=html::button('button ajax-button', 'ok', 'plus', 'Разместить в ТОП')?>
      <a href='/m/forum/show/?id=<?=$them['ID']?>' class='button-o'><?=lg('Отмена')?></a>
      </form>
      </div>
      <?
        
    }
      
    back('/m/forum/show/?id='.$them['ID']);
    acms_footer();
  
  }
  
  if (get('get') == 'act_ok'){
    
    get_check_valid();
  
    if (user('MONEY') < config('FORUM_ACT_SUM')) {
    
      error('Недостаточно денег на вашем счету');
      redirect('/m/forum/show/?id='.$them['ID'].'&get=act&'.TOKEN_URL);
    
    }
  
    db::get_set("UPDATE `FORUM_THEM` SET `ACT_TIME` = ? WHERE `ID` = ? LIMIT 1", [TM, $them['ID']]);
    db::get_set("UPDATE `USERS` SET `MONEY` = `MONEY` - ? WHERE `ID` = ? LIMIT 1", [config('FORUM_ACT_SUM'), user('ID')]);
  
    success('Тема успешно поднята');
    redirect('/m/forum/show/?id='.$them['ID']);

  }

  if (get('get') == 'act'){
  
    get_check_valid();
  
    ?>
    <div class='list'>
    <?=lg('Тема будет поднята в списке актуальных на главной странице и будет держаться там до тех пор, пока её не перебьют другие темы')?><br /><br />
    <?=lg('Стоимость 1 подъема')?>: <b><?=money(config('FORUM_ACT_SUM'), 2)?></b><br /><br />
    <a href='/m/forum/show/?id=<?=$them['ID']?>&get=act_ok&<?=TOKEN_URL?>' class='button'><?=icons('arrow-up', 17, 'fa-fw')?> <?=lg('Поднять тему')?></a>
    <a href='/m/forum/show/?id=<?=$them['ID']?>' class='button-o'><?=lg('Отмена')?></a>
    </div>
    <?
  
  }
  
}