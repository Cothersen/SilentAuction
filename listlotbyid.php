<?php

$validform = true;
$LotIDvalid = true; //if you use a variable in an if statement that was never set to anythig, it will always be false
$formisempty = true;
$IDExists = true;

//POST is an array of values and can be empty.
//if(empty($_POST)) checks to see if any data was submitted, if the form was submitted
//As such, it makes no sense to validate any information if the form hasnt been submitted.
if (empty($_POST)){
	//echo "POST was empty";
} else{
	$formisempty = false;
	$LotID =  htmlspecialchars(stripslashes(trim($_POST['LotID']))); 
	$LotIDerror="";
	if (!preg_match("/^[0-9]*$/",$LotID)){ //part in square brackets is the allowed text - preg_match is if it doesnt math the defined text
	//part in curly braces is the length of text
		$validform = false;
		$LotIDvalid = false;
		$LotIDerror = "The recipe ID must be a number with less than 2147483648";
	} else if($LotID > 2147483648){ //if the entry is too big or too small
		$validform = false;
		$LotIDvalid = false;
		$LotIDerror = "The ID number you entered can't be more than 2147483648.";
	} else if ($LotID<1){
		$validform = false;
		$LotIDvalid = false;
		$LotIDerror = "The ID number you entered can't be zero or less.";
	}
	
	if(!$formisempty and $LotIDvalid){
		$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";
		
		try 
		{
			$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "SELECT LotID FROM Lot WHERE LotID=:LotID ";
			$sth = $conn->prepare($sql);
			$sth -> bindParam(':LotID', $LotID, PDO::PARAM_INT);
			$sth->execute(); 
		}
		catch(PDOException $e)
		{
			echo $sql . "Connection failed: <br>" . $e->getMessage();
			echo "<br>SQL Statement was ". $sql;
		}
		if($sth->rowCount() == 0){
			$IDExists = false;
			$validform = false;
			$IDExistserror = "A Lot with ID ". $LotID . " does not exist <br/>";
			
		}
		$conn = null;
	}

}
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
		//echo "<br />Connected successfully<br />";
	
							
		$sql = "SELECT ItemID, Description, RetailValue, DonorID, LotID FROM Item";
		if(!$formisempty){
			if($validform){
				$sql .= " WHERE LotID = :LotID";//. $LotID; //dont forget that everything a user enters is a parameter
			}
			//echo $sql;
		}
		$sth  = $conn->prepare($sql);
		$sth -> bindParam(':LotID', $LotID, PDO::PARAM_INT); //use to avoid sql injection

		//echo "<br />SQL statement was " .$sql1;
		$sth ->execute(); // when SELECTing, execute holds the datasets in memory of database
		$result = $sth->fetch(PDO::FETCH_ASSOC);  // this command brings the first row fetch into a PHP array
		
	
		}
	catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage() ."<br />";
		echo "<br />SQL statement was " .$sql;
		}
	
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
			<link rel="shortcut icon" type="image/x-icon" href="http://katnip.pairserver.com/Project/img/owl2.png" />
		  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <title>Lot View</title>
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
			
				<h1 style="text-align: center" > Lot <?php echo $LotID; ?> </h1>
				<br/>
				<?php
				//DISPLAYING IMAGE IF IT EXISTS, CHECK ALL EXTENSIONS
				$target_dir = "uploads/";  //Target Directory
				$LotImage = "lot" . $LotID;
				$imageFileType = array(".jpg",".png",".jpeg",".gif"); //put File Extensions into array
				$extensionresult = "";
				foreach($imageFileType as $extension){ //For each extension in array
					$target_file = $target_dir . $LotImage . $extension;
					//echo $target_file;
					if (file_exists($target_file)) { //if file with that extensions exists, return extension
						$extensionresult = $extension;
					}
				}
				$image_path = $target_dir . $LotImage . $extensionresult;
				//echo $image_path;
				if(file_exists($image_path)){ //if file exists, display it
					?><img style="display: block; margin: 0 auto;" src="<?php echo $image_path;?>"><?php
				}
				
				?>
				<!---->
				<hr style="height:1px;border:none;color:#333;background-color:#333;">
				<h2 class="style5">Lot Items</h2>	
				
				<div class="w3-container">
					<div class="w3-responsive">				
					<table class= "w3-table-all w3-hoverable w3-small">
						<thead>
						<tr class="w3-blue">
							<th>Item ID</th>
							<th>Description</th>
							<th>Retail Value</th>
							<th>Donor ID</th>
							<th>LotID</th>
						</tr>	<!-- beginning and ending row tags, element tags in between: table header, table cell -->
						</thead>
						<tbody id="myTable">
						<?php
						if($sth->rowCount()==0){ //no lots found
							echo '<tr>';
							echo '<td colspan="5"> No Items Found... </td>';
							echo '<tr>';
						}
						
						do{
							echo '<tr>';
							echo '<td align="right">'. $result['ItemID'] .'</td>';
							echo '<td>'. $result['Description'] .'</td>';
							echo '<td>'. $result['RetailValue'] .'</td>';
							echo '<td>'. $result['DonorID'] .'</td>';
							echo '<td>'. $result['LotID'] .'</td>';
							echo '';
							echo '</tr>';
						}
						while($result = $sth->fetch(PDO::FETCH_ASSOC)); // want to see more than the first record,  the fetch(PDO) brings the next row
						echo "</table>";
						?>
					</div>	<!-- for responsive -->
				</div> <!-- table container -->
				
				<hr>
				
				<div class="form-style-5">
					<fieldset>
						<legend><span class="number">1</span>Upload / Change Lot Image </legend>
					</fieldset>
				</div>
				<form action="upload.php" method="post" enctype="multipart/form-data">
						<br/>
						
						Select image to upload:
						<input type="file" name="fileToUpload" id="fileToUpload">
						<input type="hidden" name="LotID" size="10" value="<?php echo $LotID; ?>">
						<input type="submit" value="Upload Image" name="submit">
						</form>
				
				<div class="form-style-5">
					<fieldset>
						<form action="biddingsheet.php" method="post">
						<br/>
						<legend><span class="number">2</span>View Bidding Sheet </legend>
							<input type="hidden" name="LotID" size="10" value="<?php echo $LotID; ?>">
							<input type="submit" value="View Bidding Sheet">
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
		?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
			<link rel="shortcut icon" type="image/x-icon" href="http://katnip.pairserver.com/Project/img/owl2.png"/>
		  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <title>Lot View</title>
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
				if($validform == false){
					echo "<span style='color:red;'>Your record was not saved. Some errors were reported.</span><br /><br />";
				} 
				?>
				<div class="form-style-5">
					<fieldset>
						<form action="listlotbyid.php" method="post">
						<br/>
						
						
						
						
						
						<legend><span class="number">1</span>View Lot </legend>
						Enter the LotID of the Lot you want to view: <input type="text" name="LotID" placeholder="*Required" value="<?php echo $LotID; ?>">
						<?php
						//var_dump($rid);
						if($LotIDvalid == false){
							echo "<br/><span style='color:red;'>Error: ". $LotIDerror ."</span><br /><br />";
						}
						if($IDExists == false)
						{
							echo "<br/><span style='color:red;'> Error: ". $IDExistserror ."</span><br>";
						}
						?>
						<input type="Submit"  value="Search">
						</form>
				</div>	<!-- for responsive -->
			</div> <!-- table container -->
			<div id="footer">
				<p class="style5 style6"> Copyright © 2005 | All Rights Reserved  </p>
			</div>
		</div> 
		
		
</body>
</html>