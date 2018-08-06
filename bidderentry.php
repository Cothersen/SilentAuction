<?php
	// define variables and set to empty values
	$validform = true;
	$BidderIDvalid = true;
	$formisempty = true;

	if(empty($_POST)){
		//echo "POST was empty";	
	} 
	else{

		//Bidder BidderID VALBidderIDATION
		$formisempty = false;
		$BidderID = htmlspecialchars(stripslashes(trim($_POST["BidderID"])));
		$BidderIDerror="";

		if(!preg_match("/^[0-9]{1,10}$/", $BidderID)){
			$validform = false;
			$BidderIDvalid = false;
			$BidderIDerror ="<br />The Bidder ID must be a number up to 10 digits long. <br />";
			}
		else if($BidderID > 2147483647 ){
			$validform = false;
			$BidderIDvalid = false;
			$BidderIDerror = "<br />The Bidder ID can't be more than 2147483647. <br />"; 
			}
		else if ($BidderID < 1 ) {
			$validform = false;
			$BidderIDvalid = false;
			$BidderIDerror = "<br />The Bidder ID must be 1 or more. <br />";
			}
		else if (preg_match("/\W/", $BidderID)){
			$validform = false;
			$BidderIDvalid = false;
			$BidderIDerror = "<br />The Bidder ID cannot be a special character. <br />";
		}
	
		$Nameerror=" "; 
		$Name = htmlspecialchars(stripslashes(trim($_POST["Name"])));
		if(preg_match("/\d/",$Name)) { 
	
	// special character preg_match("/\w/", string)
		$validform=true; 
		$Namevalid=true;
		$Nameerror=" Error: It is invalid if the name contain a number.";
		///always try to use different error messages so that you can tell what went wrong
		
		// strlen(variablestring) counts the number of characters in the string
	} else if(strlen($Name)<1){
		$validform=false; 
		$Namevalid=false;
		$Nameerror=" Error: The name should have at least 1 characters.";
	} 

	$Address=htmlspecialchars(stripslashes(trim($_POST["Address"])));
	$Addresserror=" "; 
	if(preg_match("/\d/",$Address)) { // if Address contains a numerical digit, then
	// special token d (digits) needs to be escape with a backslash
	// if(preg_match("/\d/",string)) // if it contains a number, then execute if statement otherwise move on
	// if(!preg_match("/\d/",string)) // if it DOES NOT contain a number, execute if statement
	// if(preg_match("/[a-z]/",string))  // if the string contains lower case letter, then execute if statement
	// if(preg_match("/[A-Z]/",string)) // if it contains an UPPER CASE Letter
	// if(preg_match("/[A-Za-z]/",string)) // if it contains a letter (regardless if its upper or lower case)
			// A-z contains at least six special characters from the ASCII table
	// if(preg_match("/\W/",string)) // if it contains a special character
	// if(preg_match("/[!@^]/",string)) // specific characters
	// preg_match [ ] contain
	// preg_match  { } //length
	
	
	// special character preg_match("/\w/", string)
		$validform=false; 
		$Addressvalid=false;
		$Addresserror=" Error: It is invalid if the Address contain a number.";
		///always try to use different error messages so that you can tell what went wrong
		
		// strlen(variablestring) counts the number of characters in the string
	} else if(strlen($Address)<1){
		$validform=false; 
		$Addressvalid=false;
		$Addresserror=" Error: The Address should have at least 1 characters.";
	} 
	
	
	$CellNumber = htmlspecialchars(stripslashes(trim($_POST["CellNumber"])));
		$CellNumbererror="";
		
		if(!preg_match("/^[0-9]{10}$/", $CellNumber)){
			$validform = false;
			$CellNumbervalid = false;
			$CellNumbererror ="<br />the Cell Number must be a 10 digit number. <br />";
			}
			
		$HomeNumber = htmlspecialchars(stripslashes(trim($_POST["HomeNumber"])));
		$HomeNumbererror="";
		
		if(!preg_match("/^[0-9]{10}$/", $HomeNumber)){
			$validform = false;
			$HomeNumbervalid = false;
			$HomeNumbererror ="<br />the Home Number must be a 10 digit number. <br />";
			}
			
			
	$Email=htmlspecialchars(stripslashes(trim($_POST["Email"])));
	$EmailError=" ";  //if E-mail contains a @ symbol, then execute the if statement
	//special token @ 
	//strlen(variable_string) counts the number of characters in the string
	if (!preg_match("/[@]/",$Email)){
		$validform=false; 
		$Emailvalid=false;
		$EmailError=" Error: It should be invalid if the e-mail address does not contains an @ symbol.";
	}else if (strlen($Email)<7){
		$validform=false; 
		$Emailvalid=false;
		$EmailError=" Error: It should be invalid if the e-mail address does not contain at least 7 characters.";
	}
	//echo stripos($Email, "@", start);
	//echo strripos($Email, "@", end);
	if (preg_match("/^@/", $Email)){
	//if (!preg_match("/^[-+\\.0-9=a-z_]+@([-0-9a-z]+\\.)+([0-9a-z]){2,4}$/i", $Email)){
		$validform=false;
		$Emailvalid=false;
		$EmailError="Error: It should be invalid if the e-mail address contains @ at the first character.";
	}
	
	if (preg_match("/[@]$/", $Email)){
	//if (!preg_match("/^[-+\\.0-9=a-z_]+@([-0-9a-z]+\\.)+([0-9a-z]){2,4}$/i", $Email)){
		$validform=false;
		$Emailvalid=false;
		$EmailError="Error: It should be invalid if the e-mail address contains @ at the last character.";
	
	}
	
	
	$Paid = htmlspecialchars(stripslashes(trim($_POST["Paid"])));
		$Paiderror = "";
		
		// ***USE ERROR CHECKING IF ELSES FROM "DELETECustomer.PHP"!!!***
		if(is_numeric($Paid) == false) //used to be if(!preg_match("/^[0-9]{1,10}$/", $ID));
		{
			//max value is 2147483647 so cannot be more than 10 digits
			$validform = false;
			$Paidvalid = false;
			$Paiderror = "The Paid Number must be at least 1 digit.";
			//always try to use different error messages so that you can tell what went wrong
		}
		else if($Paid > 2147483647)
		{
			$validform = false;
			$Paidvalid = false;
			$Paiderror = "The Paid Number must be a number less than 2147483647.";
		}
		else if($Paid < 1)
		{
			$validform = false;
			$Paidvalid = false;
			$Paiderror = "The Paid Number must be a number greater than 0.";
		}
	var_dump($validform);
	} // end of the else statement

	
	$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";

		
		try{
			$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO Bidder (BidderID, Name, Address, CellNumber, HomeNumber, Email, Paid) 
										  VALUES (:BidderID, :Name, :Address, :CellNumber, :HomeNumber, :Email, :Paid)";
			$sth  = $conn->prepare($sql);	
			$sth -> bindParam(':BidderID', $BidderID, PDO::PARAM_INT);
			$sth -> bindParam(':Name', $Name, PDO::PARAM_STR, 75 );
			$sth -> bindParam(':Address', $Address, PDO::PARAM_STR, 75);
			$sth -> bindParam(':CellNumber', $CellNumber, PDO::PARAM_STR, 10);
			$sth -> bindParam(':HomeNumber', $HomeNumber, PDO::PARAM_STR, 10);
			$sth -> bindParam(':Email', $Email, PDO::PARAM_STR, 200);
			$sth -> bindParam(':Paid', $Paid, PDO::PARAM_BOOL);	
			$sth ->execute();
		}		
		catch(PDOException $e){
			echo "Connection failed: " . $e->getMessage();
			echo "<br />SQL statement was " .$sql;
			}
																																										
	 // end of the if statement 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <title>Add Bidder</title>
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
				<li><a href="http://cotherse.pairserver.com/Project/listitemsbycategory.php">Category</a></li>
				<li><a class="active" href="http://nhuyn003.pairserver.com/bidderlist.php">Bidder</a><li>
			</ul>
			</div>
			
			<div id="leftcol">
				<h3 class="style5"><br /></h3>
				<div id="navcontainer">
					<ul id="navlist">
					  <li><a href="bidderentry.php">Add Bidder</a></li>
					  <li><a href="bidderdelete.php">Delete Bidder</a></li>
					  <li><a href="bidderupdate.php">Modify Bidder</a></li>
					  <li><a href="AuctionReport.php">View Report</a></li>
					</ul>
				</div>
			</div>
			<div id="content">
				<br />
				<div class="form-style-5">
					<fieldset>
					<form action="bidderentry.php" method="post">
					<legend><span class="number">1</span>Add A New Bidder </legend>
						<?php
							if ($validform == false){echo "<center><span style='color:red;'><br />Some errors are reported and record wasn't deleted.</span> </center><br />";}
						?>
						Enter the Bidder ID:  <input type="text" name="BidderID" value="<?php echo $BidderID?>"><br />
						<?php 
										if ($BidderIDvalid == false) {	echo "<span style='color:red;'>". $BidderIDerror ."</span>";	}
									?>
						Enter the Bidder Name:  <input type="text" name="Name" value="<?php echo $Name?>"><br />
						<?php 
										if ($Namevalid == false) {	echo "<span style='color:red;'>". $Nameerror ."</span>";	}
									?>
						Enter the Bidder Address:  <input type="text" name="Address" value="<?php echo $Address?>"><br />
						<?php 
										if ($Addressvalid == false) {	echo "<span style='color:red;'>". $Addresserror ."</span>";	}
									?>
						
						Enter the Bidder Cell Number:  <input type="text" name="CellNumber" value="<?php echo $CellNumber?>"><br />
						<?php 
										if ($CellNumbervalid == false) {	echo "<span style='color:red;'>". $CellNumbererror ."</span>";	}
									?>
						
						Enter the Bidder Home Number:  <input type="text" name="HomeNumber" value="<?php echo $HomeNumber?>"><br />
						<?php 
										if ($HomeNumbervalid == false) {	echo "<span style='color:red;'>". $HomeNumbererror ."</span>";	}
									?>
						Enter the Bidder Email:  <input type="text" name="Email" value="<?php echo $Email?>"><br />
							<?php 
										if ($Emailvalid == false) {	echo "<span style='color:red;'>". $Emailerror ."</span>";	}
									?>
									
						Paid: <input type="radio" name="Minor" value="No">No <input type="radio" name="Minor" value="Yes">Yes<br>			
						<input type="submit">
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
	