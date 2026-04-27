function dialog_modal(type) {
  
  var dialog_modal = document.getElementById('dialog_modal');
  var dialog_close = document.getElementById('dialog_close');
  
  if (type == 'open'){
    
    dialog_modal.style.display = '';
    dialog_close.style.display = '';
    
    $.ajax({
      
      url: '/system/AJAX/php/messages/modal_web.php',
      type: "get",
      cache: false,
      beforeSend: function() {
        
        $('#dialog_modal').html('<br /><br /><br /><br /><br /><font size="+2"><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br /></center></font>');
      
      },
      success: function(html){
        
        $('#dialog_modal').html(html);
      
      }
    
    });
  
  }else{
    
    dialog_modal.style.display = 'none';
    dialog_close.style.display = 'none';
    
  }

}