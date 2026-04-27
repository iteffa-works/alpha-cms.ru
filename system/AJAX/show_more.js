function show_more(link, id, id_upgrade, add, type) {

  var count_show = parseInt($(id).attr('count_show'));
  var count_add = $(id).attr('count_add');
  var name_show = $(id).attr('name_show');
  var name_hide = $(id).attr('name_hide');
  var name_finish = $(id).attr('name_finish');
  
  $(id).html(name_show);
  $('.button').attr('style', 'opacity: 0.6');
  
  $.ajax({
    
    url: link,
    type: "post",
    dataType: "json",
    data: {
      "count_show": count_show,
      "count_add": count_add    
    },
    success: function(data){
      
      if (data.result == "success"){
        
        $(id).html(name_finish);
        $(id).attr('count_show', (count_show + parseInt(add)));
        
        if (type == 'append') {
          
          $(id_upgrade).append(data.html);
          
        }else{
          
          $(id_upgrade).prepend(data.html);
          
        }
        
        $('.button').attr('style', 'opacity: 100000');
      
      }else{
        
        $(id).html(name_hide);
        $('.button').attr('style', 'opacity: 0.6');
      
      }
    
    }
  
  });
  
}