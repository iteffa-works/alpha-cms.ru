<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

if (ajax() == true){
  
  function music_json($artist, $name, $id, $count1, $count2, $img, $key) {
    
    echo json_encode(array(
      
      'artist' => $artist,
      'name' => $name,
      'id' => $id,
      'count1' => $count1,
      'count2' => $count2,
      'imgm' => $img,
      'key' => $key
    
    ));
    
  }
  
  $array = post('array');
  $id_play = intval(post('id'));
  $id_key = intval(post('key'));
  
  $music_list = tabs(trim(preg_replace('/\s+/', '', mb_substr($array, 0, -1))));
  
  $artist = lg('Нет артиста');
  $name = lg('Нет песни');
  $img = '/files/upload/music/no_image.png';
  $play_id = 0;
  
  $exp = explode(',', $music_list);
  $count = count($exp);
  
  if ($id_key < 0) {
    
    $count2 = $count - 1;
    $param = $exp[$count2];
    $key = $count2;
    
  }else{
    
    if (array_key_exists($id_key, $exp)){
      
      $param = $exp[$id_key];
      $key = $id_key;
    
    }else{
      
      $param = $exp[0];
      $key = 0;
    
    }
    
  }
  
  if (str($music_list) > 0) {
    
    $music = db::get_string("SELECT `ID`,`ARTIST`,`NAME` FROM `MUSIC` WHERE `ID` = ? LIMIT 1", [intval($param)]);
    
    if (isset($music['ID'])) {
      
      $artist = tabs(crop_text($music['ARTIST'],0,30));
      $name = tabs(crop_text($music['NAME'],0,35));
      $play_id = $music['ID'];
      
      hooks::challenge('play', 'play');  
      hooks::run('play');
      
      if (config('MUSIC_SCREEN') == 1){
        
        if (is_file(ROOT.'/files/upload/music/screen/240x240/'.$music['ID'].'.jpg')){
          
          $img = '/files/upload/music/screen/240x240/'.$music['ID'].'.jpg';
        
        }else{
          
          $img = '/files/upload/music/no_image.png';
        
        }
      
      }else{
        
        $img = '/files/upload/music/no_image.png';
      
      }
    
    }
    
    music_json($artist, $name, $play_id, $key + 1, $count, $img, $key);
    
  }else{
    
    music_json($artist, $name, $play_id, 0, 0, $img, 0);
    
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}