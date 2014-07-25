<?php
$_SESSION['admin']['setup-typ']="useradd";

include("check_and_add.php");

if(!isset($_POST['username'])){
?>

<h2>Benutzer hinzuf&uuml;gen</h2>
<form method="post" action="index.php">
<input name="check" id="check" type="hidden" value="false,false,false,false,false" />
<input name="setup" type="hidden" value="1" />
<table>
    <tr>
        <td align="right">Name:</td>
        <td><input name="name" onkeyup="checktext(this.value, 'name', 0, false, false,'',true)" type="text"/></td>
    </tr>
    <tr>
        <td align="right">Nachname:</td>
        <td><input name="surname" onkeyup="checktext(this.value, 'surname', 1, false, false,'',true)" type="text" /></td>
    </tr>
    <tr>
        <td align="right">Benutzername:</td>
        <td><input name="username" onkeyup="checktext(this.value, 'username', 2, false, false,'',true)" type="text" /></td>
    </tr>
    <tr>
        <td colspan="2" style="font-size:12px;">Das Passwort muss enthalten:<br /> mindestens 8 Zeichen, 1 Ziffer(n), 1 Kleinbuchstabe(n), 1 Großbuchstabe(n), 1 Sonderzeichen</td>
    </tr>
    <tr>
        <td align="right">Password:</td>
        <td><input name="password" onkeyup="checkPassword(this.value, 'password', 3)" type="password" /></td>
    </tr>
    <tr>
        <td align="right">Password wiederholen:</td>
        <td><input name="password_rwd" onkeyup="checkPassword(this.value, 'password_rwd', 4)" type="password" /></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><div id="pwd_error_message"></div></td>
    </tr>    
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
	<tr>
        <td colspan="2" align="center"><b>Rechte für den neuen Benutzer:</b></td>
    </tr>
    <tr>
    	<td align="right" style="padding-right:40px;">
        	<b><u>Medium:</u></b><br />
            hinzufügen<input name="rights[]" type="checkbox" value="0"/><br />
            löschen<input name="rights[]" type="checkbox" value="1"/><br />
            bearbeiten<input name="rights[]" type="checkbox" value="2"/><br />
            ausgeliehen<input name="rights[]" type="checkbox" value="3"/><br />
        </td>
        <td align="left" valign="top">
        	<b><u>Benutzer:</u></b><br />
            <input name="rights[]" type="checkbox" value="4"/>hinzufügen<br />
            <input name="rights[]" type="checkbox" value="5"/>löschen<br />
            <input name="rights[]" type="checkbox" value="6"/>bearbeiten<br />
        </td>
    </tr> 
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>   
    <tr>
        <td><input type="reset" value="Abbrechen" /></td>
        <td align="center"><input id="sub" type="submit" style="visibility:hidden;" onclick="return checkEquality(document.getElementsByName('password')[0].value, document.getElementsByName('password_rwd')[0].value, 'pwd_error_message','Die Passwörter stimmen nicht überein'); this.form.submit()" value="hizufügen" /></td>
    </tr>
</table>
</form>
<?php
}
?>