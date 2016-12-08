<?php
class Movie {

    private $connection;

    function __construct($mysqli){

        $this->connection = $mysqli;

    }

function saveData ($Username, $favActor, $favMov, $movGenre) {
		
		$stmt = $this->connection->prepare("INSERT INTO user_movies(username, movie_actor, movie_fav, movie_genre) VALUES (?, ?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ssss", $Username, $favActor, $favMov, $movGenre);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$this->connection->close();
		
	}
	
	function getMovieData() {
	
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
	
		$stmt = $this->connection->prepare("
		
		SELECT id, username, movie_actor, movie_fav, movie_genre
		FROM user_movies 
		WHERE deleted IS NULL");
		
		echo $this->connection->error;
		
		$stmt->bind_result($id, $username, $movie_actor, $movie_fav, $movie_genre);
		$stmt->execute();
		
		$result = array();
		

		while($stmt->fetch()) {
			

		$i = new StdClass();
		
		$i->id = $id;
		$i->Username = $username;
		$i->favActor = $movie_actor;
		$i->favMov = $movie_fav;
		$i->movGenre = $movie_genre;
		
		
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