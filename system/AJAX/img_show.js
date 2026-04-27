function img_show(root, link, name){
  
  var phone = document.getElementById('imgsh_phone');
  var obj = document.getElementById('imgsh_obj');
  
  if (phone.style.display == 'none'){
    
    phone.style.display = '';
    $(obj).html('<a href="'+root+'" ajax="no"><img src="'+root+'"></a>');
    $('.img_name').html(name);
    $('.img_name').attr('href', link);
    
  }else{
    
    phone.style.display = 'none';
    $(obj).html('');
    $('.img_name').html('');
    $('.img_name').attr('href', null);
    
  }
  
}