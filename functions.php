<?php

	//6. tund
    //<script>location.href=""</script>

	//-) "tühik" on trim function
	//-) < > html special char &lt; -> <  [& -> &amp;]
	//-) \"

//igale poole exit ja cleaninput

	require("../../config.php");

	//functions.php
	//var_dump($GLOBALS)
	
	// see fail peab olema kõigil lehtedel, kus tahan
	//kasutada SESSION muutujat
	session_start();
	
	//*****************
	//**** SIGNUP *****
	//*****************	
		
	function signUp ($username, $email, $password, $website, $comment, $age)	{
		
		$database = "if16_ege";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
	
		//sqli rida
		$stmt = $mysqli->prepare("INSERT INTO user_sample (username, email, password, website, comment, age) VALUES (?, ?, ?, ?, ?, ?)");
		
		echo $mysqli->error;
		
		$stmt->bind_param("ssssss", $username, $email, $password, $website, $comment, $age);
		
		//täida käsku
		if($stmt ->execute() ) {
			
			echo "salvestamine õnnestus";
			
		} else {
			echo "ERROR ". $stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	
		
	function login ($username, $password) {
		
		$error = "";
		
		
		$database = "if16_ege";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
	
		//sqli rida
		$stmt = $mysqli->prepare("
		SELECT id, username, email, password, created 
		FROM user_sample WHERE username = ?");
		
		echo $mysqli->error;
		
		//asendan küsimärgi s on string (d on komaga arv, i on täisarv)
		$stmt->bind_param("s", $username);
		
		//määran väärtused muutujatesse
		$stmt->bind_result($id, $usernameFromDb, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//andmed tulid andmebaasist või mitte
		//on tõene kui on vähemalt üks vaste
		if($stmt->fetch()){
			
			//oli sellise emailiga kasutajat
			//password millega kasutaja tahab sisse logida
		$hash = hash("sha512", $password);
		if ($hash == $passwordFromDb) {
			echo "kasutaja logis sisse" .$id;
			
			$_SESSION["userId"] = $id;
			$_SESSION["userName"]= $usernameFromDb;
			$_SESSION["userEmail"] = $emailFromDb;
			
			$_SESSION["message"] = "<h1>Tere tulemast!</h1>";
			
			//määran sessiooni muutujad millele saan ligi teistelt lehtedelt
			header("Location: data.php");
			exit();
			
		} else {
			$error = "vale parool";
			
		}
		
		} else {
			
			//ei leidnud kasutajat selle nimega
			$error = "ei ole sellist kasutajanime";
		}
			
		return $error; 
		
	}	
		
		function saveData ($Username, $favActor, $favMov, $movGenre) {
		
		$database = "if16_ege";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $mysqli->prepare("INSERT INTO user_movies(username, movie_actor, movie_fav, movie_genre) VALUES (?, ?, ?, ?)");
	
		echo $mysqli->error;
		
		$stmt->bind_param("ssss", $Username, $favActor, $favMov, $movGenre);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	function getMovieData() {
	
		$database = "if16_ege";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
	
		$stmt = $mysqli->prepare("
		
		SELECT id, username, movie_actor, movie_fav, movie_genre
		FROM user_movies
		");
		
		echo $mysqli->error;
		
		$stmt->bind_result($id, $username, $movie_actor, $movie_fav, $movie_genre);
		$stmt->execute();
		
		//tekitan massiivi
		$result = array();
		
		//tee seda seni, kuni on rida andmeid
		//mis vastab select lausele
		while($stmt->fetch()) {
			
			
		//tekitan objekti
		$i = new StdClass();
		
		$i->id = $id;
		$i->Username = $username;
		$i->favActor = $movie_actor;
		$i->favMov = $movie_fav;
		$i->movGenre = $movie_genre;
		
		
		//echo $plate."<br>";
		//igakord massiivi lisan juurde nr märgi
		array_push($result, $i);				
		}
			
		
			
		$stmt->close();
		$mysqli->close();
		
		return $result;
		
	}
		
		
	function cleanInput($input){
		
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
	return $input;	
		
		
		
		
	}
		
		
		
		
		
		
		
		
		

		
		

?>