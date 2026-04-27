function checkbox(id) {
  
  if (document.getElementById('chset'+id).checked){
    
    $('.checkbox'+id).removeClass('check-close');
  
  }else{
    
    $('.checkbox'+id).addClass('check-close');
  
  }
  
  var count = $(':checkbox').map(function(i,el){
    
    if ($(el).prop('checked')){
      
      return $(el).val();
    
    }
  
  }).get();
  
  if (count == ''){
    
    $('#get_check').addClass('bclose');
  
  }else{
    
    $('#get_check').removeClass('bclose');    
  
  }

}

$('#get_check').on('click', function(){
  
  var vals = $(':checkbox').map(function(i,el){
    
    if ($(el).prop('checked')){
      
      return $(el).val();
    
    }
  
  }).get();
  
  var type = $('#get_check').attr('type');
  var ptype = $('#get_check').attr('ptype');
  var action = $('#get_check').attr('action');
  var id = $('#get_check').attr('idt');
  
  $.ajax({
    
    url: '/system/AJAX/php/attachments/attachments.php?param='+vals+'&rtype='+type+'&ptype='+ptype+'&id='+id+'&link='+action,
    type: "GET",
    success: function(data){

      $('#upload-attachments-result').html(data);
    
    }
  
  });

});