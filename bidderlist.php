<?php

$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";

	try {
		$conn = new PDO("mysql:host=$servername; dbname=$databasename", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "Connected successfully<br />"; 
		//$ID = 1;
		$sql = "SELECT BidderID, Name, Address, CellNumber, HomeNumber, Email, Paid"; //join two tables together? 
		$sql .= " FROM Bidder"; //need to identify where fields come from
		$sql .= " ORDER BY BidderID";
		$sth = $conn->prepare($sql);
		//$sth -> bindParam(':ID', $ID, PDO::PARAM_INT); //because no criteria, don't need parameters
		//$sth -> bindParam(':title', $title, PDO::PARAM_STR, 75);
		//echo "<br />SQL statement was ". $sql;
		$sth->execute(); //when SELECTing, execute holds a dataset in memory of database
		$result = $sth->fetch(PDO::FETCH_ASSOC); //this command brings the first row into a PHP array.
	}
	catch(PDOException $e) {
		echo "SELECT Statement failed: " . $e->getMessage();
		echo "<br />SQL statement was ". $sql;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
	  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <title>Bidder List</title>
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	  <link href="http://katnip.pairserver.com/Project/style.css" rel="stylesheet" type="text/css" />
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
			<p>This is the administrator page related to Bidders. </p><br /><br /> <br />

			<br />
			
			<form action="bidderlist.php" method="post">
				<p>
				<input type="text" name="Range" size="10" value="<?php echo $result['Range']; ?>"> 
				<input type="submit" name ="Search" value="Search"> 
				</p>
			</form>
			<br /> <br />
			<h2 class="style5">Bidder List</h2>	
			
			<div class="w3-container">
				<div class="w3-responsive">				
				<table class= "w3-table-all w3-hoverable ">
					<thead>
					<tr class="w3-blue">
						<th>Bidder ID</th>
						<th>Name</th>
						<th>Address</th>
						<th>Cell Number</th>
						<th>Home Number</th>
						<th>Email</th>
						<th>Paid</th>
						<th>Update</th>
						<th>Delete</th>
					</tr>	<!-- beginning and ending row tags, element tags in between: table header, table cell -->
					</thead>	
				<?php
					
					do { //want to see more than the first record, can use for..each
						echo '<tr>';
						echo '<td align="right">'. $result['BidderID'] .'</td>';
						echo '<td>'. $result['Name'] .'</td>';
						echo '<td>'. $result['Address'] .'</td>';
						echo '<td>'. $result['CellNumber'] .'</td>';
						echo '<td>'. $result['HomeNumber'] .'</td>';
						echo '<td>'. $result['Email'] .'</td>';
						echo '<td>'. $result['Paid'] .'</td>';
						echo '';
					?>
						<td width="6%>><form action="bidderupdate.php" method="post">
						<input type="hidden" name="ID" value="<?php echo $result['ID']; ?>">
						<button class="submit" class="btn" value="UPDATE"><i class="fa fa-pencil"></i></button>
						</form></td>
						<td width="6%>><form action="bidderdelete.php" method="post">
						<input type="hidden" name="ID" value="<?php echo $result['ID']; ?>">
						<button class="submit" class="btn" value="DELETE"><i class="fa fa-trash"></i></button>
						</form></td>
					<?php
						echo '</tr>';
					}   while($result = $sth->fetch(PDO::FETCH_ASSOC)); //bring the next row into the array
						echo '</table>'
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

