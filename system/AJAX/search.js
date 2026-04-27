$(document).ready(function(){

  $(this).on("keyup", "input[class=panel-top-search]", function() {
    
    open_or_close('search_close', 'open');
    
    var search_link = $('.panel-top-search').attr('action');
    var form_data = $(this).serialize();
    var search_phone = document.getElementById('search-phone');
    
    $.ajax({
      
      type: 'post',
      url: search_link, 
      data: form_data,
      response: 'text',
      success: function(data){
        
        $(".search_result").html(data).fadeIn(); 
        search_phone.style.display = '';
      
      }
    
    });
  
  });

});