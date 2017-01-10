<?php

	require("/home/egenoor/config.php");
	require("../functions.php");
	
	require("../class/User.class.php");
		$User = new User($mysqli);

	if (isset($_SESSION["userId"])) {

		//suunan sisselogimise lehele
		header("Location: movies.php");
		exit();
	}

	//MUUTUJAD
	$loginUsername = "";
	$loginUsernameError = "";
	$signupUsernameError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$signupPassword = "";
	$signupUsername = "";
	$signupEmail = "";
	$signupGender = "";
	$signupWebsite = "";
	$signupAge = "";


	if(isset($_POST["signupUsername"])){

		if(empty($_POST["signupUsername"])){

			$signupUsernameError = "Sisesta kasutajanimi";
		} else {
			$signupUsername = $_POST["signupUsername"];
		}
	}

	// kas e-post oli olemas
	if ( isset ( $_POST["signupEmail"] ) ) {

		if ( empty ( $_POST["signupEmail"] ) ) {

			// oli email, kuid see oli tühi
			$signupEmailError = "See väli on kohustuslik!";

		} else {

			//email olemas
			$signupEmail = $_POST["signupEmail"];

		}

	}


	if ( isset ( $_POST["signupPassword"] ) ) {

		if ( empty ( $_POST["signupPassword"] ) ) {

			// oli password, kuid see oli tühi
			$signupPasswordError = "See väli on kohustuslik!";

		} else {

			// tean et parool on ja see ei olnud tühi
			// VÄHEMALT 8

			if ( strlen($_POST["signupPassword"]) < 8 ) {

				$signupPasswordError = "Parool peab olema vähemalt 8 tähemärkki pikk";

			}

		}


	}

	if ( isset ( $_POST["signupAge"] ) &&
		!empty ( $_POST["signupAge"] )) {

		$signupAge = $_POST["signupAge"];
	}

	if ( isset ( $_POST["signupGender"] ) ) {

		if (!empty ( $_POST["signupGender"] ) ) {


			$signupGender = $_POST["signupGender"];

		}

	}

	if ( isset ( $_POST["signupWebsite"] ) ) {

		if (!empty ( $_POST["signupWebsite"] ) ) {


			$signupWebsite = $_POST["signupWebsite"];

		}

	}


	//peab olema email ja parool
	//ja ühtegi errorit

	if ( isset($_POST["signupUsername"]) &&
	    isset($_POST["signupEmail"]) &&
		isset($_POST["signupPassword"]) &&
		isset($_POST["signupWebsite"]) &&
		isset($_POST["signupAge"]) &&
		$signupEmailError == "" &&
		empty ($signupPasswordError)) {

		//salvestame ab'i
		echo "Saving...<br>";

		$password = hash("sha512", $_POST["signupPassword"]);

		$User->signUp($Helper->cleanInput($_POST['signupUsername']), $Helper->cleanInput($_POST['signupEmail']),
		$Helper->cleanInput ($password),$Helper->cleanInput($_POST['signupAge']),$Helper->cleanInput($_POST['signupWebsite']));



	}

	$error ="";
	if (isset($_POST["loginUsername"]) &&
		isset($_POST["loginPassword"]) &&
		!empty($_POST["loginUsername"]) &&
		!empty($_POST["loginPassword"])) {

		$error = $User->login($Helper->cleanInput($_POST["loginUsername"]), 
		$Helper->cleanInput($_POST["loginPassword"]));


	}
	?>

<?php require("../header.php"); ?>

<div class="container">

    <div class="row">

        <div class="col-sm-3">

                <h1>Log in</h1>

                <form method="POST">

                    <p style="color:red;"><?=$error;?></p>
                    <label>Username:</label><br>
                    <input name="loginUsername" type="text" class="form-control" value="<?php if(isset($_POST["loginUsername"]))
                    { echo $_POST['loginUsername'];}?>">
                    <?php echo $loginUsernameError; ?>
                    <br><br>
                    <input name="loginPassword" type="password" class="form-control" placeholder="Password">
                    <br><br>
                    <input class="btn btn-default btn-sm" type="submit" value="Log in">

                </form>
        </div>

        <div class="col-sm-3 col-sm-offset-3">

            <h1>Create account</h1>
                <form method="POST">

                    <label>Username:</label><br>
                    <input name="signupUsername" type="text" class="form-control" value="<?=$signupUsername;?>">
                    <?php echo $signupUsernameError; ?>

                    <br><br>

                    <label>Email:</label><br>
                    <input name="signupEmail" type="text" class="form-control" value="<?=$signupEmail;?>">
                    <?php echo $signupEmailError; ?>

                    <br><br>

                    <input name="signupPassword" type="password" class="form-control" placeholder="Password">
                    <?php echo $signupPasswordError; ?>

                    <br><br>

                    <label>Website:</label><br>
                    <input name="signupWebsite" type="text" class="form-control" value="<?=$signupWebsite;?>">

                    <br><br>

                    <label>Age:</label><br>
                    <input name="signupAge" type="number" class="form-control" value="<?=$signupAge;?>">

                    <br><br>
                    <label>Gender:</label><br>

                    <?php if($signupGender == "male") { ?>
                        <input type="radio" name="signupGender" value="male" checked> Male<br>
                    <?php }else { ?>

                        <input type="radio" name="signupGender" value="male"> Male<br>
                    <?php } ?>

                    <?php if($signupGender == "female") { ?>
                        <input type="radio" name="signupGender" value="female" checked> Female<br>
                    <?php }else { ?>
                        <input type="radio" name="signupGender" value="female"> Female<br>
                    <?php } ?>

                    <br>

                    <input class="btn btn-default btn-sm" type="submit" value="Create account">
                </form>
        </div>
    </div>

</div>

<?php require("../footer.php"); ?>