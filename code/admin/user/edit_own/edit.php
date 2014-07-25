<?php
if(isset($_SESSION['user']['id'])){
	$_SESSION['admin']['user-id']=$_SESSION['user']['id'];
}

$length = strlen($_SESSION['admin']['user-id']);
$pattern = "/\\d{".$length."}/";

if(!preg_match($pattern, $_SESSION['admin']['user-id'])){
	die("manipulation detectet user id");
}

$dbc = new PDO("mysql:host=".$host.";dbname=".$dbank, $uname, $pword);
$dbc->query("SET NAMES 'utf8'");

$sql = "SELECT *, BIN(rights+0) as rights FROM user WHERE uid = :id";
$stmt = $dbc->prepare($sql);

$stmt->bindParam(':id', $_SESSION['admin']['user-id']);

if($stmt->execute()){
	$data = $stmt->fetch(PDO::FETCH_BOTH);
}
else{
	die("Beim auslesen der Benutzerdaten ist ein Fehler aufgetreten");
}
?>
<div id="movie-edit-wrapper">
	<form name="movie-edit" method="post" action="index.php" enctype="multipart/form-data">
    <input name="check" id="check" type="hidden" value="true,true,true,true,true,true" />
    <table>
        <tr>
            <td align="right">Name:</td>
            <td><input name="name" onkeyup="checktext(this.value, 'name', 0, false, false,'',true)" value="<?php echo $data['name']; ?>" type="text"/></td>
        </tr>
        <tr>
            <td align="right">Nachname:</td>
            <td><input name="surname" onkeyup="checktext(this.value, 'surname', 1, false, false,'',true)" value="<?php echo $data['surname']; ?>" type="text" /></td>
        </tr>
        <tr>
            <td align="right">Benutzername:</td>
            <td><input name="username" onkeyup="checktext(this.value, 'username', 2, false, false,'',true)" value="<?php echo $data['username']; ?>" type="text" /></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:12px;">Das Passwort muss enthalten:<br /> mindestens 8 Zeichen, 1 Ziffer(n), 1 Kleinbuchstabe(n), 1 Großbuchstabe(n), 1 Sonderzeichen</td>
        </tr>
        <tr>
            <td align="right">altes Password:</td>
            <td><input name="password_old" onkeyup="checkPassword(this.value, 'password_old', 3)" type="password" /></td>
        </tr>
        <tr>
            <td align="right">neues Password:</td>
            <td><input name="password" onkeyup="checkPassword(this.value, 'password', 4)" type="password" /></td>
        </tr>
        <tr>
            <td align="right">Password wiederholen:</td>
            <td><input name="password_rwd" onkeyup="checkPassword(this.value, 'password_rwd', 5)" type="password" /></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><div id="pwd_error_message"></div></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input name="setup" type="hidden" value="1" />
                <input id="sub" type="submit" onclick="return checkEquality(document.getElementsByName('password')[0].value, document.getElementsByName('password_rwd')[0].value, 'pwd_error_message','Die Passwörter stimmen nicht überein'); this.form.submit()" value="updaten"/>
            </td>
        </tr>
 	</table> 
    </form>
</div>