<?php
class User {
	
	private $connection;
	
	function __construct($mysqli) {
		
		//this viitab klassile (this == User)
		$this->connection = $mysqli;
		
		
	}
	
	/*TEISED FUNKTSIOONI*/
	function signUp ($username, $email, $password, $website, $age)	{
		
		$stmt = $this->connection->prepare("INSERT INTO user_sample (username, email, password, website, age) VALUES (?, ?, ?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("sssss", $username, $email, $password, $website, $age);
		
		if($stmt->execute()) {
			echo "salvestamine �nnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$this->connection->close();
		
	}
	
	
	function login ($username, $password) {
		
		$error = "";

		$stmt = $this->connection->prepare("
		SELECT id, username, password, created 
		FROM user_sample
		WHERE username = ?");
	
		echo $this->connection->error;
		
		$stmt->bind_param("s", $username);
		
		$stmt->bind_result($id, $usernameFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		if($stmt->fetch()){
			
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				
				echo "Kasutaja logis sisse ".$id;
				
				$_SESSION["userId"] = $id;
				$_SESSION["userName"] = $usernameFromDb;
				
				$_SESSION["message"] = "<h1>Tere tulemast!</h1>";
				
				header("Location: movies.php");
				exit();
				
			}else {
				$error = "vale parool";
			}
			
			
		} else {
			
			$error = "ei ole sellist kasutajanime";
		}
		
		return $error;
		
	}
}
?>