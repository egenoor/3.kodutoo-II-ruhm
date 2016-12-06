<?php

require("functions.php");

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
        $favActor = cleanInput($_POST["favActor"]);
    }

if (isset($_POST["favMov"]) &&
    !empty ($_POST["favMov"])) {
        $favMov = cleanInput($_POST["favMov"]);
        }

if (isset($_POST["movGenre"]) &&
    !empty ($_POST["movGenre"])) {
        $movGenre = cleanInput($_POST["movGenre"]);
    }

$error= "";

if(isset($_POST["favActor"]) &&
    isset($_POST["favMov"]) &&
    isset($_POST["movGenre"]) &&
    !empty($_POST["favActor"]) &&
    !empty($_POST["favMov"]) &&
    !empty($_POST["movGenre"])) {

    saveData($_SESSION["userName"], $_POST["favActor"], $_POST["favMov"], $_POST["movGenre"]);

    } else {

        $error = "Täida kõik väljad";
    }

echo "$error";
//saan filmi andmed
$saveData = getMovieData();


?>



    <!DOCTYPE html>
    <html>
    <body>
    <p><a href="data.php"> <button onclick="goBack()">Go Back</button></a></p>
    <h1>Movies</h1>
    <?=$msg;?>
    <p>
        Welcome <?=$_SESSION["userName"];?>!
        <a href="?logout=1">Log out</a>
    </p>

    <h2> Add data </h2>

    <form method="POST">

        <label>Favorite actor:</label><br>
        <input name="favActor" type="text" value="<?=$favActor;?>">

        <br><br>

        <label>Favorite movie:</label><br>
        <input name="favMov" type="text" value="<?=$favMov;?>">

        <br><br>


        <label>Movie genre:</label><br>
        <select name="movGenre">
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

        <input type="submit" value="Submit">


    </form>
    </body>
    </html>

    <br><br>

    <head>
        <style>
            table, th, td {
                border: 1px solid black;
            }
        </style>
    </head>

<?php

$html = "<table>";

$html .= "<tr>";
$html .="<th>id</th>";
$html .="<th>username</th>";
$html .="<th>actor</th>";
$html .="<th>movie</th>";
$html .="<th>genre</th>";
$html .= "</tr>";

foreach($saveData as $i){

    $html .= "<tr>";
    $html .= "<td>".$i->id."</td>";
    $html .= "<td>".$i->Username."</td>";
    $html .= "<td>".$i->favActor."</td>";
    $html .= "<td>".$i->favMov."</td>";
    $html .= "<td>".$i->movGenre."</td>";
	$html .= "<td><a href='edit.php?id=".$i->id."'>edit.php?id=".$i->id."</a></td>";
    $html .= "</tr>";
}

$html .= "</table>";

echo $html;



?>