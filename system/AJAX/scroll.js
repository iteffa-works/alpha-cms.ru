$(window).scroll(function() {
  
  if  ($(window).scrollTop() >= $(document).height() - $(window).height()){
    
    $("#OnBottom").fadeOut("slow");
  
  }else{
    
    $("#OnBottom").fadeIn("slow");
    
  }

});

$(window).scroll(function() {
  
  if  ($(window).scrollTop() > 0){
    
    $("#OnTop").fadeIn("slow");
  
  }else{
    
    $("#OnTop").fadeOut("slow");
    
  }

});

$(document).ready(function(){
  
  $(document).on('click', '#OnBottom', function(){
    
    $("html,body").animate({scrollTop:$(document).height()},"slow");
  
  });
  
  $(document).on('click', '.OnBottom', function(){
    
    $("html,body").animate({scrollTop:999999999999999999},"slow");
  
  });

});

$(document).ready(function(){
  
  $(document).on('click', '#OnTop', function(){
    
    $("html,body").animate({scrollTop:0},"slow");
  
  });

});