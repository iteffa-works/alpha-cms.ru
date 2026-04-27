/*
--------------------------------------
AlphaPlayer 1.0.0
Автор: Пченашев Анзаур
Официальный сайт: https://alpha-cms.ru
--------------------------------------
*/

/*
-----------------------------------------
Отправка команды на воспроизведение трека
-----------------------------------------
*/

function PlayGo(play_id, id_post, id_key, type, array_click) {
  
  var img_element = document.getElementById('player-img');
  var player_data2 = document.getElementById('player-data');
  var player_music_download2 = document.getElementById('player-music-download');
  var player_music_comments2 = document.getElementById('player-music-comments');
  var player_data = $(player_data2);
  var player_music_download = $(player_music_download2);
  var player_music_comments = $(player_music_comments2);
  
  if (array_click == 'none') {
    
    var array = $('.music_post'+id_post).attr('array');
    
  }else{

    var array = array_click;
    
  }
  
  if (type == 'none') {
    
    var key = parseInt(id_key);
  
  }
  
  if (type == 'forward') {
    
    var key = parseInt(id_key) + 1;
  
  }
  
  if (type == 'back') {
    
    var key = parseInt(id_key) - 1;
  
  }
  
  player_data.attr('array', array);
  
  $.ajax({
    
    url: "/system/AJAX/AlphaPlayer/audio/receiver.php",
    type: "post",
    dataType: "json",
    data: {
      "array": array,
      "id": play_id,
      "key": key
    },
    success: function(data) {
      
      $('#play_mini_artist').html(data.artist);
      $('#play_mini_name').html(data.name);
      
      $('#play_artist').html(data.artist);
      $('#play_name').html(data.name);
      $('#play_count1').html(data.count1);
      $('#play_count2').html(data.count2);
      img_element.src = data.imgm;
      player_data.attr('id_key', data.key);
      player_data.attr('play_id', data.id);
      player_music_download.attr('href', '/music/'+data.id+'/');
      player_music_comments.attr('href', '/m/music/show/?id='+data.id);
      
      MusicPlay(data.id, id_post, data.key, array);
    
    }
  
  });
  
}

/*
------------------
Проигрывание трека
------------------
*/

var tp = 0;
var ky = 0;

function MusicPlay(id, id_post, id_key, array) {
  
  play_eq_off();
  
  if (parseInt(id) > 0) {
    
    //Данные из тега <audio>
    var player = document.getElementsByTagName('audio')[0];
    
    //Текущий трек 
    player.src = "/files/upload/music/source/"+id+".mp3";
    //player.src = "/music/"+id+"/?get=show";
    
    //Ловим контейнер текущего трека
    var player_id = document.getElementById('music'+id);
    
    if (tp == id) {

      player.pause();
      tp = 0;
    
    }else{

      player.load();  
      player.play();
      tp = id;
    
    }
    
    //Кастомный прогресс бар с перемоткой
    var progressBar = document.getElementById('player-play');
    
    progressBar.addEventListener('click', function(event){
      
      let widthLeft = $(progressBar).offset().left;
      let x = event.pageX - widthLeft;
      let xPersent =  x / this.offsetWidth * 100;
      player.currentTime = player.duration * (xPersent / 100);
      var curtime = player.duration * (xPersent / 100);
      player.play();
    
    });
    
    var bar = $('.player-bar');    
    //var barWidth = bar.parent().width(); 
    var barWidth = bar.attr('width');
    var barChange = false;
    var perSecond = 0;
    var perPixel = 0;
    
    function changeBar() {
      
      bar.width(player.currentTime * perSecond);
      $('#timer').html(duration_format(player.currentTime));
    
    }
    
    //Получение метаданных
    player.addEventListener('loadedmetadata', function(){ 
      
      perSecond = barWidth / player.duration; 
      perPixel = player.duration / barWidth;
      
      //Длительность трека
      var dt = duration_format(player.duration);      
      
      //Кнопка play
      $('#play').click(function() {
        
        player.play();
      
      });
      
      //Кнопка pause
      $('#pause').click(function() {
        
        player.pause();
      
      });
      
      //Кнопка pause2
      $('#pause2').click(function() {
        
        player.pause();
      
      });
      
      //Кнопка pause3
      $('#pause3').click(function() {
        
        player.pause();
      
      });
      
      //Кнопка play3
      $('#play3').click(function() {
        
        player.play();
      
      });
      
      //Включить звук
      $('#volumep').click(function() {
        
        var v = player.volume + 1;
        player.volume = v >= 1 ? 1 : v;
      
      });
      
      //Выключить звук
      $('#volumem').click(function() {
        
        var v = player.volume - 1;
        player.volume = v <= 0 ? 0 : v;
      
      });
      
      $('.duration').html(dt);
    
    });
    
    //Начало воспроизведения
    player.addEventListener('playing', function(){
      
      play_eq_off(); 
      mini_player_show();
      barChange = setInterval(changeBar, 500); 
      ky = id;
      
      //Навешиваем эквалайзер на текущий трек
      $(player_id).html('<img src="/system/AJAX/AlphaPlayer/icons/eq.gif">');
    
    });
    
    //Пауза
    player.addEventListener('pause', function(){ 
      
      play_eq_off();
      clearInterval(barChange); 
      barChange = false; 
      changeBar();
      tp = 0;
    
    });
    
    //Конец воспроизведения
    player.addEventListener('ended', function(){ 
      
      play_eq_off();
      
      if (ky == id){
        
        PlayGo(id, id_post, id_key, 'forward', array);
        ky = 0;
        
      }
    
    });
    
    return false;
  
  }
  
}

