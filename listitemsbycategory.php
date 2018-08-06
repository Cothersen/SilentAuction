<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
			<link rel="shortcut icon" type="image/x-icon" href="http://katnip.pairserver.com/Project/img/owl2.png"/>
		  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <title>Category List</title>
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

				<div class="form-style-5">
					<fieldset>
						<form action="search.php" method="post">
						<br/>
						<legend><span class="number">1</span>Search Items by Description </legend>
							<input type="text" name="Substr" size="10" placeholder="*Required" value="<?php echo $result['Substr']; ?>">
							<input type="submit" name ="Search" value="Search">
						</form>
					</fieldset>
				</div>
				<br/><br/><hr style="height:1px;border:none;color:#333;background-color:#333;">
				<div class="form-style-5">
					<legend><span class="number">2</span>Live Search Categories </legend>	
					<p><input id="myInput" type="text" placeholder="Search..."></p>
				</div>
					<?php
					function listCategory($CategoryID){
						$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";
						try 
						{
							$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
							$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$sql = "SELECT Item.ItemID, Item.Description, Item.RetailValue, Item.DonorID, Item.LotID, Lot.LotID, Lot.CategoryID FROM Item, Lot";
							$sql .= " WHERE Lot.CategoryID = :CategoryID";
							$sql .= " AND Item.LotID = Lot.LotID";
							$sth = $conn->prepare($sql);
							$sth -> bindParam(':CategoryID', $CategoryID, PDO::PARAM_INT);
							$sth->execute(); //when SELECTing, execute holds a dataset in memory 
							$result = $sth->fetch(PDO::FETCH_ASSOC); //this command brings the first row into a PHP array
							
							
							//Getting category description
							$sql1 = "SELECT CategoryID, Description FROM Category";
							$sql1 .= " WHERE CategoryID = :CategoryID";
							$sth1 = $conn->prepare($sql1);
							$sth1 -> bindParam(':CategoryID', $CategoryID, PDO::PARAM_INT);
							$sth1->execute();
							$result1 = $sth1->fetch(PDO::FETCH_ASSOC);
						}
						catch(PDOException $e)
						{
							echo "SELECT statement failed: <br>" . $e->getMessage();
							echo "<br>SQL Statement was ". $sql;
							
						}
						if($sth->rowCount()==0){ //If nothing in category, leave function
							return;
						}
						if($CategoryID == 0 ){//if category is 0, display that it has no category
							$result1['Description'] = "Not assigned to a category";
						} 
						?>
							<h1> Category <?php echo $CategoryID ." - " . $result1['Description'] ?> </h1>
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
											echo '<td colspan="5"> No Lot Found... </td>';
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
										echo '</tbody>';
										echo "</table>";
									}
									?>
									<?php //place in same container as other tables
										for ($x = 1; $x <= 100; $x++) {
											listCategory($x);
										} 
										listCategory(0);
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