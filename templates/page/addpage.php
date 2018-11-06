<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <base href="http://dev.coder:8028/">
    <script type="text/javascript" src="res/js/MooTools-Core-1.6.0.js"></script>
    <style type="text/css">
        form label, form input, form textarea {
            font-family: monospace;
        }
        form label.newline {
            display: block;
        }
        form label {
            padding: 2px 5px;
            background-color: #000000;
            color: #ffffff;
        }
        form div {
            margin: 20px 0;
            width: 80%;
        }
        form textarea {
            width: 100%;
            height: 150px;
        }
    </style>
</head>
<body>
    <form>
        <div>
            <label for="filename" title="The page-directive in routes.json points to [page].inc.html for generic">Actual filename:</label>
            <input type="text" name="filename" id="filename" />
        </div>
        <div>
            <label for="routes" class="newline">routes.json</label>
            <textarea id="routes" name="routes"><?= $routesJson;?></textarea>
        </div>
        <div>
            <label for="htmlcontent" class="newline">HTML-content</label>
            <textarea id="htmlcontent" name="htmlcontent"></textarea>
        </div>
        <div>
            <input type="button" id="submit" value="Lägg till" />
        </div>
    </form>
    <script type="text/javascript">
        $(window).addEvent("domready", function(){
            $('routes').addEvent('change', function(){
                console.log($('routes').value);
                if (!JSON.validate($('routes').value)) {
                    alert("Please check the routes-json - it does not pass as json")
                    return false;
                }
            });
            $('submit').addEvent('click', function(e){
                submitForm();
            });
        });
        var submitForm = function()
        {
            //validate form
            if (!JSON.validate($('routes').value)) {
                alert("Please check the routes-json - it does not pass as json")
                return false;
            }
            if ($('htmlcontent').value.length < 6) {
                alert("Please add html content")
                return false;
            }
            if ($('filename').value.length == 0) {
                alert("Please supply a valid filename")
                return false;
            }
            var fd = new FormData();
            fd.append('routes', $('routes').value)
            fd.append('filename', $('filename').value)
            fd.append('htmlcontent', $('htmlcontent').value)
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add/page', true);
            xhr.send(fd)
        }
    </script>
</body>
</html>
