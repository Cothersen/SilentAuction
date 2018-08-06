<?php

$validform = true;
$CategoryIDvalid = true; //if you use a variable in an if statement that was never set to anything, it will always be false
$Descriptionvalid = true;
$IDExists = false;


$formisempty = true;

//POST is an array of values and can be empty.
//if(empty($_POST)) checks to see if any data was submitted, if the form was submitted
//As such, it makes no sense to validate any information if the form hasnt been submitted.
if (empty($_POST)){
	//echo "POST was empty";
} else{
	$formisempty = false;
	
	$CategoryID =  htmlspecialchars(stripslashes(trim($_POST["CategoryID"]))); 
	$CategoryIDerror="";
	if (!preg_match("/^[0-9]{1,10}$/",$CategoryID)){ //part in square brackets is the allowed text - preg_match is if it doesnt math the defined text
	//part in curly braces is the length of text
		$validform = false;
		$CategoryIDvalid = false;
		$CategoryIDerror = "The Lot ID must be a number with less than 2147483648";
	} else if($CategoryID > 2147483648){ //if the entry is too big or too small
		$validform = false;
		$CategoryIDvalid = false;
		$CategoryIDerror = "The ID number you entered can't be more than 2147483648.";
	} else if ($CategoryID<1){
		$validform = false;
		$CategoryIDvalid = false;
		$CategoryIDerror = "The ID number you entered can't be zero or less.";
	}

	$Description =  htmlspecialchars(stripslashes(trim($_POST["Description"]))); 
	$Descriptionerror="";
	if (strlen($Description)<1){
		$validform = false;
		$Descriptionvalid = false;
		$Descriptionerror = "The Description can't be empty.";
	}
	else if (strlen($Description)>75){
		$validform = false;
		$Descriptionvalid = false;
		$Descriptionerror = "The Description you entered can't greater than 75 characters.";
	}
	//check if ID exists
	if(!$formisempty and $CategoryIDvalid){
		$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";
		
		try 
		{
			$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "SELECT CategoryID FROM Category WHERE CategoryID=:CategoryID ";
			$sth = $conn->prepare($sql);
			$sth -> bindParam(':CategoryID', $CategoryID, PDO::PARAM_INT);
			$sth->execute(); 
		}
		catch(PDOException $e)
		{
			echo $sql . "Connection failed: <br>" . $e->getMessage();
			echo "<br>SQL Statement was ". $sql;
		}
		if($sth->rowCount() == 1){
			$IDExists = true;
			$validform = false;
			$IDExistserror = "A Category with ID ". $CategoryID . " already exists <br/>";
			
		}
		$conn = null;
	}
}
///Connecting to sql database
if(!$formisempty and $validform){
	$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";
	//try block is an entire set of commands you want to succeed. catch block handles errors
	try {

		
		$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connected successfully"; 

		$sql = "INSERT INTO Category (CategoryID, Description) VALUES (:CategoryID, :Description)";
		$sth = $conn->prepare($sql);
		$sth -> bindParam(':CategoryID', $CategoryID, PDO::PARAM_INT);
		$sth -> bindParam(':Description', $Description, PDO::PARAM_STR, 75);
		echo "<br/>SQL statement was " . $sql;
		$sth->execute();
		echo "<br/>New record created successfully";
	}
	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		echo "<br/> SQL statement was ". $sql;
		}
	
	header("Location: categorylist.php"); //when redirecting, its best not to have any anything output at all
	die;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
			<link rel="shortcut icon" type="image/x-icon" href="http://katnip.pairserver.com/Project/img/owl2.png"/>
		  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <title>Add New Category</title>
		  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		  <link href="http://katnip.pairserver.com/Project/style.css" rel="stylesheet" type="text/css" />
		 
		  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<script>
			$(document).ready(function(){
			  $("#myInput").on("keyup", function() {
				var value = $(this).val().toLowerCase();
				$("#myTable tr").filter(function() {
				  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			  });
			});
			</script>
		</head>
		<body>
		<div id="contain">
			<div id="header">
				<h1 class="headerFont">Silent "Owl"ction</h1>
			</div>
			<div class="topnav">
			<ul>
				<li><a href="http://katnip.pairserver.com/Project/index.html">Home</a></li>
				<li><a href="http://katnip.pairserver.com/Project/listDonors.php">Donors</a></li>
				<li><a href="http://katnip.pairserver.com/Project/listItems.php">Items</a></li>
				<li><a href="http://cotherse.pairserver.com/Project/lotlist.php">Lots</a></li>
				<li><a class="active" href="http://cotherse.pairserver.com/Project/listitemsbycategory.php">Category</a></li>
				<li><a href="http://nhuyn003.pairserver.com/bidderlist.php">Bidder</a><li>
			</ul>
			</div>
			
			<div id="leftcol">
				<h3 class="style5"><br />Categories</h3>
				<div id="navcontainer">
					<ul id="navlist">
						<li><a href="categorylist.php">View Categories</a></li>
						<li><a href="addcategory.php">Add Category</a></li>
						<li><a href="deletecategory.php">Delete Category</a></li>
						<li><a href="updatecategory.php">Modify Category</a></li>
					</ul>
				</div>
			</div>
			
			<div id="content">
				<?php
				if($validform == false){
					echo "<span style='color:red;'>Your record was not saved. Some errors were reported.</span><br /><br />";
				} 
				?>
				<div class="form-style-5">
					<fieldset>
						<form action="addcategory.php" method="post">
						<br/>
						<legend><span class="number">1</span>Add a New Category</legend>
						Enter the Category ID: <input type="text" name="CategoryID" placeholder="*Required" value="<?php echo $CategoryID; ?>"><br />
						<?php
						if($CategoryIDvalid == false){
							echo "<span style='color:red;'>Error: ". $CategoryIDerror ."</span><br /><br />";
						}
						if($IDExists == true)
						{
							echo "<span style='color:red;'> Error: ". $IDExistserror ."</span><br>";
						}

						?>
						Enter Description: <input type="text" name="Description" placeholder="*Required" value="<?php echo $Description; ?>"><br />
						<?php
						if($Descriptionvalid == false){
							echo "<span style='color:red;'>Error: ". $Descriptionerror ."</span><br /><br />";
						}
						?>

						<input type="Submit">
						</form>
					</fieldset>
				</div>
			</div>
			<div id="footer">
				<p class="style5 style6"> Copyright © 2005 | All Rights Reserved  </p>
			</div>
		</div>
</body>
</html>