$(function() {
    if ($.cookie("phTheme") != 0) {
        $.get("theme/universe/html.htm", function( content ) {
		    $("body").append(content);
		}, 'html');
        
        $("body").append('<link href="theme/universe/style.css" rel="stylesheet" type="text/css">');
        $("body").append('<span class="themeOnOff">Cosmology? Nah!</span>');
    } else {
        $("body").append('<span class="themeOnOff">Show me the way, milky-milky way!</span>');
    }
});

var runCode = function(){
    
}