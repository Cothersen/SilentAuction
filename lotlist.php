<?php
	$formisempty = false;
	$validform = true;
	$LotID =  htmlspecialchars(stripslashes(trim($_POST["Range"])));		
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
	$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";
	try 
	{
		$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		if($_POST["Search"]=="Search" and $validform){
			$sql = "SELECT LotID, Description, CategoryID, WinningBid, WinningBidder, Delivered FROM Lot";
			$sql .= " WHERE LotID = :LotID";
			$sth = $conn->prepare($sql);
			$sth -> bindParam(':LotID', $LotID, PDO::PARAM_INT); //use to avoid sql injection
		}else{
			$sql = "SELECT LotID, Description, CategoryID, WinningBid, WinningBidder, Delivered FROM Lot";
			$sth = $conn->prepare($sql);
		}
		
		
		$sth->execute(); //when SELECTing, execute holds a dataset in memory 
		$result = $sth->fetch(PDO::FETCH_ASSOC); //this command brings the first row into a PHP array
	}
	catch(PDOException $e)
	{
		echo "SELECT statement failed: <br>" . $e->getMessage();
		echo "<br>SQL Statement was ". $sql;
	}
	?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="http://katnip.pairserver.com/Project/img/owl2.png"/>
	  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <title>Lot List</title>
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
			<br /> 
			<p>This is the administrator page related to Lots. </p><br /><br /> <br />
	
			<h2 class="style5">Search Lots</h2>	
			<p><input id="myInput" type="text" placeholder="Search..."></p>
			<br />
			<br /> <br />
			<h2 class="style5">Lot List</h2>	
			
			<div class="w3-container">
				<div class="w3-responsive">				
				<table class= "w3-table-all w3-hoverable w3-small">
					<thead>
					<tr class="w3-blue">
						<th>Lot ID</th>
						<th>Description</th>
						<th>Category ID</th>
						<th>Winning Bid</th>
						<th>Winning Bidder</th>
						<th>Delivered</th>
						<th>View Lot Page</th>
						<th>Modify</th>
						<th>Delete</th>
						<th>View Bidding Sheet</th>
					</tr>	<!-- beginning and ending row tags, element tags in between: table header, table cell -->
					</thead>
					<tbody id="myTable">
	<?php
	if($sth->rowCount()==0){ //no lots found
		echo '<tr>';
		echo '<td colspan="5"> No Lot Found... </td>';
		echo '<tr>';
	}
	else{
		do{
			echo '<tr>';
			echo '<td align="right">'. $result['LotID'] .'</td>';
			echo '<td>'. $result['Description'] .'</td>';
			echo '<td>'. $result['CategoryID'] .'</td>';
			echo '<td>'. $result['WinningBid'] .'</td>';
			echo '<td>'. $result['WinningBidder'] .'</td>';
			echo '<td>'. $result['Delivered'] .'</td>';
			echo '';
			?>
				<td><form action="listlotbyid.php" method="post">
				<input type="hidden" name="LotID" size="10" value="<?php echo $result['LotID']; ?>">
				<button class="submit" class="btn" value="View Items"><i class="fa fa-address-card-o"></i></button>
				<!-- <input type="submit" value="View Items"> -->
				</form></td>
				<td><form action="updatelot.php" method="post">
				<input type="hidden" name="LotID" size="10" value="<?php echo $result['LotID']; ?>">
				<button class="submit" class="btn" value="Update"><i class="fa fa-pencil"></i></button>
				</form></td>
				<td><form action="deletelot.php" method="post">
				<input type="hidden" name="LotID" size="10" value="<?php echo $result['LotID']; ?>">
				<button class="submit" class="btn" value="Delete"><i class="fa fa-trash"></i></button>
				</form></td>
				<td><form action="biddingsheet.php" method="post">
				<input type="hidden" name="LotID" size="10" value="<?php echo $result['LotID']; ?>">
				<button class="submit" class="btn" value="View Bidding Sheet"><i class="fa fa-file-pdf-o"></i></button>
				</form></td>
			<?php
			echo '</tr>';
		}while($result = $sth->fetch(PDO::FETCH_ASSOC)); // want to see more than the first record,  the fetch(PDO) brings the next row
		echo '</tbody>';
		echo "</table>";
	}	
?>
				</div>	<!-- for responsive -->
			</div> <!-- table container -->
		<br />
		</div> 
		<div id="footer">
			<p class="style5 style6"> Copyright © 2005 | All Rights Reserved  </p>
		</div>
	</div>
</body>
</html>