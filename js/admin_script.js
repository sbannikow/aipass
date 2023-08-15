$(document).ready(function(){
  var thick = true;
  var showStatus = true;
  var currEmpl = 0;
  var cHeight = document.documentElement.clientHeight;
  var user_name="";
  var user_lname="";
  var rep_Date="";
  var uname_attr="";
  var navigation=0;
  $("div.topper_left").html("Логин:<br>Имя:<br>Фамилия:");
  $("div.topper_right").html("-<br>-<br>-");
  $("div.topper").css("height", $("div.topper_left").height()+25+"px");
  $("div.topper").prepend("<center>Данные сотрудника</center>");

  var topper = $("div.topper");
  var cap_admin = $(".caption_admin");
  var cap_message = $("div.caption_message");
  $(".main_topper").css("height", topper.height()+cap_message.height()+20 +"px")
  $(".main_topper").css("margin-top", cap_admin.height()+10+"px");
  cap_message.css("margin-top", topper.height()+10+"px");
  $(".record_table").css("margin-top", cap_admin.height() + $(".main_topper").height()+10+"px");
  $(".record_table").css("height", cHeight - cap_admin.height() - $(".main_topper").height()-25+"px");
  var status_date = $("div.status_date");

  getEmplStatus();
  date_clock();
  $(".admin_exit_canvas").bind("click", function(){
    window.location = "logout.php";
  })




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
})

function getEmplStatus(){
    $("div.topper_right").html("-<br>-<br>-");
    $("div.status_str").html("");
    var rst = "getEstatus";
    $.post("admin_handler.php", {rst}, function(data){

      var message;
      var fill="";
      $(".record_table").html(fill);
      $(data).find('code').each(function(){
        $(this).find('employee').each(function(){
          var eName = $(this).find('e_name').text();
          var eLName = $(this).find('e_lname').text();
          var uName = $(this).find('e_uname').text();
          var eStatus = $(this).find('e_status').text();
          fill = "<div class = 'empl_tag_main' dtuname = '"+uName+"'><div dtuname = '"+uName+"' class = 'empl_tag_sign'></div><div class = 'empl_tag_fill'><div dtuname = '"+uName+"' class = 'empl_tag_fill_content'>"+eLName + "&nbsp"+eName+"<br><div>Отсутствует</div></div></div></div>";
          $(".record_table").append(fill);
          if(eStatus==1) {
            window.setTimeout(function(){
              $(".empl_tag_sign[dtuname='"+uName+"']").css("width", "10%");
            }, 100);
            $(".empl_tag_fill_content[dtuname='"+uName+"']").html(eLName + "&nbsp"+eName+"<br><div>На работе</div>");
          }
          var this_tag = $(document.querySelector(".empl_tag_main[dtuname='"+uName+"']"));
          this_tag.bind("click", function(){uname_attr=this.getAttribute('dtuname');getEmplData();});
        })

        message = $(this).find('message').text();
        $(".caption_message").prepend(message+"<br>");

      })

    }, 'xml')

}

function getEmplData(){
  var send_date = new Date();
  var rst = "getCurrMonth";
  var year = send_date.getFullYear();
  var month = send_date.getMonth();
  $.post("admin_handler.php", {rst, uname_attr, year, month}, function(data){
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
      user_name = $(this).find('name').text();
      user_lname = $(this).find('lname').text();
      var user_login = $(this).find('login').text();
      var user_status = $(this).find('status').text();
      $(".record_table").html(gen_record);
      total = "Итого: " + total;
      if(user_status==1) $("div.status_str").html("На работе");
      else $("div.status_str").html("Отсутствует");
      $(".record_table").prepend("<div class = 'record_total'>Отчет за&nbsp"+rep_Date+"&nbsp"+total+"</div>");
      $(".record_table").prepend("<div class = 'pdfRef'>Сохранить в PDF</div>");
      $(".record_table").prepend("<div dt_id='getPMonth' class='button_admin'><div style = 'position: absolute;width:300px;margin:auto;margin-top: 10px;'>Отчет за предыдущий месяц</div></div>");
      $(".record_table").prepend("<div dt_id='empl_list' class='button_admin'><div style = 'position: absolute;width:300px;margin:auto;margin-top: 10px;'>&lt список сотрудников</div></div>");

      var bt_admin_list = $(document.querySelector(".button_admin[dt_id='empl_list']"));
      var bt_admin_pMonth = $(document.querySelector(".button_admin[dt_id='getPMonth']"));
      bt_admin_list.bind("click", getEmplStatus);
      bt_admin_pMonth.bind("click", getEmplPrevMonth);
      navigation = 0;
      $("div.topper_right").html(user_login+"<br>"+user_name+"<br>"+user_lname);
      $(".pdfRef").bind("click", generatePDF);
      if(message) $(".caption_message").prepend(message +"<br>");

    })
  }, 'xml')
}

