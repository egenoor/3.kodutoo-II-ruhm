<?php
class Movie{

        private $connection;
        function __construct($mysqli){
            $this->connection = $mysqli;
        }

        function delete($id){
            $stmt = $this->connection->prepare("UPDATE user_movies SET deleted=NOW() WHERE id=? AND deleted IS NULL");
            $stmt->bind_param("i", $id);

            // kas õnnestus salvestada
            if ($stmt->execute()) {
                // õnnestus
                echo "Deleted";
            }

            $stmt->close();

        }

        function get($q, $sort, $direction){

            //mis sort ja j�rjekord
            $allowedSortOptions = ["id", "username", "movie_actor", "movie_fav", "movie_genre"];
            //kas sort on lubatud valikute sees
            if (!in_array($sort, $allowedSortOptions)){
                    $sort = "id";
            }
            echo "Sorteerin: " .$sort. " ";

            //turvaliselt luban ainult 2 valikut
            $orderBy = "ASC";
            if($direction == "descending"){
                    $orderBy = "DESC";
            }
            echo "Order: " .$orderBy." ";

            if ($q == ""){

                    echo "ei otsi";

                    $stmt = $this->connection->prepare("
                        SELECT id, username, movie_actor, movie_fav, movie_genre
                        FROM user_movies
                        WHERE deleted IS NULL 
                        ORDER BY $sort $orderBy
                     ");

            } else {

                    echo "Searches: " .$q;

                    //teen otsis�na
                    // lisan m�lemale poole %
                    $searchword = "%".$q."%";

                    $stmt = $this->connection->prepare("
                        SELECT id, username, movie_actor, movie_fav, movie_genre
                        FROM user_movies
                        WHERE deleted IS NULL AND
                        (id LIKE ? OR username LIKE ?)
                        ORDER BY $sort $orderBy
                    ");
                    $stmt->bind_param("ss", $searchword, $searchword);

            }


            $stmt->bind_result($id, $userName, $favActor, $favMov, $movGenre);
            $stmt->execute();


            //tekitan massiivi
            $result = array();

            // tee seda seni, kuni on rida andmeid
            // mis vastab select lausele
            while ($stmt->fetch()) {

                //tekitan objekti
                $movie = new StdClass();

                $movie->id = $id;
                $movie->username = $userName;
                $movie->favActor = $favActor;
                $movie->favMov = $favMov;
                $movie->favGenre = $movGenre;

                //echo $plate."<br>";
                // iga kord massiivi lisan juurde nr m�rgi
                array_push($result, $movie);
            }

            $stmt->close();


            return $result;
        }

        function getSingle($edit_id){
            $stmt = $this->connection->prepare("SELECT movie_actor, movie_fav, movie_genre FROM user_movies WHERE id=? 
                AND deleted IS NULL");
            //et näha, mis error on koodis täpsemalt kirjutame echo error
            echo $this->connection->error;
            $stmt->bind_param("i", $edit_id);
            $stmt->bind_result($favActor, $favMov, $movGenre);
            $stmt->execute();

            //tekitan objekti
            $movie = new Stdclass();

            if ($stmt->fetch()) {
                $movie->movie_actor = $favActor;
                $movie->movie_fav = $favMov;
                $movie->movie_genre = $movGenre;


            } else {

                header("Location: movies.php");
                exit();
            }

            $stmt->close();

            return $movie;

        }

        function save($userName, $favActor, $favMov, $movGenre){

            $stmt = $this->connection->prepare("INSERT INTO user_movies(username, movie_actor, movie_fav, movie_genre) VALUES (?, ?, ?, ?)");


            $stmt->bind_param("ssss", $userName, $favActor, $favMov, $movGenre);

            if ($stmt->execute()) {
                echo "Saved!";
            } else {
                echo "ERROR " . $stmt->error;
            }

            $stmt->close();
            $this->connection->close();

        }


        function cleanInput($input){

            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);

            return $input;


        }

        function update($id, $favActor, $favMov, $movGenre){
            $stmt = $this->connection->prepare("UPDATE user_movies SET movie_actor=?, movie_fav=?, movie_genre=? WHERE id=? 
                    AND deleted IS NULL");

            $stmt->bind_param("sssi", $favActor, $favMov, $movGenre, $id);

            // kas õnnestus salvestada
            if ($stmt->execute()) {
                // õnnestus
                echo "salvestus õnnestus!";
            }

            $stmt->close();

        }
}
?>
