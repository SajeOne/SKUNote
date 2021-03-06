<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/png" href="images/favicon.png"/>
		<link rel="stylesheet" type="text/css" href="layout.css"/>
		<link rel="stylesheet" type="text/css" href="addnote.css"/>
		<title>SKU Note - Add Note</title>
	</head>

	<body>
		<a id="header" href="./"><h1>SKU Note</h1></a>
		<?php
			require_once("private/dbhandler.php");
			require_once("private/util.php");

			// Create SQL connection from saved login details
			$handler = new DBHandler("private/mysql.ini");

			// Check all inputs
			if((isset($_POST['sku']) && !empty($_POST['sku']))
				&& isset($_POST['user']) && !empty($_POST['user'])
				&& isset($_POST['note']) && !empty($_POST['note'])){

				// Convert posted values to variables
				$sku = ltrim($_POST['sku'], '0');
				$user = $_POST['user'];
				$note = $_POST['note'];

				$division = "default";
				if(isset($_POST['division']) && !empty($_POST['division'])){
					$division = $_POST['division'];
				}

				if($handler->add_note($sku, $user, $note, $division)){
					echo "<h3>Successfully Added Note.</h3>";
				}else{
					echo "<h3>Failed to Add Note.</h3>";
				}
			}else{
				// No submission, display form
				if(SKUtil::divisions_enabled()){
					$divisions = $handler->retrieve_divisions();

					$cookie_division = "";
					if(isset($_COOKIE['division']) && !empty($_COOKIE['division'])){
						$cookie_division = $_COOKIE['division'];
					}
					echo '<div class="container"><form method="POST" action="addnote.php">
							<label for="sku">SKU: </label>
							<input type="text" name="sku"/><br/>
							<label for="user">Employee: </label>
							<input type="text" name="user"/><br/>
							<label for="note">Note: </label>
							<textarea  name="note" rows="4" cols="48"></textarea><br/>
							<label for="division">Division: </label>
							<select name="division">';
					foreach($divisions as $item){
							if(strcmp($item, $cookie_division) == 0){
								echo '<option value="' . $item . '" selected>' . $item . '</option>';
							}else{
								echo '<option value="' . $item . '">' . $item . '</option>';
							}
					}
					echo '</select>
							<input type="submit" name="submit" value="Submit Note"/>
						</form></div>';
				}else{
					echo '<div class="container"><form method="POST" action="addnote.php">
							<label for="sku">SKU: </label>
							<input type="text" name="sku"/><br/>
							<label for="user">Employee: </label>
							<input type="text" name="user"/><br/>
							<label for="note">Note: </label>
							<textarea  name="note" rows="4" cols="48"></textarea><br/>
							<input type="submit" name="submit" value="Submit Note"/>
						</form></div>';
				}

				// Submit pressed with insufficient field completion
				if(isset($_POST['submit']))
					echo "<h3>Please fill all fields.</h3>";
			}

			echo '<a id="backbtn" class="btn" href="./">Back</a>';
		?>
	</body>
</html>
