$(document).ready(function(){
$(".add_row").on('click',function(){

var rowpos = $('#preview-area>.stickytable-container>.stickytable-wrap>.stickytable-enabled>tbody>tr:last').position();

//$('.stickytable-wrap').scroll(rowpos.top);
//alert(rowpos.top);
$('.stickytable-wrap').animate({
    scrollTop:  rowpos.top
});

});
});