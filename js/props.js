

$(document).ready(function(){
  let clientWidth = document.documentElement.clientWidth;
  let clientHeight = document.documentElement.clientHeight;

var window_width = $(window).width() - '5px';

let item_style_default = {"border-right":"1px solid black", "background-color":"transparent", "color":"white"};

$("<div class = 'main_caption'></div>").appendTo('body');
var main_caption = $('div.main_caption');

$("<img class = 'menu_img' src = 'img/pict_online.png' title = 'online'/>").appendTo(main_caption);

$("<span class  = 'topper_menu'></span>").appendTo(main_caption);
var topper_menu = $('span.topper_menu');

main_caption.css('width', window_width);
main_caption.css('margin', '5px');

let i = 0;

for(i=0; i<4; i++){
  var oldHtml = topper_menu.html();
  topper_menu.html(oldHtml + "<span id='fag' number = "+i+" class = 'item_menu'>&nbsp"+name[i]+"&nbsp</span>");
}

var span = $('span#fag');
span.bind('mouseover', function(){
  $(this).css('background-color', '#E5E5E5');
  $(this).css('color' , 'black');

})
span.bind('mouseout', function(){
  $(this).css(item_style_default);
})

$(window).resize(function(){
  main_caption.css('width', window_width);
});

});
