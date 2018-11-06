window.addEvent('domready', function() {
    var editorModeEnabledElement = new Element("button");
    editorModeEnabledElement
        .set("text", "Gå ur redigeraläget!")
        .set("id", "btn_exiteditor")
        .set("styles", {
            position: "fixed",
            top: "10px",
            left: "10px",
        })
        .addEvent("click", function(e){
            get.send();
        })
        .inject($(document.body));
    if (!$$(".article").length) {
        return false
    }
    $(document.body).getElement(".article").addEvent("click", function(e){
        var toolboxForm = new Element('form');
        toolboxForm
            .set("class", "editor")
            .set("send", post);
        var position = this.getBoundingClientRect();
        var textarea = new Element("textarea");
        textarea.set("styles", {
            width: position.width,
            height: position.height,
            top: position.top,
            left: position.left,
        });
        textarea
            .set("class", "editor")
            .set("name", "page_content")
            .set("text", this.get("html"))
            .inject(toolboxForm);
        ////////////////////////////////////////////////////////
        var editor = textarea;
        var parent = this.getParent();
        var toolboxDiv = new Element('div');
        toolboxDiv.set("class", "toolbox");
        var inputHidden = new Element('input');
        inputHidden
            .set("type", "hidden")
            .set("value", pageLoader)
            .set("name", "page_name")
            .inject(toolboxDiv);
        var selector = new Element('select');
        var options = [
            {value: '', text: "[select]"},
            {value: "article", text: "article"},
            {value: "gallery", text: "gallery"}
        ];
        buildSelectorOptions(selector, options, 'article');
        selector
            .set('title', "Typ av sida - ändra inte om du inte vet vad du håller på med :-)")
            .set("name", "page_type")
            .inject(toolboxDiv);
        var saveButton = new Element('input');
        saveButton
            .set("type", "button")
            .set("value", "Save")
            .set("name", "btn_save")
            .addEvent('click', function(){
                toolboxForm.send();
            })
            .inject(toolboxDiv);
        var editDiv = this;
        var cancelButton = new Element('input');
        cancelButton
            .set("type", "button")
            .set("value", "Avbryt")
            .addEvent("click", function(){
                toolboxForm.destroy();
                editDiv.set("styles", {display: "inherit"});
            })
            .inject(toolboxDiv);

        toolboxDiv.inject(toolboxForm);
        toolboxForm.inject(parent);
        ////////////////////////////////////////////////////////
        this.set("styles", {display: "none"});
    });
});

var buildSelectorOptions = function(ddl, options, index)
{
    var option;
    options.each(function(el, indx){
        option = new Element('option');
        option
            .set('value', el.value)
            .set('text', el.text)
            .set('selected', el.value==index?'selected':'')
            .inject(ddl);
    });
}

var buildToolBoxSet = function(editor, parent, position)
{
    var toolboxForm = new Element('form');
    toolboxForm
        .set("send", post);
    var toolboxDiv = new Element('div');
    toolboxDiv.set("class", "toolbox");
    var inputHidden = new Element('input');
    inputHidden
        .set("type", "hidden")
        .set("value", pageLoader)
        .set("name", "page_name")
        .inject(toolboxDiv);
    var saveButton = new Element('input');
    saveButton
        .set("type", "button")
        .set("value", "Spara")
        .set("name", "btn_save")
        .addEvent('click', function(){
            toolboxForm.send();
        })
        .inject(toolboxDiv);
    var cancelButton = new Element('input');
    cancelButton
        .set("type", "button")
        .set("value", "Avbryt")
        .addEvent("click", function(){
            toolboxForm.destroy();
        })
        .inject(toolboxDiv);

//    var addButton = new Element('input');
//    addButton
//        .set("type", "button")
//        .set("value", "+")
//        .set("Name", "btn_add")
//        .inject(toolboxDiv);

    toolboxDiv.inject(toolboxForm);
    toolboxForm.inject(parent);

}

var post = {
    url: "/api/page/save",
    data: pageLoader,
    method: 'post',
    noCache: true,
    onSuccess: function() {
        wait(false);
        window.location.reload(true);
    },
    onFailure: function() {
        wait(false);
        alert("fail");
    },
    onRequest: function() {
        wait(true);
    }
};

var get = new Request({
    url: "/exit/editormode",
    method: 'get',
    onSuccess: function() {
        wait(false);
        $(document).getElement('btn_exiteditor').destroy();
        window.location.reload(true);
    },
    onFailure: function() {
        wait(false);
        alert("fail");
    },
    onRequest: function() {
        wait(true);
    }
});

var wait = function(on) {
    var cursorStyle = '';
    if (on) {
        cursorStyle = 'wait';
    }
    $(document.body).set("styles", {cursor: cursorStyle});
}
