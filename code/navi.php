<div id="navi1" align="center">
<?php 
if($Public||isset($_SESSION['user']['logged'])){
	link_page("Filme",7,"content","typ","0");
	link_page("Serien",7,"content","typ","1");
	link_page("Sonstiges",7,"content","typ","2");
}
?>
</div>
<div id="navi2">
<?php
link_page("Startseite", 0, "content");
link_page("Erweiterte Suche", 3, "content");
//Prüfen ob eine Session id vorhanden ist, dann bekommt man die Admin Seiten angezeigt
if(isset($_SESSION['user']['logged'])){
	link_page("Adminbereich", 4, "content");
}
?>


	<div id="search">
        <form id="searchForm" name="search" action="index.php" method="post" autocomplete="off">
            <input id="searchValue" name="searchValue" onkeyup="showHint(this.value);" onfocus="this.value=''" type="text" maxlength="40" value="Suche ..." />
            <input name="content" type="hidden" value="2" />
        </form>
        <a href="#" onclick="document.search.submit()">
            <div id="searchSubmit"></div>
        </a>
        <div id="searchHint">
        &nbsp;
        </div>
    </div>
</div>