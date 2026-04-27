/*
--------------------
Модальное окно снизу
--------------------
*/

$(document).on('click', 'a[id=modal_bottom_open_set]', function() {
  
  $('.modal_bottom').fadeIn(300).removeClass('modal_bottom_open').addClass('modal_bottom_active');
  $('.modal_phone').fadeIn(300).removeClass('modal_bottom_close').addClass('modal_bottom_no_active');

});

function modal_bottom_open() {
  
  $('.modal_bottom').fadeIn(300).removeClass('modal_bottom_open').addClass('modal_bottom_active');
  $('.modal_phone').fadeIn(300).removeClass('modal_bottom_close').addClass('modal_bottom_no_active');

}

$(document).on('click', 'div[id=modal_bottom_close_set]', function() {
  
  $('.modal_bottom').fadeOut(300).removeClass('modal_bottom_active');
  $('.modal_phone').fadeOut(300).removeClass('modal_bottom_no_active');

});

function modal_bottom_close() {
  
  $('.modal_bottom').fadeOut(300).removeClass('modal_bottom_active');
  $('.modal_phone').fadeOut(300).removeClass('modal_bottom_no_active');

}

/*
------------------------
Модальное окно по центру
------------------------
*/

$(document).on('click', 'a[id=modal_center_open_set]', function() {
  
  $('.modal_center').fadeIn(300).removeClass('modal_center_open').addClass('modal_center_active');
  $('.modal_phone').fadeIn(300).removeClass('modal_center_close').addClass('modal_center_no_active');

});

function modal_center_open() {
  
  $('.modal_center').fadeIn(300).removeClass('modal_center_open').addClass('modal_center_active');
  $('.modal_phone').fadeIn(300).removeClass('modal_center_close').addClass('modal_center_no_active');

}

$(document).on('click', 'div[id=modal_center_close_set]', function() {
  
  $('.modal_center').fadeOut(300).removeClass('modal_center_active');
  $('.modal_phone').fadeOut(300).removeClass('modal_center_no_active');

});

function modal_center_close() {
  
  $('.modal_center').fadeOut(300).removeClass('modal_center_active');
  $('.modal_phone').fadeOut(300).removeClass('modal_center_no_active');

}

function modal_center(id, type, link_upload, id_upload) {
  
  if (type == 'open'){
    
    $('#'+id).fadeIn(300).removeClass('modal_center_open').addClass('modal_center_active');
    $('#'+id+'2').fadeIn(300).removeClass('modal_center_close').addClass('modal_center_no_active');
    
    if (link_upload != null){
      
      $.ajax({
        
        url: link_upload,
        type: "get",
        cache: false,
        beforeSend: function() {
          
          $('#'+id_upload).html('<br /><br /><br /><br /><font size="+2"><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br /></center></font>');
        
        },
        success: function(html){
          
          $('#'+id_upload).html(html);
        
        }
      
      });
    
    }
  
  }
  
  if (type == 'close'){
    
    $('#'+id).fadeOut(300).removeClass('modal_center_active');
    $('#'+id+'2').fadeOut(300).removeClass('modal_center_no_active');
    
  }

}