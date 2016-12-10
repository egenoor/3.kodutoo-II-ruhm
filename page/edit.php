<?php
//edit.php
require("../functions.php");
require("../class/Movie.class.php");
$Movie = new Movie($mysqli);
//var_dump($_POST);

//kas kasutaja uuendab andmeid
if(isset($_POST["update"])){

    $Movie->update($Helper->cleanInput($_POST["id"]),$Helper->cleanInput($_POST["movie_actor"]),
        $Helper->cleanInput($_POST["movie_fav"]),$Helper->cleanInput($_POST["movie_genre"]));

    header("Location: edit.php?id=".$_POST["id"]."&success=true");
    exit();

}

        if(!isset($_GET["id"])) {
            header("Location: movies.php");
            exit();
        }

        $c = $Movie->getSingle($_GET["id"]);

        if(isset($_GET["success"])){
            echo "salvestamine õnnestus";
        }

        if(isset($_GET["delete"])){

            delete($_GET["id"]);

            header("Location: movies.php");
            exit();

}

?>

<?php require("../header.php"); ?>

<br>
<a href="movies.php"> Back </a>

<h2>Change information</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="movie_actor" >Favorite actor</label><br>
	<input id="movie_actor" name="movie_actor" type="text" value="<?php echo $c->movie_actor;?>" ><br><br>
  	<label for="movie_fav" >Favorite movie</label><br>
	<input id="movie_fav" name="movie_fav" type="text" value="<?php echo $c->movie_fav;?>" ><br><br>
	<label for="movie_genre" >Favorite genre</label><br>
	<select name="movie_genre">
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
        </select><br><br>
		
	<input type="submit" name="update" value="Salvesta">
  </form>
  
<br>
<a href="?id=<?=$_GET["id"];?>&delete=true">Delete</a>
<?php require("../footer.php"); ?>