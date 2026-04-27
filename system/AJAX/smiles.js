function smile_save(id) {
  
  $.ajax({
    
    url: '/system/AJAX/php/smiles_save.php?id='+id,
    type: "get"
  
  });
  
}

$(document).on('click', '.bbs', function(e) {
  
  var alt = $(this).attr("alt");    
  var start = ''+alt+'';
  
  smile_insert(alt);
  
  return false;

});

function smile_insert(start) {
  
  element = document.getElementById('count_char');
  
  if (document.selection) {
    
    element.focus();
    sel = document.selection.createRange();
    sel.text = start + sel.text;
  
  } else if (element.selectionStart || element.selectionStart == '0') {
    
    element.focus();
    var startPos = element.selectionStart;
    
    element.value = element.value.substring(0, startPos) + start + element.value.substring(startPos) + element.value.substring(element.value.length);
  
  }else{
    
    element.value += start;
  
  }

}

$(document).on('click', 'a[id=bbs_for]', function() {
  
  $('.bbs_op').stop().animate({
    
    scrollLeft: '+=' + (100 * $(this).data('factor'))
  
  });

}); 

$(document).on('click', 'a[id=bbs_back]', function() {
  
  $('.bbs_op').stop().animate({
    
    scrollLeft: '+=' + (100 * $(this).data('factor'))
  
  });

});

function smiles_up(link){
  
  smiles_up_ajax(link);
  
}

$(document).on('click', 'a[id=smile_up]', function() {
  
  var link = $(this).attr("action");
  smiles_up_ajax(link);

});

function smiles_up_ajax(link){
  
  $.ajax({
    
    url: link,
    type: "get",
    cache: false,
    beforeSend: function() {
      
      $('#smiles_up').html('<br /><br /><br /><br /><font size="+2"><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br /></center></font>');
    
    },
    success: function(html){
      
      $('#smiles_up').html(html);
    
    }
  
  });
  
}