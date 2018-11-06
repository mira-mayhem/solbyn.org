<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$host = $_SERVER['HTTP_HOST'];
$scheme = $_SERVER['REQUEST_SCHEME'];
$uri = preg_split('/&/', $_SERVER['REQUEST_URI'])[0];
$url = sprintf("%s://%s%s", $scheme, $host, $uri);
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

        ul.dirlist li {
            list-style: none;
            font-family: monospace;
            margin-bottom: 2px;
            padding: 4px;
            border-radius: 2px;
        }
        ul.dirlist li img {
            width: 12px;
            margin-right: 5px;
        }
        ul.dirlist li.file {
            width: fit-content;
        }
        ul.dirlist li:hover {
            cursor: pointer;
        }
        div.dir {
            font-family: monospace;
            border: outset 1px;
            padding: 3px;
            width: 500px;
            background-color: #fffcc7cc;
            cursor: pointer;
        }
        ul.dirlist {
            margin: 10px 0;
        }
        svg path {
            fill: #000000;
            transition: 0.5
        }
        svg:hover path {
            fill: #808080;
        }
        ul.dirlist li.uplevel {
            margin-left: -20px;
            margin-bottom: -18px;
        }
        ul.dirlist li.selected {
            background-color: #3390ff;
            color: #ffffff;
        }
        ul.dirlist li.rcselected {
            background-color: #ff6f00;
            color: #ffffff;
        }
        div label, div input {
            font-family: monospace;
            font-size: 11px;
        }
        div input[type=text] {
            width: 200px;
        }
        div label {
            display: inline-block;
            width: 120px;
        }
        div.form {
            padding: 10px;
            border: 1px outset;
            width: fit-content;
            background-color: #fffcc7cc;
        }
        div#contextmenuDelete {
            position: fixed;
        }
        div#contextmenuDelete li {
            font-family: monospace;
            list-style: none;
            background-color: #dadada;
            border: outset;
            padding: 5px;
        }
        div#contextmenuDelete li:hover {
            background-color: "blue";
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Review images</h1>
        <h3> - move to correct folder and create thumbnails</h3>
        <div class="dir" href="<?= $dir; ?>">
            <?= $dir; ?>
        </div>
        <?= $dirlisting; ?>
        <div class="form">
            <!-- separera tumnagelkreation från flytt
            skapa ny targetdirectory för tumnageln
            sätt ny checkbox för att flytta  -->
            <div>
                <div>
                    <label for="chbMove">Move file</label>
                    <input type="checkbox" value="" id="chbMove" />
                </div>
                <div>
                    <label for="txtTargetDirectory">Target directory</label>
                    <input type="text" value="" id="txtTargetDirectory" placeholder="/images/gallery" />
                </div>
            </div>
            <hr />
            <div>
                <div>
                    <label for="chbThumb">Create thumbnails</label>
                    <input type="checkbox" value="" id="chbThumb" />
                    </div>
                <div>
                    <label for="txtThumbTargetSize">Thumbnailsize</label>
                    <input type="text" value="" id="txtThumbTargetSize" placeholder="width:110" />
                </div>
            </div>
            <hr />
            <div>
                <label for="btnSubmitForm">...</label>
                <input type="button" id="btnSubmitForm" value="Execute" />
            </div>
        </div>
    </div>
    <div id="contextmenuDelete">
        <ul>
            <li id="deleteImage">
                Remove image
            </li>
        </ul>
    </div>
</body>
<script type="text/javascript">
    $(window).addEvent('domready', function(){
        $$('.dir').addEvent("click", function(){
            var uri = this.get('href')
            window.location.href = '<?= $url?>&directory=' + uri
        })
        $$('.file').addEvent('click', function(){
            this.toggleClass('selected');
        })
        $$('.file').addEvent("dblclick", function(){
            var href = this.get('href');
            var dir = ('<?= $dir?>').replace('<?= $root?>', '')
            var url = '<?= $scheme?>://<?= $host?>/' + dir + '/' + href;
            window.open(url);
        }).addEvent('contextmenu', function(e){
            e.preventDefault()
            popDelete(this)
        })
        $('btnSubmitForm').addEvent("click", function(){
            var fd = new FormData()
            var td = $('txtTargetDirectory').value ? $('txtTargetDirectory').value : $('txtTargetDirectory').get('placeholder')
            var ttd = $('txtThumbTargetSize').value ? $('txtThumbTargetSize').value : $('txtThumbTargetSize').get('placeholder')
            fd.append('targetdirectory', td)
            fd.append('thumbsizes', ttd)
            fd.append('domove', $('chbMove').get('checked'))
            fd.append('createthumb', $('chbThumb').get('checked'))
            $$('.selected').each(function(el){
                fd.append('image[]', el.get('href'))
            });
            fd.append('sourcedirectory', '<?= $dir; ?>')
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'handle/images', true);
            xhr.onload = function() {
                console.log(this.response);
            }
            xhr.send(fd);
        })
        $('contextmenuDelete').setStyle('display', 'none');
        $('deleteImage').addEvent('click', function(){
            removePopUp();
            var msg = 'Ta bort bilden ' + currentImage + '? Åtgärden kan inte ångras'
            if(confirm(msg)) {
                var fd = new FormData()
                fd.append("filename", currentImage)
                fd.append('sourcedirectory', '<?= $dir; ?>')
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete/file', true);
                xhr.onload = function() {
                    console.log(this.response);
                }
                xhr.send(fd);
            }
        })
    });
    var removePopUp = function(){
        $$('.file').removeClass('rcselected')
        $('contextmenuDelete').setStyle('display', 'none')
    }
    var popDelete = function(el){
        currentImage = el.get('href');
        calcPosition(el);
        $$('.file').removeClass('rcselected')
        el.addClass('rcselected')
        $('contextmenuDelete').setStyle('display', 'block')
    }
    var currentImage;
    var calcPosition = function(el){
        var boundingBox = el.getBoundingClientRect()
        var top = boundingBox.top - 5
        var left = boundingBox.left + boundingBox.width - 30
        $('contextmenuDelete')
            .setStyle('top', top)
            .setStyle('left', left)
    }
</script>
</html>
