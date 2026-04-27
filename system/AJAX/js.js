/*
----------------------
Показать/скрыть пароль
----------------------
*/

function pass_eye(name) {

  var input_pass = 'input[id='+name+']';

  if ($(input_pass).attr('type') == 'password'){
    
    $(input_pass).attr('type', 'text');
    $('.'+name).html('<i class="fa fa-eye" style="font-size: 17px; vertical-align: middle"></i>');
  
  }else{
    
    $(input_pass).attr('type', 'password');
    $('.'+name).html('<i class="fa fa-eye-slash" style="font-size: 17px; vertical-align: middle"></i>');
  
  }

}

/*
------------------------
Эффект ожидания загрузки
------------------------
*/

function load_p(title, id){
  
  $(id).html('<i class="fa fa-spinner fa-spin fa-fw"></i> '+title);
  $(id).attr('style', 'opacity: 0.6');
  
}

$(document).on('click', '.comments-button', function(){
  
  $('.comments-button').html('<i class="fa fa-spinner fa-spin fa-fw"></i>');
  $('.comments-button').attr('style', 'opacity: 0.6');

});

/*
---------------------
Растягивание textarea
---------------------
*/

function messages_prints(){
  
  var link_id_mess = $('.messages_prints').attr('action');
  $.ajax({url: link_id_mess, type: "get"});
  
}

$(document).on('keyup paste', '.count_char', function(){
  
  var $el = $(this), offset = $el.innerHeight() - $el.height();
  
  if ($el.innerHeight < this.scrollHeight) {
    
    $el.height(this.scrollHeight - offset);
  
  }else{
    
    if (this.scrollHeight <= 200) {
      
      $el.height(1);
      $el.height(this.scrollHeight - offset);
    
    }
  
  }
  
});

/*
--------------------------
Загрузить контент по клику
--------------------------
*/

var load = '<br /><br /><br /><br /><font size="+2"><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br /></center></font>';
function upload(link, id, load_ind){
  
  $.ajax({
    
    url: link,
    type: "get",
    cache: false,
    beforeSend: function() {
      
      if (load_ind != '1'){
        
        $('#'+id).html(load);
        
      }
    
    },
    success: function(html){
      
      $('#'+id).html(html);
    
    }
  
  });
  
}

/*
-----------------------
Открыть/закрыть элемент
-----------------------
*/

function open_or_close(element, prioritet){
  
  var id_element = document.getElementById(element);
  
  if (prioritet == 'close'){

    id_element.style.display = 'none';
    
  }
  
  if (prioritet == 'open'){
    
    id_element.style.display = '';
    
  }
  
  if (prioritet != 'close' && prioritet != 'open'){
    
    if (id_element.style.display == ''){

      id_element.style.display = 'none';
      $('#'+element+'-c').html('<i class="fa fa-angle-down" style="font-size: 20px; vertical-align: middle;"></i>');      
      
    }else{

      id_element.style.display = '';
      $('#'+element+'-c').html('<i class="fa fa-angle-up" style="font-size: 20px; vertical-align: middle;"></i>');
      
    }
    
  }
  
}

/*
-----------------------------
Отправка запроса и обновление
-----------------------------
*/

function request(link, id) {
  
  $(id).load(link+" "+id+"");
  
}

/*
-------------------------------------------
Подсчет количества символов в поле textarea
-------------------------------------------
*/

function countLetters(num = 0) {
  
  var textarea = document.getElementById("count_char");
  var count = document.getElementById("countLetters");
  var textlength = textarea.value.length;
  count.innerText = textlength + num;

}

/*
------------------------------------
Скрыть системное сообщение в модулях
------------------------------------
*/

function info_message(type) {
  
  var type_i = document.getElementById(type);
  
  type_i.style.display = 'none';
  document.cookie = type+"=1;path=/";

}