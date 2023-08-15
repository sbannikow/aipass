$(document).ready(function(){
  var thick = true;
  var menu_drop = false;
  var status = false;
  var repDate ="";
  var cHeight = document.documentElement.clientHeight;
  $("div.topper_left").html("Логин:<br>Имя:<br>Фамилия:");
  $("div.topper_right").html(user_login + "<br>" + user_name + "<br>" + user_lName);
  $("div.button_menu").append("<div class='button_menu_tri'></div>");
  $("div.topper").css("height", $("div.topper_left").height()+2+"px");
  var bt_tri = $("div.button_menu_tri");
  var menu = $(".button_menu");
  bt_tri.css("left", ($("div.button_menu").width()-bt_tri.width())/2 + "px");
  bt_tri.css("top", ($("div.button_menu").height()-bt_tri.height())/2 + 3+"px");
  var topper = $("div.topper");
  var buttons = $("div.buttons");
  var cap_message = $("div.caption_message");
  buttons.css("margin-top", topper.height() + 8 + "px");
  cap_message.css("margin-top", topper.height()+buttons.height()+15+"px");
  $(".drop_down_menu").css("margin-top", topper.height()+buttons.height()+10+"px");
  $(".main_topper").css("height", topper.height()+buttons.height()+cap_message.height()+20 +"px")
  $(".record_table").css("margin-top", $(".main_topper").height()+5+"px");
  $(".record_table").css("height", cHeight - $(".main_topper").height()-40+"px");
  var button_check = $(".button_check");
  var status_date = $("div.status_date");
  date_clock();
  var bt_exit = $(document.querySelector('.drop_down_menu_item[b_action = "exit"]'));
  var bt_getCurrMonth = $(document.querySelector('.drop_down_menu_item[b_action = "currMonth"]'));
  var bt_getPrevMonth = $(document.querySelector('.drop_down_menu_item[b_action = "prevMonth"]'));
  check_status();
  getCurrMonth();
  bt_exit.bind("click", function(){
    window.location = "logout.php";
  });
  bt_getCurrMonth.bind("click", function(){
    getCurrMonth();
    menu_drop = !menu_drop;
    $("div.drop_down_menu").css("height", "0px");
    $("div.drop_down_menu_item").css("opacity", "0%");
    $("div.drop_down_menu_item").css("visibility", "hidden");
  });
  bt_getPrevMonth.bind("click", function(){
    getPrevMonth();
    menu_drop = !menu_drop;
    $("div.drop_down_menu").css("height", "0px");
    $("div.drop_down_menu_item").css("opacity", "0%");
    $("div.drop_down_menu_item").css("visibility", "hidden");
  });
  var item_count = $(".drop_down_menu").children().length;
  $(document).mousedown(function(e){
    if(menu_drop){
      if(!$(".button_menu").is(e.target) && $(".button_menu").has(e.target).length === 0 && !bt_exit.is(e.target) && !bt_getCurrMonth.is(e.target) && !bt_getPrevMonth.is(e.target)){
        menu_drop = !menu_drop;
        $("div.drop_down_menu").css("height", "0px");
        $("div.drop_down_menu_item").css("opacity", "0%");
        $("div.drop_down_menu_item").css("visibility", "hidden");
      }
    }
  });
  var bt_check = $(".button_check");
  bt_check.bind("click", post_check);
  menu.bind("click", function(){
    if(!menu_drop){
    menu_drop = !menu_drop;
    $("div.drop_down_menu").css("height", item_count * 32 + 2 + "px");
    $("div.drop_down_menu_item").css("visibility", "visible");
    $("div.drop_down_menu_item").css("opacity", "100%");
    }
    else{
      menu_drop = !menu_drop;
      $("div.drop_down_menu").css("height", "0px");
      $("div.drop_down_menu_item").css("opacity", "0%");
      $("div.drop_down_menu_item").css("visibility", "hidden");

    }
  });

  function date_clock(){
    thick = !thick;
    var date = new Date();
    var hours = date.getHours();
    var mins = date.getMinutes();
    var year = date.getFullYear();
    var month = date.getMonth()+1;
    var day = date.getDate();

    if(hours<10)hours="0"+hours;
    if(mins<10)mins="0"+mins;
    if(month<10)month = "0"+month;
    if(day<10)day="0"+day;
    if(thick) var clock = day+"."+month+"."+year+"  "+hours+":"+mins;
    else var clock = day+"."+month+"."+year+"  "+hours+" "+mins;
    status_date.html(clock);
    setTimeout(date_clock, 1000);
  }



  function getRandom(number){
    return Math.round(number * Math.random());
};
 })

 function post_check(){
   var send_date = new Date();
   var request = "putEntry";
   var hours = send_date.getHours();
   var mins = send_date.getMinutes();
   var year = send_date.getFullYear();
   var month = send_date.getMonth();
   var day = send_date.getDate();
   $.post("profile_handler.php", {request, hours, mins, year, month, day}, function(data){
     var message;
     $(data).find('code').each(function(){
       message = $(this).find('message').text();
       user_status = $(this).find('status').text();
       if(message) $(".caption_message").prepend(message+"<br>");
       check_status();

     })
   }, 'xml')
    $(".button_check").unbind("click");
    setTimeout(check_hold, 5000);
    setTimeout(getCurrMonth, 1000);
}

