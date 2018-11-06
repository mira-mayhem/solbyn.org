<ul class="sb_menu">
    <li><a href="member" <?= $this->isSelected('member');?>>För boende</a></li>
    <li><a href="member/solbybladet" <?= $this->isSelected('solbybladet');?>>Solbybladet</a></li>
    <!--<li><a href="member/protocols" <?= $this->isSelected('protocols');?>>Protokoll</a></li>-->
    <!--<li><a href="member/thevice" <?= $this->isSelected('thevice');?>>Vicevärden informerar</a></li>-->
    <li><a class="locked" href="member/imageuploader">Bidra med bilder</a></li>
    <li><a class="locked" href="enter/editmode">Redigera!</a></li>
    <li><a class="locked" href="add/page">Lägg till sida</a></li>
</ul>