function getEmplPrevMonth(){
  var send_date = new Date();
  var rst = "getPrevMonth";
  var year = send_date.getFullYear();
  var month = send_date.getMonth();
  $.post("admin_handler.php", {rst, uname_attr, year, month}, function(data){
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
      user_name = $(this).find('name').text();
      user_lname = $(this).find('lname').text();
      var user_login = $(this).find('login').text();
      var user_status = $(this).find('status').text();
      $(".record_table").html(gen_record);
      total = "Итого: " + total;
      if(user_status==1) $("div.status_str").html("На работе");
      else $("div.status_str").html("Отсутствует");
      $(".record_table").prepend("<div class = 'record_total'>Отчет за&nbsp"+rep_Date+"&nbsp"+total+"</div>");
      $(".record_table").prepend("<div class = 'pdfRef'>Сохранить в PDF</div>");
      $(".record_table").prepend("<div dt_id='getCMonth' class='button_admin'><div style = 'position: absolute;width:300px;margin:auto;margin-top: 10px;'>Отчет за текущий месяц</div></div>");
      $(".record_table").prepend("<div dt_id='empl_list' class='button_admin'><div style = 'position: absolute;width:300px;margin:auto;margin-top: 10px;'>&lt список сотрудников</div></div>");

      var bt_admin_list = $(document.querySelector(".button_admin[dt_id='empl_list']"));
      var bt_admin_cMonth = $(document.querySelector(".button_admin[dt_id='getCMonth']"));
      bt_admin_list.bind("click", getEmplStatus);
      bt_admin_cMonth.bind("click", getEmplData);
      navigation = 1;
      $("div.topper_right").html(user_login+"<br>"+user_name+"<br>"+user_lname);
      $(".pdfRef").bind("click", generatePDF);
      if(message) $(".caption_message").prepend(message +"<br>");

    })
  }, 'xml')
}


function generatePDF() {
  $(".pdfRef").remove();
  $(".button_admin").remove();
  var element = $(".record_table").html();
  $(".record_table").prepend("<div class = 'pdfRef'>Сохранить в PDF</div>");
  $(".pdfRef").bind("click", generatePDF);
  if(navigation==0){
    $(".record_table").prepend("<div dt_id='getPMonth' class='button_admin'><div style = 'position: absolute;width:300px;margin:auto;margin-top: 10px;'>Отчет за предыдущий месяц</div></div>");
    var bt_admin_pMonth = $(document.querySelector(".button_admin[dt_id='getPMonth']"));
    bt_admin_pMonth.bind("click", getEmplPrevMonth);
  }
  if(navigation==1){
    $(".record_table").prepend("<div dt_id='getCMonth' class='button_admin'><div style = 'position: absolute;width:300px;margin:auto;margin-top: 10px;'>Отчет за текущий месяц</div></div>");
    var bt_admin_cMonth = $(document.querySelector(".button_admin[dt_id='getCMonth']"));
    bt_admin_cMonth.bind("click", getEmplData);
  }
  $(".record_table").prepend("<div dt_id='empl_list' class='button_admin'><div style = 'position: absolute;width:300px;margin:auto;margin-top: 10px;'>&lt список сотрудников</div></div>");
  var bt_admin_list = $(document.querySelector(".button_admin[dt_id='empl_list']"));
  bt_admin_list.bind("click", getEmplStatus);

  element +="<br><br><div class = 'pdfRef'>"+user_name+" "+user_lname+"</div>";
  var opt = {
    margin:       5,
    filename:     user_name+user_lname+rep_Date+'.pdf',
    image:        { type: 'jpeg', quality: 1.0 },
    html2canvas:  { scale: 2 },
    jsPDF:        { unit: 'mm', format: 'letter', orientation: 'portrait', putOnlyUsedFonts: true }
  };
  html2pdf(element, opt);
}
