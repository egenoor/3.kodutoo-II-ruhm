<?php


	require("../functions.php");
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])) {
		
		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
		
	}

	//kui on ?logout aadressi real siis login välja
	if(isset ($_GET["logout"])) {
		
		session_destroy();
		header("Location:login.php");
		exit();
	}

	$msg = " ";
	if(isset($_SESSION["message"])) {
		$msg = $_SESSION["message"];
	
		//kui ühe näitame siis kusutua ära, et pärast refreshi ei näita
	unset($_SESSION["message"]);
	
		}

	
?>
<?php require("../header.php"); ?>
<div class="container">
	<?=$msg;?>

	<h1></h1><p> Welcome <?=$_SESSION["userName"];?>!</p></h1>
	<p><a href="movies.php"> Movies </a></p>
	<a href="?logout=1"> Log out</a>


	<br><br>
	<br><br>
</div>
<?php require("../footer.php"); ?>