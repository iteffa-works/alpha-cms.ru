/*
------------------------
AJAX переходы по ссылкам
------------------------
*/

$(document).on('click', 'a[ajax != "no"]', function(e){
  
  var link = $(this).attr('href');
  
  if (link != null){
    
    go_url(link);
    
    var title_ajax = document.title;
    history.pushState(link, title_ajax, link);
    
    e.preventDefault();
    
  }

});

if (history.pushState){
  
  $(window).on('popstate', function(event){
    
    var location = event.location || ( event.originalEvent && event.originalEvent.location ) || document.location;
    go_url(location.href);
  
  });

}

function go_url(href){
  
  var ajax_load = href+' #content';
  var ajax_indication = $('.ajax_indication').attr('data');
  
  $('#ajax_load').fadeOut(400);
  $('.ajax_indication').html('<i class="fa fa-spinner fa-spin"></i><span>'+ajax_indication+'</span>').fadeIn(400);
  
  setTimeout(function() {
    
    $("#content").load(ajax_load, function(data, status) {
      
      if (status == "success"){
        
        if ($(".scroll").hasClass("bottom")){
          
          document.body.scrollTop = document.body.scrollHeight;
          document.documentElement.scrollTop = document.body.scrollHeight;
          
        }else{
          
          document.body.scrollTop = 0;
          document.documentElement.scrollTop = 0;
          
        }
        
        js_hooks();
      
      }
    
    });
  
  }, 130);

}

/*
------------------
AJAX загрузка форм
------------------
*/

function ajax_post(o) {
  
  var $that = $('.ajax-form'+o);
  var link = $that.attr('action');
  
  $.post(
    
    link,    
    $that.serialize(),    
    
    function(data) {

      var elem = $(data).filter('#content').html();
      $("#content").html(elem);
      
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
      var title_ajax = document.title;
      history.pushState(link, title_ajax, link);
    
    }
  
  );
  
}

$(document).on('click', '.ajax-button', function(e){
  
  var bset = $(this);
  var o = bset.attr('o');
  
  ajax_post(o);  
  e.preventDefault();
  
});

$(document).on('click', '.ajax-button-search', function(e){
  
  $(this).html('<i class="fa fa-spinner fa-spin fa-fw" style="font-size: 20px"></i>');
  
  ajax_post(2);  
  e.preventDefault();
  
});

$(document).on('click', '.ajax-button-search-web', function(e){
  
  $(this).html('<i class="fa fa-spinner fa-spin fa-fw" style="font-size: 20px"></i>');
  
  ajax_post(999);  
  e.preventDefault();
  
});