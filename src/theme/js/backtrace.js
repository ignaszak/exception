$(document).ready(function () {
    $('.backtrace-content').hide();
    $('.backtrace').click(function(){
        var parent = $(this).parent();
        var content = parent[0].children[1];
        $('.backtrace-content:visible').not(content).hide(200);
        $(content).toggle(200);
    });
});