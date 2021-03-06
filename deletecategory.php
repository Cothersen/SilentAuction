<?php
	$IDExists = true;

	$validform = true;
	$CategoryIDvalid = true; //if you use a variable in an if statement that was never set to 
					//anything, then it will always be false
	$formisempty = true;
	$confirmdelete = false; //use this to keep track of whether they clicked "Yes, please delete"
	if(empty($_POST))
	{
		//echo "POST was empty";
		echo "";
	}
	else
	{
		
		$formisempty = false;
		$CategoryID = htmlspecialchars(stripslashes(trim($_POST["CategoryID"])));
		$CategoryIDerror = "";
		
		if(is_numeric($CategoryID) == false) //used to be if(!preg_match("/^[0-9]{1,10}$/", $rid));
		{
			//max value is 2147483647 so cannot be more than 10 digits
			$validform = false;
			$CategoryIDvalid = false;
			$CategoryIDerror = "The Category ID must be a number with less than 10 digits.";
			//always try to use different error messages so that you can tell what went wrong
		}
		else if($CategoryID > 2147483647)
		{
			$validform = false;
			$CategoryIDvalid = false;
			$CategoryIDerror = "The Category ID must be a number less than 2147483647.";
		}
		else if($CategoryID < 1)
		{
			$validform = false;
			$CategoryIDvalid = false;
			$CategoryIDerror = "The category ID must be a number greater than 0.";
		}
	}
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
		if($sth->rowCount() == 0){
			$IDExists = false;
			$validform = false;
			$IDExistserror = "The Category does not exist.";
			
		}
		$conn = null;
	}
	
	//confirmdelete if statement validation
	if(!$formisempty and $validform)
	{
		$deleteconfirm = htmlspecialchars(stripslashes(trim($_POST["ConfirmDelete"]))); //could have error messages, but choose not to
		//echo $deleteconfirm;
		if($deleteconfirm == "Yes")
		{
			$confirmdelete = true;
		}
		else if($deleteconfirm == "No") //just use else meant that this was true when confirm was ""
		{
			header("Location: categorylist.php"); //command sent to browser to change the location
		}
	}
	
	if(!$formisempty and $validform and $confirmdelete)
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
			echo "Connected successfully<br>";
			
			$sql = "DELETE FROM Category WHERE CategoryID=:CategoryID ";
			$sth = $conn->prepare($sql);
			$sth -> bindParam(':CategoryID', $CategoryID, PDO::PARAM_INT);
			echo "<br>SQL Statement was ". $sql;
			// use exec() because no results are returned
			$sth->execute(); // comment off so that it isn't actually executed
			
			echo "<br>New record deleted successfully";
		}
		catch(PDOException $e)
		{
			echo $sql . "Connection failed: <br>" . $e->getMessage();
			echo "<br>SQL Statement was ". $sql;
		}

		$conn = null;
		
		//echo "<br>Record deleted.";
		header("Location: categorylist.php");
		die;
	}
	
	if(!$formisempty and $validform and !$confirmdelete) //... and have NOT clicked delete
	{
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
		<head>
			<link rel="shortcut icon" type="image/x-icon" href="http://katnip.pairserver.com/Project/img/owl2.png"/>
		  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <title>Delete Category</title>
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
						if($validform == false)
						{
							echo "<span style='color:red;'>Your record was not deleted. Some errors were reported.</span><br>";
						}
					?>
			
					<div class="form-style-5">
					<fieldset>
					<form action="deletecategory.php" method="post">
					<br/>
					<legend><span class="number">2</span>Delete A Category </legend>
						Are you sure you want to delete Category <?php echo $CategoryID; ?><input type="hidden" name="CategoryID" size="10" value="<?php echo $CategoryID; ?>"><br>
						
						<?php
							if($CategoryIDvalid == false)
							{
								echo "<span style='color:red;'> Error: ". $CategoryIDerror ."</span><br>";
							}
							
						?>
						
						<input type="submit" name="ConfirmDelete" value="Yes">
						<input type="submit" name="ConfirmDelete" value="No">
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
		  <title>Delete Existing Category</title>
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
						if($validform == false)
						{
							echo "<span style='color:red;'>Your record was not deleted. Some errors were reported.</span><br>";
						}
					?>
					<div class="form-style-5">
						<fieldset>
							<form action="deletecategory.php" method="post">
								<br/>
								<legend><span class="number">2</span>Delete A Category </legend>
								Enter the CategoryID of the Category you want to delete: <input type="text" name="CategoryID" size="10" placeholder="*Required" value="<?php echo $CategoryID; ?>"><br>
								
								<?php
									if($CategoryIDvalid == false)
									{
										echo "<span style='color:red;'> Error: ". $CategoryIDerror ."</span><br>";
									}
									if($IDExists == false)
									{
										echo "<span style='color:red;'> Error: ". $IDExistserror ."</span><br>";
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