/*
------------------
Сброс проигрывания
------------------
*/

function play_eq_off() {
  
  //Сбрасываем эквалайзер
  $(".music-play").html('<i class="fa fa-play fa-lg"></i>');
  
}

/*
---------------------------------------
Таймер оставшегося времени до окончания 
текущего трека
---------------------------------------
*/

function duration_format(time){   
  
  var hrs = ~~(time / 3600);
  var mins = ~~((time % 3600) / 60);
  var secs = ~~time % 60;
  var ret = "";
  
  if (hrs > 0) {
    
    ret += "" + hrs + ":" + (mins < 10 ? "0" : "");
  
  }
  
  ret += "" + mins + ":" + (secs < 10 ? "0" : "");
  ret += "" + secs;
  
  return ret;

}

/*
--------------------------------
Отключение/включение мини-плеера
--------------------------------
*/

//Включение
function mini_player_show() {
  
  var mini1 = document.getElementById('player-mini');
  var mini2 = document.getElementById('player-mini2');
  
  mini1.style.display = "";
  mini2.style.display = "";
  
}

//Отключение
function mini_player_hide() {
  
  var mini1 = document.getElementById('player-mini');
  var mini2 = document.getElementById('player-mini2');
  
  mini1.style.display = "none";
  mini2.style.display = "none";
  
}

/*
----------------------------
Стоп/пуск для трека в плеере
----------------------------
*/

function PlayPause(type) {
  
  var play = document.getElementById('play');
  var pause = document.getElementById('pause');
  var play3 = document.getElementById('play3');
  var pause3 = document.getElementById('pause3');
  
  if (type == "pause"){
    
    pause.style.display = "none";
    play.style.display = "";
    pause3.style.display = "none";
    play3.style.display = ""; 
    
  }
  
  if (type == "play"){
    
    pause.style.display = "";
    play.style.display = "none";
    pause3.style.display = "";
    play3.style.display = "none";
    
  }
  
}

/*
-------------------------------------
Отключение/включение основного плеера
-------------------------------------
*/

function player() {
  
  var player = document.getElementById('player_с');
  var player_phone = document.getElementById('player_phone');
  
  if (player.style.display == "none"){ 
    
    player.style.display = "";
    player_phone.style.display = "";
  
  }else{
    
    player.style.display = "none";
    player_phone.style.display = "none";
  
  }
  
}
  
/*
----------------------------------
Переход к следующей песне по клику
----------------------------------
*/

function player_forward() {
  
  var player_data2 = document.getElementById('player-data');
  var player_data = $(player_data2);  
  var id_post = player_data.attr('id_post');
  var id_key = player_data.attr('id_key');
  var array = player_data.attr('array');
  var play_id = player_data.attr('play_id');
  
  PlayGo(play_id, id_post, id_key, 'forward', array);
  
}

/*
-----------------------------------
Переход к предыдущей песне по клику
-----------------------------------
*/

function player_backward() {
  
  var player_data2 = document.getElementById('player-data');
  var player_data = $(player_data2);  
  var id_post = player_data.attr('id_post');
  var id_key = player_data.attr('id_key');
  var array = player_data.attr('array');
  var play_id = player_data.attr('play_id');
  
  PlayGo(play_id, id_post, id_key, 'back', array);
  
}

/*
-----------------------
Включить/выключить звук
-----------------------
*/

function volume() {
  
  var plus = document.getElementById('volumep');
  var minus = document.getElementById('volumem');

  if (minus.style.display == ""){
    
    minus.style.display = "none";
    plus.style.display = ""; 
    
  }else{
    
    minus.style.display = "";
    plus.style.display = "none"; 
    
  }
  
}