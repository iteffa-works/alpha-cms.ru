function modal_comments(link) {
  
  $('#comments_reply').fadeIn(300).removeClass('modal_comments_open').addClass('modal_comments_active');
  $('#comments_reply2').fadeIn(300).removeClass('modal_comments_close').addClass('modal_comments_no_active');
  
  $.ajax({
    
    url: link,
    type: "get",
    cache: false,
    beforeSend: function() {
      
      $('#mcload').html('<br /><br /><br /><br /><font size="+2"><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br /></center></font>');
    
    },
    success: function(html){
      
      $('#mcload').html(html);
    
    }
  
  });

}

function modal_comments_close(id) {
  
  $('#comments_reply').fadeOut(300).removeClass('modal_comments_active');
  $('#comments_reply2').fadeOut(300).removeClass('modal_comments_no_active');

}

function comments(){
  
  var ajax_li = document.getElementById('ajax_comments');
  var link = $(ajax_li).attr('action');
  var count_add = $(ajax_li).attr('count_add');
  var action = $(ajax_li).attr('actionv');
  var author = $(ajax_li).attr('author');
  var type = $(ajax_li).attr('type');
  var o_id = $(ajax_li).attr('o_id');
  var ajn = $(ajax_li).attr('ajn');
  var blink = 500; 
  
  if (ajax_li){
    
    $.ajax({
      
      url: link,
      type: "POST",
      dataType: "json",
      data: {
        "count_add": count_add,
        "action": action,
        "author": author,
        "type": type,
        "o_id": o_id,
        "ajn": ajn
      },
      success: function(data){
        
        if (data.count_add > 0){

          $(ajax_li).prepend(data.html);
          $('#blink'+data.count_add).fadeOut(blink).fadeIn(blink).fadeOut(blink).fadeIn(blink);
          $(ajax_li).attr('count_add', data.count_add);
          
        }
    
      }
    
    });
  
  }
  
}

function message(){
  
  var ajax_li = document.getElementById('ajax_loaders_interval');
  var link = $(ajax_li).attr('action');
  var count_add = $(ajax_li).attr('count_add');
  var blink = 500; 
  
  if (ajax_li){
    
    $.ajax({
      
      url: link,
      type: "POST",
      dataType: "json",
      data: {
        "count_add": count_add    
      },
      success: function(data){
        
        if (data.count_add > 0){
          
          $('.list2').remove();
          $(ajax_li).append(data.html);
          $('#blink'+data.count_add).fadeOut(blink).fadeIn(blink).fadeOut(blink).fadeIn(blink);
          $(ajax_li).attr('count_add', data.count_add);
          $("#OnBottom").fadeIn("slow");
          
        }
        
        if (data.eye == 0){
          
          $(".mail-message-eye").html('');
          
        }
    
      }
    
    });
  
  }
  
}

$(document).on('click', '.ajax-button-comments', function(e){
  
  var $that = $('.ajax-form-comments');
  var link = $that.attr('action');
  var id_btc = document.getElementById('body-top-comments');
  var id_btcp = parseInt($(id_btc).attr('id_post'));
  var pixel = parseInt($(id_btc).attr('pixel'));
  
  $.post(
    
    link,    
    $that.serialize(),    
    
    function(data) {
      
      if (pixel > 0) {
        
        if (id_btcp == 0) {
          
          $("html,body").scrollTop($('#body-top-comments').offset().top - pixel);
        
        }else{
          
          $("html,body").scrollTop($('#blink'+id_btcp).offset().top - pixel);
        
        }
        
      }else{

        $("html, body").animate({scrollTop: document.body.scrollHeight}, "slow");

      }
      
      var elem = $(data).filter('#content').html();
      $("#content").html(elem);
      $("#OnBottom").fadeOut("slow");
      
      var title = $('title').text();
      history.pushState(link, title, link);
    
    }
  
  );
  
  e.preventDefault();
  
});

function reply(link, id, post){
  
  $(id).load(link+" "+id+"");
  $('#body-top-comments').attr('id_post', post);
  
}