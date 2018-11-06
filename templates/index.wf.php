<?php
global $ioc;
$sess = $ioc->getSessionService();
$editMode = $sess->get('editmode', false);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>BRF Solbyn i Dalby</title>
  <base href="http://dev.coder:8028/">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link href="res/styles/style.css" rel="stylesheet" type="text/css" />
  <link href="res/styles/app.css" rel="stylesheet" type="text/css" />
  <link href="res/styles/obox.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="res/js/MooTools-Core-1.6.0.js"></script>
  <script type="text/javascript" src="res/js/general.js"></script>
  <script type="text/javascript" src="res/js/obox.js"></script>
  <?php
  if ($editMode) {
    ?>
    <script type="text/javascript">
      var pageLoader="<?= $page?>";
    </script>
    <script type="text/javascript" src="res/js/adminbootstrapper.js"></script>
    <link href="res/styles/admin/toolbox.css" rel="stylesheet" type="text/css" />
    <?php
  }
  ?>
</head>
<body>
  <div class="main">
    <div class="header">
      <div class="header_resize">
        <div class="logo">
          <img src="images/solbyn_v1.png" style="width: 220px;" />
        </div>
        <div class="menu_nav">
          <ul>
            <li class="<?= $activeLinkMap['start'] ?>"><a href="/">Hem</a></li>
            <li class="<?= $activeLinkMap['about'] ?>"><a href="about">Om Solbyn</a></li>
            <li class="<?= $activeLinkMap['contact'] ?>"><a href="contact">Kontakt</a></li>
            <li class="<?= $activeLinkMap['gallery'] ?>"><a href="gallery">Bilder</a></li>
          </ul>
        </div>
        <div class="clr"></div>
      </div>
    </div>
    <div class="content">
      <div class="content_resize">
        <div class="ctop" style="background-image: url(images/gallery/<?= $imgMap[$page];?>); width: 927px; height: 240px; background-repeat: no-repeat; background-size: cover; background-position: 0 -190px; position: relative; top: 20px;"></div>
        <div class="mainbar">
          <?php
          echo $content;
          ?>
        </div>
        <div class="sidebar">
          <div class="gadget">
            <?php
            if (isset($submenu)) {
              echo $submenu->render();
            }
            ?>
          </div>
          <div class="gadget">
          </div>
        </div>
        <div class="clr"></div>
      </div>
    </div>
    <div class="fbg">
      <div class="fbg_resize">
        <div class="col c1">
          <h2>Om hemsidan</h2>
          <p>Sidan är under utveckling - uppdaterades senast <?= date("Y-m-d", filemtime(__FILE__)) ;?>
            <br />Är det något du saknar eller vill fråga om - kontakta <a href="mailto:webmaster@solbyn.org">webmaster</a></p>
          </div>
          <div class="clr"></div>
        </div>
      </div>
      <div class="footer">
        <div class="footer_resize">
          <p class="lf">Designed by Blue <a href="http://www.bluewebtemplates.com/">Website Templates</a> - compromised by Solbyns webmaster</p>
          <div class="clr"></div>
        </div>
      </div>
    </div>
    <div class="div-navigation left-arrow obox">
      <a class="image-navigation" id="previousButton">
        <svg class="left" viewbox="0 0 10 10">
          <path d="M6,8 L3,5 L6,2"></path>
        </svg>
        </a>
    </div>
    <div class="div-navigation right-arrow obox">
      <a class="image-navigation" id="nextButton">
        <svg class="right" viewbox="0 0 10 10">
          <path d="M4,2 L7,5 L4,8"></path>
        </svg>
      </a>
    </div>
  </body>
  </html>
