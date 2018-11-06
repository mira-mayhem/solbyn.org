    var itemCount;

    window.addEvent('domready', function(){
        itemCount = $$('a[rel^=obox]').length;
        itemCount--;
        //introduce obox elements
        $(document.body).adopt(
            $$(
                modalDiv = new Element("div").set("id", "obox-modal").set('class', 'obox').addEvent("click", function(){modal(false)}),
                modalImg = new Element("img").set("id", "obox-image").set('class', 'obox').addEvent("click", function(){modal(false)}),
                navigatorDiv = new Element("div").set("id", "obox-navigator").set('class', 'obox'),
            )
        );
        navigatorDiv.adopt(
            descTitle = new Element('span').set("id", "obox-description"),
            );
        modal(false);
        $$('a[rel^=obox]').each(function(el, indx){
            el.addEvent("click", function(e){
                e.preventDefault();
                modal(true);
                loadObox(this, indx);
            })
        });
        $('nextButton').addEvent("click", function(e){
            e.preventDefault();
            loadObox($$('a[rel^=obox]')[nextImageIndex], nextImageIndex)
        });
        $('previousButton').addEvent("click", function(e){
            e.preventDefault();
            loadObox($$('a[rel^=obox]')[previousImageIndex], previousImageIndex)
        });
    });

var loadObox = function(el, imgIndex)
{
    loadText(el);
    loadImage(el);
    setNextAndPreviousButton(imgIndex);
    setTimeout(function() {
        calcPosition()
    }, 50);
}

var nextImageIndex;
var previousImageIndex;
var setNextAndPreviousButton = function(index)
{
    var nextIndex = index + 1;
    var previousIndex = index - 1;
    if (nextIndex > itemCount) {
        nextIndex = 0;
    }
    if (previousIndex < 0) {
        previousIndex = itemCount;
    }
    nextImageIndex = nextIndex;
    previousImageIndex = previousIndex;
}

var loadText = function(el)
{
    if (el.get('title')) {
        $('obox-description').set("text", el.get('title'));
        $('obox-navigator').setStyle("visibility", "visible");
    } else {
        $('obox-navigator').setStyle("visibility", "hidden");
    }
}

var loadImage = function(el)
{
    $("obox-image").set("src", el.get('href'));
};

var modal = function(on)
{
    if (on) {
        $$('.obox').setStyle("display", "block");
    } else {
        $$('.obox').setStyle("display", "none");
    }
};

function calcPosition()
{
    var windowHeight, windowWidth;
    if (document.documentElement.clientHeight) {
        windowWidth = document.documentElement.clientWidth;
        windowHeight = document.documentElement.clientHeight;
    } else if (window.innerHeight) {
        windowWidth = window.innerWidth;
        windowHeight = window.innerHeight;
    }
    $("obox-image").setStyle('height', windowHeight - 20);
    var currentWidth = $("obox-image").offsetWidth;
    var leftPos = (windowWidth - currentWidth) / 2;
    $("obox-image").setStyle('left', leftPos);
    $("obox-navigator").set('styles', {left: leftPos, width: currentWidth});
    $$(".div-navigation").setStyle('top', (windowHeight-45) / 2)
};
