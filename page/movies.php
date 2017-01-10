<?php

	require("../functions.php");
	require("../class/Movie.class.php");
	$Movie = new Movie($mysqli);

	//MUUTUJAD
	$Username = "";
	$favActor = "";
	$favMov = "";
	$movGenre = "";

	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){

		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}


	//kui on ?logout aadressireal siis log out
	if (isset($_GET["logout"])) {

		session_destroy();
		header("Location: login.php");
		exit();
	}

	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		unset($_SESSION["message"]);
	}

	if (isset($_POST["favActor"]) &&
		!empty ($_POST["favActor"])) {
			$favActor = $Helper->cleanInput($_POST["favActor"]);
		}

	if (isset($_POST["favMov"]) &&
		!empty ($_POST["favMov"])) {
			$favMov = $Helper->cleanInput($_POST["favMov"]);
			}

	if (isset($_POST["movGenre"]) &&
		!empty ($_POST["movGenre"])) {
			$movGenre = $Helper->cleanInput($_POST["movGenre"]);
		}

$error= "";

	if(isset($_POST["favActor"]) &&
		isset($_POST["favMov"]) &&
		isset($_POST["movGenre"]) &&
		!empty($_POST["favActor"]) &&
		!empty($_POST["favMov"]) &&
		!empty($_POST["movGenre"])) {

		$Movie->save($Helper->cleanInput($_SESSION["userName"]), $Helper->cleanInput($_POST["favActor"]),
			$Helper->cleanInput($_POST["favMov"]), $Helper->cleanInput($_POST["movGenre"]));

		}
	elseif(isset($_POST["favActor"]) &&
			isset($_POST["favMov"]) &&
			isset($_POST["movGenre"]) &&
			empty($_POST["favActor"]) &&
			empty($_POST["favMov"]) &&
			empty($_POST["movGenre"])) {

			$error = "Täida kõik väljad";
		}

	echo $error;
	
		//sorteerib
	if(isset($_GET["sort"]) && isset($_GET["direction"])){
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	} else {
		//kui ei ole määratud siis vaikimis id ja ASC
		$sort = "id";
		$direction = "ascending";
		
	}
	
	//kas otsib
	if(isset($_GET["q"])){
		
		$q = $Helper->cleanInput($_GET["q"]);
		
		$movieData = $Movie->get($q, $sort, $direction);
	
	} else {
		$q = "";
		$movieData = $Movie->get($q, $sort, $direction);
	
	}
	


?>


<?php require("../header.php"); ?>

<div class="container">

	<div class="row">

		<div class="col-sm-3">

			<p><a href="?logout=1"> <button onclick="goBack()">Log out</button></a></p>
			<h1>Movies</h1>
			<?=$msg;?>
			<p>
				Welcome <?=$_SESSION["userName"];?>!
			</p>

			<h2> Add data </h2>
			<form method="POST">

				<label>Favorite actor:</label><br>
				<input name="favActor" type="text" class="form-control" value="<?=$favActor;?>">

				<br><br>

				<label>Favorite movie:</label><br>
				<input name="favMov" type="text" class="form-control" value="<?=$favMov;?>">

				<br><br>


				<label>Movie genre:</label><br>
				<select name="movGenre">
					<option value="" <?php echo $result['genre'] == 'Action' ? 'selected' : ''?> >Genre</option>
					<option value="Action" <?php echo $result['genre'] == 'Action' ? 'selected' : ''?> >Action</option>
					<option value="Comedy" <?php echo $result['genre'] == 'Comedy' ? 'selected' : ''?>>Comedy</option>
					<option value="Crime" <?php echo $result['genre'] == 'Crime' ? 'selected' : ''?>>Crime</option>
					<option value="Adventure" <?php echo $result['genre'] == 'Adventure' ? 'selected' : ''?> >Adventure</option>
					<option value="War" <?php echo $result['genre'] == 'War' ? 'selected' : ''?>>War</option>
					<option value="Sci-Fi" <?php echo $result['genre'] == 'Sci-Fi' ? 'selected' : ''?>>Sci-Fi</option>
					<option value="Romance" <?php echo $result['genre'] == 'Romance' ? 'selected' : ''?>>Romance</option>
					<option value="Horror" <?php echo $result['genre'] == 'Horror' ? 'selected' : ''?> >Horror</option>
					<option value="Documentary" <?php echo $result['genre'] == 'Documentary' ? 'selected' : ''?>>Documentary</option>
					<option value="Fantasy" <?php echo $result['genre'] == 'Fantasy' ? 'selected' : ''?>>Fantasy</option>
				</select>
				<input class="btn btn-default btn-sm" type="submit" value="Submit">

			</form>

		</div>

	</div>



	<div class="row">

			<div class="col-sm-3">
				<h2>Movies</h2>
				<form>
					<input type="search" class="form-control" name="q">
					<input class="btn btn-default btn-sm" type="submit" value="Search">
				</form>
			</div>
	</div>


	<?php

		$direction = "ascending";
		if(isset($_GET["direction"])){
			if ($_GET["direction"] == "ascending"){
				$direction = "descending";
			}

		}
		$html = "<table class='table table-striped table-bordered'>";

		$html .= "<tr>";
			$html .= "<th><a href=?q=".$q."&sort=id&direction=".$direction."'>id</a></th>";
			$html .= "<th><a href='?q=".$q."&sort=username&direction=".$direction."'>username</a></th>";
			$html .= "<th><a href='?q=".$q."&sort=actor&direction=".$direction."'>actor</a></th>";
			$html .= "<th><a href='?q=".$q."&sort=movie&direction=".$direction."'>movie</a></th>";
			$html .= "<th><a href='?q=".$q."&sort=genre&direction=".$direction."'>genre</a></th>";
		$html .= "</tr>";

		foreach($movieData as $i){
			$html .= "<tr>";
				$html .= "<td>".$i->id."</td>";
				$html .= "<td>".$i->username."</td>";
				$html .= "<td>".$i->favActor."</td>";
				$html .= "<td>".$i->favMov."</td>";
				$html .= "<td>".$i->favGenre."</td>";
				$html .= "<td>
							<a href='edit.php?id=".$i->id."'>
          					<span class=\"glyphicon glyphicon-cog\"></span>
							</a></td>";

			$html .= "</tr>";
		}

		$html .= "</table>";

		echo $html;
	?>
</div>


<?php require("../footer.php"); ?>