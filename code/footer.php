	<?php
	if($_SESSION['content']!=6) echo '<div id="footer">';
	else  echo '<div id="details-footer">';
	?>
        <a href="http://www.spezelechse.de" target="_blank">
        	<div id="creators_link"></div>
        </a>
    </div>
    <div id="scrollNavi" style="visibility:hidden;">
        <?php
		if(isset($_SESSION['listNeeded'])){
			if($_SESSION['overviewTyp']==1){
				echo '<div id="overviewCover" onclick="changeOverviewTyp('.$_SESSION['overviewTyp'].');">';
				echo '</div>';
			}
			else if($_SESSION['overviewTyp']==0){
				echo '<div id="overviewList" onclick="changeOverviewTyp('.$_SESSION['overviewTyp'].');">';
				echo '</div>';
			}
		}
		?>
    	<div id="scrollUpFast" onclick="scrollComplete(true,-1)"></div>
        <div id="scrollup" onmouseover="scrollSlow(true)" onmouseout="scrollStop()"></div>
        <div id="scrolldown" onmouseover="scrollSlow()" onmouseout="scrollStop()"></div>  
        <div id="scrollDownFast" onclick="scrollComplete(false,-1)"></div>
    </div>
</div>
</body>
</html>