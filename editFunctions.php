<?php

	require_once("../../config.php");

	function getSingleMovieData($edit_id){

		$database = "if16_ege";

		//echo "id on ".$edit_id;

		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

		$stmt = $mysqli->prepare("SELECT movie_actor, movie_fav, movie_genre FROM user_movies WHERE id=? 
			AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($favActor, $favMov, $movGenre);
		$stmt->execute();

		//tekitan objekti
		$movie = new Stdclass();

		if($stmt->fetch()){
			$movie->movie_actor = $favActor;
			$movie->movie_fav = $favMov;
			$movie->movie_genre = $movGenre;


		}else{

			header("Location: data.php");
			exit();
		}

		$stmt->close();
		$mysqli->close();

		return $movie;

	}

	function updateMovie($id, $favActor, $favMov, $movGenre){

		$database = "if16_ege";


		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

		$stmt = $mysqli->prepare("UPDATE user_movies SET plate=?, color=? WHERE id=? 
			AND deleted IS NULL");

		$stmt->bind_param("sssi",$favActor, $favMov, $movGenre, $id);

		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}

		$stmt->close();
		$mysqli->close();

	}

	function deleteMovie($id){

		$database = "if16_ege";


		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);

		$stmt = $mysqli->prepare("UPDATE user_movies SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);

		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}

		$stmt->close();
		$mysqli->close();

	}



?>