<?php
	$validform = true;
	$LotIDvalid = true; //if you use a variable in an if statement that was never set to 
					//anything, then it will always be false
	
	$validIDform = true;
	$Descriptionvalid = true;
	$CategoryIDvalid = true;
	$WinningBidvalid = true;
	$WinningBiddervalid = true;
	$Deliveredvalid = true;
	
	$formisempty = true;
	$confirmupdate = false; //this will be true when they click submit after changing data
	$recordfound = false; //this will be true when they click submit after entering a valid recipe id
	if(empty($_POST))
	{
		//echo "POST was empty";
		echo "";
	}
	else
	{
		$formisempty = false;
		//echo $_POST["ConfirmUpdate"];
		
		if($_POST["ConfirmUpdate"]=="Modify"){
			$confirmupdate = true;
		}
		
		
		$LotID =  htmlspecialchars(stripslashes(trim($_POST["LotID"]))); 
		$LotIDerror="";
		if (!preg_match("/^[0-9]{1,10}$/",$LotID)){ //part in square brackets is the allowed text - preg_match is if it doesnt math the defined text
		//part in curly braces is the length of text
			$validform = false;
			$LotIDvalid = false;
			$LotIDerror = "The Lot ID must be a number with less than 2147483648";
		} else if($LotID > 2147483648){ //if the entry is too big or too small
			$validform = false;
			$LotIDvalid = false;
			$LotIDerror = "The ID number you entered can't be more than 2147483648.";
		} else if ($LotID<1){
			$validform = false;
			$LotIDvalid = false;
			$LotIDerror = "The ID number you entered can't be zero or less.";
		}
		else if($confirmupdate == false)//nothing is apparently wrong, but I am still nervous
		{ // if they have clicked submit after updating, dont want to just look it up again
		
		
		//bascially checks to see if lot exists
			$validform = true;
			$LotIDvalid = true;
			
			$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";
	
			//try block is an entire set of commands you want to succeed. Catch block handles errors.
			try 
			{
				$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				//echo "Connected successfully<br>";
				$sql = "SELECT LotID, Description, CategoryID, WinningBid, WinningBidder, Delivered FROM Lot";
				if(!$formisempty)
				{
					if($validform)
					{
						$sql .= " WHERE LotID = :LotID";
						
					}
					//echo $sql;
				}
				$sth = $conn->prepare($sql);
				$sth -> bindParam(":LotID", $LotID, PDO::PARAM_INT);
				
				$sth->execute(); //when SELECTing, execute holds a dataset in memory 
				if($sth->rowCount() == 1)
				{
					$result = $sth->fetch(PDO::FETCH_ASSOC); //this command brings the first row into a php array
					$recordfound = true;
					$Description = $result['Description']; //bring value for title from the table
					$CategoryID = $result['CategoryID']; 
					$WinningBid = $result['WinningBid']; 
					$WinningBidder = $result['WinningBidder']; 
					$Delivered = $result['Delivered']; 
				}
				else
				{
					$validIDform = false;
					$LotIDvalid = false;
					$LotIDerror = "The Lot ID you entered is not in the table";
				}
			}
			catch(PDOException $e)
			{
				echo "SELECT statement failed: <br>" . $e->getMessage();
				echo "<br>SQL Statement was ". $sql;
			}
		}
	
	}
	
	if(!$recordfound and !$formisempty){
		//var_dump($recordfound);
		
		
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
		
		$CategoryID =  htmlspecialchars(stripslashes(trim($_POST["CategoryID"]))); 
		$CategoryIDerror="";
		if (!preg_match("/^[0-9]{1,10}$/",$CategoryID)){ //part in square brackets is the allowed text - preg_match is if it doesnt math the defined text
		//part in curly braces is the length of text
			$validform = false;
			$CategoryIDvalid = false;
			$CategoryIDerror = "The Category ID must be a number with less than 2147483648";
		} else if($CategoryID > 2147483648){ //if the entry is too big or too small
			$validform = false;
			$CategoryIDvalid = false;
			$CategoryIDerror = "The ID number you entered can't be more than 2147483648.";
		} else if ($CategoryID<1){
			$validform = false;
			$CategoryIDvalid = false;
			$CategoryIDerror = "The ID number you entered can't be zero or less.";
		}
		
		$WinningBid =  htmlspecialchars(stripslashes(trim($_POST["WinningBid"]))); 
		$WinningBiderror="";
		if (!preg_match("/^[0-9]{1,10}+\.[0-9]{2}$/",$WinningBid)){ //part in square brackets is the allowed text - preg_match is if it doesnt math the defined text
		//part in curly braces is the length of text
			$validform = false;
			$WinningBidvalid = false;
			$WinningBiderror = "The Winning Bid must be a number with two decimal places.";
		} else if($WinningBid > 2147483648){ //if the entry is too big or too small
			$validform = false;
			$WinningBidvalid = false;
			$WinningBiderror = "The Winning bid number you entered can't be more than 2147483648.";
		} else if ($WinningBid<1){
			$validform = false;
			$WinningBidvalid = false;
			$WinningBiderror = "The Winning bid number you entered can't be zero or less.";
		}
		
		
		$WinningBidder =  htmlspecialchars(stripslashes(trim($_POST["WinningBidder"]))); 
		$WinningBiddererror="";
		if (!preg_match("/^[0-9]{1,10}$/",$WinningBidder)){ //part in square brackets is the allowed text - preg_match is if it doesnt math the defined text
		//part in curly braces is the length of text
			$validform = false;
			$WinningBiddervalid = false;
			$WinningBiddererror = "The Winning Bidder ID must be a number with less than 2147483648";
		} else if($WinningBidder > 2147483648){ //if the entry is too big or too small
			$validform = false;
			$WinningBiddervalid = false;
			$WinningBiddererror = "The ID number you entered can't be more than 2147483648.";
		} else if ($WinningBidder<1){
			$validform = false;
			$WinningBiddervalid = false;
			$WinningBiddererror = "The ID number you entered can't be zero or less.";
		}
		
		
		$Delivered =  htmlspecialchars(stripslashes(trim($_POST["Delivered"]))); 
		$Deliverederror="";
		if (!preg_match("/^[0-9]{1}$/",$Delivered)){ //part in square brackets is the allowed text - preg_match is if it doesnt math the defined text
		//part in curly braces is the length of text
			$validform = false;
			$Deliveredvalid = false;
			$Deliverederror = "The Delivered field must be a number 0 or 1";
			
		} else if($WinningBidder > 2147483648){ //if the entry is too big or too small
			$validform = false;
			$Deliveredvalid = false;
			$Deliverederror = "The Delivered number you entered can't be more than 2147483648.";
		} else if ($WinningBidder<0){
			$validform = false;
			$Deliveredvalid = false;
			$Deliverederror = "The Delivered number you entered can't be less than 0.";
		}
		
	}
	
	/*var_dump($formisempty);
	var_dump($validform);
	var_dump($recordfound);*/
	if(!$formisempty and $validform and !$recordfound)
	{
		$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";
	

		//try block is an entire set of commands you want to succeed. Catch block handles errors.
		try 
		{
			$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//			echo "Connected successfully<br>";
			
			$sql = "UPDATE Lot SET LotID = :LotID, Description = :Description, CategoryID = :CategoryID, WinningBid = :WinningBid, WinningBidder = :WinningBidder, Delivered = :Delivered WHERE LotID = :LotID";
		//	$sql = "UPDATE recipe SET title = :title, rid = :rid WHERE rid = :rid";
			$sth = $conn->prepare($sql);
			$sth -> bindParam(':LotID', $LotID, PDO::PARAM_INT);
			$sth -> bindParam(':Description', $Description, PDO::PARAM_STR, 75);
			$sth -> bindParam(':CategoryID', $CategoryID, PDO::PARAM_INT);
			$sth -> bindParam(':WinningBid', $WinningBid,  PDO::PARAM_STR);
			$sth -> bindParam(':WinningBidder', $WinningBidder, PDO::PARAM_INT);
			$sth -> bindParam(':Delivered', $Delivered, PDO::PARAM_BOOL);

			$sth->execute(); // comment off so that it isn't actually executed
			
//			echo "New record created successfully";
		}
		catch(PDOException $e)
		{
			echo $sql . "Connection failed: <br>" . $e->getMessage();
		}
		header("Location: lotlist.php"); //when redirecting, its best not to have any anything output at all
		die;
	}
	if(($recordfound or !$validform) and $validIDform and $LotIDvalid) //if record is found to update and the LotID is in the table. 
	{
		?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
			<link rel="shortcut icon" type="image/x-icon" href="http://katnip.pairserver.com/Project/img/owl2.png"/>
		  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <title>Update Lot</title>
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
				<li><a class="active" href="http://cotherse.pairserver.com/Project/lotlist.php">Lots</a></li>
				<li><a href="http://cotherse.pairserver.com/Project/listitemsbycategory.php">Category</a></li>
				<li><a href="http://nhuyn003.pairserver.com/bidderlist.php">Bidder</a><li>
			</ul>
			</div>
			
			<div id="leftcol">
				<h3 class="style5"><br />Lots</h3>
				<div id="navcontainer">
					<ul id="navlist">
					  <li><a href="addlot.php">Add Lot</a></li>
					  <li><a href="deletelot.php">Delete Lot</a></li>
					  <li><a href="updatelot.php">Modify Lot</a></li>
					  <li><a href="listlotbyid.php">View Lot</a></li>
					  <li><a href="biddingsheet.php">Print Bidding Sheet</a></li>
					</ul>
				</div>
			</div>
			
			<div id="content">
				<?php
					if($validform == false)
					{
						echo "<span style='color:red;'>Your record was not saved. Some errors were reported.</span><br>";
					}
				?>
				<div class="form-style-5">
					<fieldset>
						<form action="updatelot.php" method="post">
						<br/>
						<legend><span class="number">1</span>Modify Existing Lot </legend>
							<br/>
							Lot ID: <?php echo $LotID; ?>
							<input type="hidden" name="LotID" value="<?php echo $LotID; ?>">
							<br/><br/>
							Enter Description: <input type="text" name="Description" placeholder="*Required" value="<?php echo $Description; ?>"><br />
							<?php
							if($Descriptionvalid == false){
								echo "<span style='color:red;'>Error: ". $Descriptionerror ."</span><br /><br />";
							}
							?>

							Enter Category ID: <input type="text" name="CategoryID" placeholder="*Required" value="<?php echo $CategoryID; ?>"><br />
							<?php
							if($CategoryIDvalid == false){
								echo "<span style='color:red;'>Error: ". $CategoryIDerror ."</span><br /><br />";
							}
							?>

							Enter WinningBid: <input type="text" name="WinningBid" placeholder="*Required" value="<?php echo $WinningBid; ?>"><br />
							<?php
							if($WinningBidvalid == false){
								echo "<span style='color:red;'>Error: ". $WinningBiderror ."</span><br /><br />";
							}
							?>

							Enter WinningBidder: <input type="text" name="WinningBidder" placeholder="*Required" value="<?php echo $WinningBidder; ?>"><br />
							<?php
							if($WinningBiddervalid == false){
								echo "<span style='color:red;'>Error: ". $WinningBiddererror ."</span><br /><br />";
							}
							?>

							Enter Delivered: <input type="text" name="Delivered" placeholder="*Required" value="<?php echo $Delivered; ?>"><br />
							<?php
							if($Deliveredvalid == false){
								echo "<span style='color:red;'>Error: ". $Deliverederror ."</span><br /><br />";
							}
							?>
							<br>
							<input type="submit" name ="ConfirmUpdate" value="Modify">
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
		<?php
		die;
	}
	else{
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
			<link rel="shortcut icon" type="image/x-icon" href="http://katnip.pairserver.com/Project/img/owl2.png"/>
		  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <title>Update Lot</title>
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
				<li><a class="active" href="http://cotherse.pairserver.com/Project/lotlist.php">Lots</a></li>
				<li><a href="http://cotherse.pairserver.com/Project/listitemsbycategory.php">Category</a></li>
				<li><a href="http://nhuyn003.pairserver.com/bidderlist.php">Bidder</a><li>
			</ul>
			</div>
			
			<div id="leftcol">
				<h3 class="style5"><br />Lots</h3>
				<div id="navcontainer">
					<ul id="navlist">
					  <li><a href="addlot.php">Add Lot</a></li>
					  <li><a href="deletelot.php">Delete Lot</a></li>
					  <li><a href="updatelot.php">Modify Lot</a></li>
					  <li><a href="listlotbyid.php">View Lot</a></li>
					  <li><a href="biddingsheet.php">Print Bidding Sheet</a></li>
					</ul>
				</div>
			</div>
			
			<div id="content">
			
				<?php
					if($validform == false)
					{
						echo "<span style='color:red;'>Cannot find record. Some errors were reported.</span><br>";
					}
				?>
				<div class="form-style-5">
					<fieldset>	
						<form action="updatelot.php" method="post">
						<br/>
						<legend><span class="number">1</span>Modify an Existing Lot </legend>
							Enter the Lot ID of the Lot you want to modify: <input type="text" name="LotID" size="10" placeholder="*Required"value="<?php echo $LotID; ?>"><br>
							<?php
								if($LotIDvalid == false)
								{
									echo "<span style='color:red;'> Error: ". $LotIDerror ."</span><br>";
								}
							?>
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
	<?php
	}
?>

