<?php
  
//Только друзьям
if (MANAGEMENT == 0 && $blog['PRIVATE'] == 1 && $blog['USER_ID'] != user('ID') && db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), $blog['USER_ID']]) == 0){
  
  html::empty('Доступ только для друзей автора записи', 'users');
  back('/m/blogs/', 'Ко всем записям');
  acms_footer();
  
}

//Только автору
if (MANAGEMENT == 0 && $blog['PRIVATE'] == 2 && $blog['USER_ID'] != user('ID')){
  
  html::empty('Доступ только для автора записи', 'lock');
  back('/m/blogs/', 'Ко всем записям');
  acms_footer();
  
}

//По паролю
if (MANAGEMENT == 0 && $blog['PRIVATE'] == 4 && str($blog['PASSWORD']) > 0 && $blog['USER_ID'] != user('ID') && !session('BLOGS_PASSWORD')){
  
  if (post('ok_blog_pass')){
    
    $password = md5(tabs(post('password')));
    
    if ($password != $blog['PASSWORD']){
      
      error('Неверный пароль');
      redirect('/m/blogs/show/?id='.$blog['ID']);
      
    }

    session('BLOGS_PASSWORD', 1);
    redirect('/m/blogs/show/?id='.$blog['ID']);
    
  }
  
  html::empty('Доступ только по паролю', 'key');
  
  ?>    
  <div class='list'>
  <form method='post' class='ajax-form' action='/m/blogs/show/?id=<?=$blog['ID']?>'>
  <?=html::input('password', 'Введите пароль', null, null, null, 'form-control-100', 'password', null, 'lock')?>  
  <?=html::button('button ajax-button', 'ok_blog_pass', null, 'Вперед')?>  
  </form>  
  </div>
  <?
  
  back('/m/blogs/', 'Ко всем записям');
  acms_footer();
  
}