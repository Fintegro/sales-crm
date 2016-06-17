;
(function($) {
    $.StartScreen = function(){
        var plugin = this;
        var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;

        plugin.init = function(){
            setTilesAreaSize();
            if (width > 640) addMouseWheel();
        };

        var setTilesAreaSize = function(){
            var groups = $(".tile-group");
            var tileAreaWidth = 80;
            $.each(groups, function(i, t){
                if (width <= 640) {
                    tileAreaWidth = width;
                } else {
                    tileAreaWidth += $(t).outerWidth() + 80;
                }
            });
            $(".tile-area").css({
                width: tileAreaWidth
            });
        };

        var addMouseWheel = function (){
            $("body").mousewheel(function(event, delta, deltaX, deltaY){
                var page = $(document);
                var scroll_value = delta * 50;
                page.scrollLeft(page.scrollLeft() - scroll_value);
                return false;
            });
        };

        plugin.init();
    }
})(jQuery);

$(function(){
    $.StartScreen();

    var tiles = $(".tile, .tile-small, .tile-sqaure, .tile-wide, .tile-large, .tile-big, .tile-super");

    $.each(tiles, function(){
        var tile = $(this);
        setTimeout(function(){
            tile.css({
                opacity: 1,
                "-webkit-transform": "scale(1)",
                "transform": "scale(1)",
                "-webkit-transition": ".3s",
                "transition": ".3s"
            });
        }, Math.floor(Math.random()*500));
    });

    $(".tile-group").animate({
        left: 0
    });
});
function showSearch(){
    var  charm = $("#charmSearch");

    if (charm.data('hidden') == undefined) {charm.data('hidden', true);}

    if (!charm.data('hidden')) {

        charm.animate({
            right: -300
        });

        charm.data('hidden', true);
    } else {
        charm.animate({
            right: 0
        });
        charm.data('hidden', false);
    }
}

function showSettings(){
    var  charm = $("#charmSettings");

    if (charm.data('hidden') == undefined) {charm.data('hidden', true);}

    if (!charm.data('hidden')) {

        charm.animate({
            right: -300
        });

        charm.data('hidden', true);
    } else {
        charm.animate({
            right: 0
        });
        charm.data('hidden', false);
    }
}

function setSearchPlace(el){
    var a = $(el);
    var text = a.text();
    var toggle = a.parents('label').children('.dropdown-toggle');

    toggle.text(text);
}

$(function(){
    var current_tile_area_scheme = localStorage.getItem('tile-area-scheme') || "tile-area-scheme-dark";
    $(".tile-area").removeClass (function (index, css) {
        return (css.match (/(^|\s)tile-area-scheme-\S+/g) || []).join(' ');
    }).addClass(current_tile_area_scheme);

    $(".schemeButtons .button").hover(
        function(){
            var b = $(this);
            var scheme = "tile-area-scheme-" +  b.data('scheme');
            $(".tile-area").removeClass (function (index, css) {
                return (css.match (/(^|\s)tile-area-scheme-\S+/g) || []).join(' ');
            }).addClass(scheme);
        },
        function(){
            $(".tile-area").removeClass (function (index, css) {
                return (css.match (/(^|\s)tile-area-scheme-\S+/g) || []).join(' ');
            }).addClass(current_tile_area_scheme);
        }
    );

    $(".schemeButtons .button").on("click", function(){
        var b = $(this);
        var scheme = "tile-area-scheme-" +  b.data('scheme');

        $(".tile-area").removeClass (function (index, css) {
            return (css.match (/(^|\s)tile-area-scheme-\S+/g) || []).join(' ');
        }).addClass(scheme);

        current_tile_area_scheme = scheme;
        localStorage.setItem('tile-area-scheme', scheme);

        showSettings();
    });
});