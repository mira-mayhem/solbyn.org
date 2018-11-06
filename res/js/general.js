var wait = function(on) {
    var cursorStyle = '';
    if (on) {
        cursorStyle = 'wait';
    }
    $(document.body).set("styles", {cursor: cursorStyle});
}
