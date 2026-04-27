<?php
  
/*
-------------------------------------
Подключение ключевых конфигурационных
файлов системы
-------------------------------------
*/

require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/global/config.php');
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/global/connect.php');
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/global/users.php');

/*
------------------------------------------
Функции размещения шапки и подвала страниц
------------------------------------------
*/

function acms_header($title = null, $access = null, $description = null, $keywords = null, $logo = null) {
  
  //$title - заголовок текущей страницы
  //$access - кому доступна страница
  //$description - описание текущей страницы
  //$keywords - ключевые слова на странице
  //$logo - ссылка на логотип страницы
  
  $title_data = null;
  $description_data = null;
  
  $metadata = array(
    
    'rating' => 'ТОП',
    'new' => 'Новые',
    'guests' => 'Гости',
    'online' => 'Онлайн',
    'photos' => 'Фото',
    'videos' => 'Видео',
    'files' => 'Файлы',
    'blogs' => 'Блоги',
    'forum' => 'Темы форума',
    'games' => 'Онлайн игры',
    'music' => 'Музыка',
    'users' => 'Пользователи',
    'downloads' => 'Загрузки',
    'communities' => 'Сообщества'
  
  );
  
  $metadata_get = get('get');
  $metadata_type = get('type');
  
  if (isset($metadata[$metadata_get])) {
    
    $title_data .= ' | '.lg($metadata[$metadata_get]);
  
  }
  
  if (isset($metadata[$metadata_type])) {
    
    $title_data .= ' | '.lg($metadata[$metadata_type]); 
  
  }
  
  $metadata_path = get('path');
  
  $metapath = array(

    'photos' => 'Загружайте фото, просматривайте фото пользователей, найдите понравившихся людей по фото на нашем сайте',
    'videos' => 'Загружайте видео, просматривайте интерсеные видео на нашем сайте',
    'files' => 'Загружайте файлы и храните их на нашем сайте',
    'blogs' => 'Напишите в блог и удивите пользователей, читайте интересные статьи на нашем сайте',
    'forum' => 'Напишите пост на нашем форуме. Здесь Вам помогут. Интересные и полезные посты ежедневно только на нашем сайте',
    'games' => 'Играйте в онлайн игры на нашем сайте',
    'music' => 'Слушайте музыку онлайн, скачивайте музыку или загружайте на нашем сайте',
    'music_services' => 'Онлайн музыка со скачиванием на нашем сайте',
    'clips' => 'Смотрите интересные клипы на нашем сайте',
    'stories' => 'Смотрите интересные истории на нашем сайте',
    'ask' => 'Задавайте вопросы пользователям на нашем сайте',
    'lottery' => 'Играйте в лотерею на нашем сайте',
    'users' => 'Пользователи нашего сайта',
    'downloads' => 'Загрузки на любой вкус только на нашем сайте',
    'photobattle' => 'Примите участие или голосуйте в фотобаттлах на нашем сайте',
    'movies' => 'Топовые онлайн фильмы, мультфильмы и сериалы только на нашем сайте',
    'communities' => 'Найдите интересные для вас сообщества по своим увлечениям на нашем сайте',
    'dating' => 'Найдите свою вторую половинку, знакомьтесь и общайтесь на нашем сайте',
    'shop' => 'На нашем магазине вы найдете всё что вас интересует по низким ценам. Только на нашем сайте',
    'adlist' => 'Подайте объявление или находите полезное только на нашем сайте'
  
  );
  
  if (isset($metapath[$metadata_path])) {
    
    $description_data .= lg($metapath[$metadata_path]).' '.HTTP_HOST;
  
  }
  
  if (get('page') != '0') { 
    
    $title_data .= ' | '.(get('page') == 'end' ? ' '.lg('последняя страница') : abs(intval(get('page'))).' '.lg('стр.')); 
    $description_data .= ' | '.(get('page') == 'end' ? lg('последняя страница') : abs(intval(get('page'))).' '.lg('стр.'));
  
  }
  
  if (str($title) > 0) { 
    
    html::title($title);
  
  }else{ 
    
    if (str($title_data) > 0) { 
      
      html::title(config('TITLE').$title_data);
    
    }
  
  }
  
  if (str($description) > 0) { 
    
    html::description($description);
  
  }else{ 
    
    if (str($description_data) > 0) { 
      
      html::description($description_data);
    
    }
  
  }
  
  if (str($keywords) > 0) { 
    
    html::keywords($keywords);
  
  }
  
  require (ROOT.'/system/connections/header.php');
  
  if (str($access) > 0) { 
    
    access($access); 
  
  }
  
}

function acms_footer($exit = 0){
  
  require (ROOT.'/system/connections/footer.php');
  
  if ($exit == 0) { 
    
    exit; 
  
  }
  
}