$(document).on('click', 'div[id=sidebar_show]', function() {
  
  $('.sidebar_wrap').fadeIn(300).removeClass('sidebar_wrap_hidden').addClass('wrap_active');
  $('.sidebar_hide').fadeIn(300).removeClass('sidebar_hide_hidden').addClass('hide_active');

});

$(document).on('click', 'div[id=sidebar_hide]', function() {
  
  $('.sidebar_wrap').fadeOut(300).removeClass('wrap_active');
  $('.sidebar_hide').fadeOut(300).removeClass('hide_active');

});