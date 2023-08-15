$(document).ready(function(){
    $("form#main_form").submit(function(event){
    event.preventDefault();
    var form_values = $(this).serialize();
    $.post("indexhandler.php", form_values, function(data){
      var message;
      var redirect;
      $(data).find('code').each(function(){
        dest = $(this).find('post').text();
        message = $(this).find('message').text();
        redirect = $(this).find('link').text();
      })
      $("div.id_message").html(message);
      if(redirect) {
        location.replace(redirect);
      }
      }, 'xml');
  });


});
