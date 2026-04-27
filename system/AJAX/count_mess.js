function count_mess() {
  
  $.ajax({
    
    url: "/system/AJAX/php/count_mess.php",
    type: "post",
    dataType: "json",
    success: function(data){
      
      $('#count_notif').html(data.count_notif);
      $('#count_tape').html(data.count_tape);
      $('#count_mail').html(data.count_mail);
    
    }
  
  });
  
}