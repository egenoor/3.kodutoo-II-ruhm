<?php
//edit.php
require("functions.php");
require("editFunctions.php");

//var_dump($_POST);

//kas kasutaja uuendab andmeid
if(isset($_POST["update"])){

    updateMovie(cleanInput($_POST["id"]), cleanInput($_POST["movie_actor"]), cleanInput($_POST["movie_fav"]), cleanInput($_POST["movie_genre"]));

    header("Location: edit.php?id=".$_POST["id"]."&success=true");
    exit();

}

if(!isset($_GET["id"])) {
    header("Location: data.php");
    exit();
}

$c = getSingleMovieData($_GET["id"]);

if(isset($_GET["success"])){
    echo "salvestamine õnnestus";
}

if(isset($_GET["delete"])){

    deleteMovie($_GET["id"]);

    header("Location: data.php");
    exit();

}

?>

<br>
<a href="data.php"> Back </a>

<h2>Change information</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="favActor" >Favorite actor</label><br>
	<input id="favActor" name="favActor" type="text" value="<?php echo $i->favActor;?>" ><br><br>
  	<label for="favMov" >Favorite movie</label><br>
	<input id="favMov" name="favMov" type="text" value="<?php echo $i->favMov;?>" ><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
<br>
<a href="?id=<?=$_GET["id"];?>&delete=true">Delete</a>