function check_hold(){
  $(".button_check").bind("click", post_check);
}



  function getCurrMonth(){
    var send_date = new Date();
    var request = "getCurrMonth";
    var year = send_date.getFullYear();
    var month = send_date.getMonth();
    $.post("profile_handler.php", {request, year, month}, function(data){
      var message = "";
      var total = "";
      var gen_record = "";
      $(data).find('code').each(function(){
        $(this).find('record').each(function(){
          var r_id = $(this).find('id');
          var arr_date = $(this).find('arr_date').text();
          var arr_time = $(this).find('arr_time').text();
          var dep_date = $(this).find('dep_date').text();
          var dep_time = $(this).find('dep_time').text();
          var amount = $(this).find('amount').text();
          gen_record += "<div data-id = "+r_id+" class = 'record_row'><table class = 'record'>";
          gen_record +="<tr><td class = 'record_arrive'>Прибытие</td><td class = 'record_arrive'>"+arr_date+"</td>";
          gen_record +="<td class = 'record_arrive'>"+arr_time+"</td>";
          gen_record +="<td rowspan='2' class = 'record_amount'>"+amount+"</td></tr>";
          gen_record +="<tr><td class = 'record_depar'>Убытие</td><td class = 'record_depar'>"+dep_date+"</td>";
          gen_record +="<td class = 'record_depar'>"+dep_time+"</td></tr>";
          gen_record += "</table></div>";

      })
        rep_Date = $(this).find('repDate').text();
        total = $(this).find('total').text();
        message = $(this).find('message').text();
        $(".record_table").html(gen_record);
        total = "Итого: " + total;

        $(".record_table").prepend("<div class = 'record_total'>Отчет за&nbsp"+rep_Date+"&nbsp"+total+"</div>");
        $(".record_table").prepend("<div class = 'pdfRef'>Сохранить в PDF</div>");
        $(".pdfRef").bind("click", generatePDF);
        if(message) $(".caption_message").prepend(message +"<br>");
      })
    }, 'xml')
    check_status();
  }

  function getPrevMonth(){
    var send_date = new Date();
    var request = "getPrevMonth";
    var year = send_date.getFullYear();
    var month = send_date.getMonth();
    $.post("profile_handler.php", {request, year, month}, function(data){
      var message = "";
      var total = "";
      var gen_record = "";
      $(data).find('code').each(function(){
        $(this).find('record').each(function(){
          var r_id = $(this).find('id');
          var arr_date = $(this).find('arr_date').text();
          var arr_time = $(this).find('arr_time').text();
          var dep_date = $(this).find('dep_date').text();
          var dep_time = $(this).find('dep_time').text();
          var amount = $(this).find('amount').text();
          gen_record += "<div data-id = "+r_id+" class = 'record_row'><table class = 'record'>";
          gen_record +="<tr><td class = 'record_arrive'>Прибытие</td><td class = 'record_arrive'>"+arr_date+"</td>";
          gen_record +="<td class = 'record_arrive'>"+arr_time+"</td>";
          gen_record +="<td rowspan='2' class = 'record_amount'>"+amount+"</td></tr>";
          gen_record +="<tr><td class = 'record_depar'>Убытие</td><td class = 'record_depar'>"+dep_date+"</td>";
          gen_record +="<td class = 'record_depar'>"+dep_time+"</td></tr>";
          gen_record += "</table></div>";

      })
        rep_Date = $(this).find('repDate').text();
        total = $(this).find('total').text();
        message = $(this).find('message').text();
        $(".record_table").html(gen_record);
        total = "Итого: " + total;
        $(".record_table").prepend("<div class = 'record_total'>Отчет за&nbsp"+rep_Date+"&nbsp"+total+"</div>");
        $(".record_table").prepend("<div class = 'pdfRef'>Сохранить в PDF</div>");
        $(".pdfRef").bind("click", generatePDF);
        if(message) $(".caption_message").prepend(message +"<br>");
      })
    }, 'xml')
  }

  function check_status(){
    if(user_status==0){
      $("div.status_str").html("Отсутствует");
      $("div.button_check").css("background-color", "#32CD32");
      $("div.button_check").html("<p class= 'p_button_check_big'>Отметить прибытие</p><p class= 'p_button_check_small'>нажмите сюда, чтобы поставить отметку</p>");
    }
    else {
      $("div.status_str").html("На работе");
      $("div.button_check").html("<p class= 'p_button_check_big'>Отметить убытие</p><p class= 'p_button_check_small'>нажмите сюда, чтобы поставить отметку</p>");
      $("div.button_check").css("background-color", "#E31E24");
    }
  }
  function generatePDF() {
    $(".pdfRef").remove();
    var element = $(".record_table").html();
    $(".record_table").prepend("<div class = 'pdfRef'>Сохранить в PDF</div>");
    $(".pdfRef").bind("click", generatePDF);
    element +="<br><br><div class = 'pdfRef'>"+user_name+" "+user_lName+"</div>";
    var opt = {
      margin:       5,
      filename:     user_name+user_lName+rep_Date+'.pdf',
      image:        { type: 'jpeg', quality: 1.0 },
      html2canvas:  { scale: 2 },
      jsPDF:        { unit: 'mm', format: 'letter', orientation: 'portrait', putOnlyUsedFonts: true }
    };
    html2pdf(element, opt);
}
