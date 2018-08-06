<?php
	$validform = true;
	$CategoryIDvalid = true; //if you use a variable in an if statement that was never set to 
					//anything, then it will always be false

	$Descriptionvalid = true;
	$validIDform = true;
	
	$formisempty = true;
	$confirmupdate = false; //this will be true when they click submit after changing data
	$recordfound = false; //this will be true when they click submit after entering a valid category id
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
		
		
		$CategoryID =  htmlspecialchars(stripslashes(trim($_POST["CategoryID"]))); 
		$CategoryIDerror="";
		if (!preg_match("/^[0-9]{1,10}$/",$CategoryID)){ //part in square brackets is the allowed text - preg_match is if it doesnt math the defined text
		//part in curly braces is the length of text
			$validform = false;
			$CategoryIDvalid = false;
			$CategoryIDerror = "The category ID must be a number with less than 2147483648";
		} else if($CategoryID > 2147483648){ //if the entry is too big or too small
			$validform = false;
			$CategoryIDvalid = false;
			$CategoryIDerror = "The ID number you entered can't be more than 2147483648.";
		} else if ($CategoryID<1){
			$validform = false;
			$CategoryIDvalid = false;
			$CategoryIDerror = "The ID number you entered can't be zero or less.";
		}

		else if($confirmupdate == false)//nothing is apparently wrong, but I am still nervous
		{ // if they have clicked submit after updating, dont want to just look it up again
		
			$validform = true;
			$CategoryIDvalid = true;
			
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
				$sql = "SELECT CategoryID, Description FROM Category";
				if(!$formisempty)
				{
					if($validform)
					{
						$sql .= " WHERE CategoryID = :CategoryID";
						
					}
					//echo $sql;
				}
				$sth = $conn->prepare($sql);
				$sth -> bindParam(":CategoryID", $CategoryID, PDO::PARAM_INT);
				
				$sth->execute(); //when SELECTing, execute holds a dataset in memory 
				if($sth->rowCount() == 1)
				{
					$result = $sth->fetch(PDO::FETCH_ASSOC); //this command brings the first row into a php array
					$recordfound = true;
					$Description = $result['Description']; //bring value for title from the table
				}
				else
				{
					$validIDform = false;
					$CategoryIDvalid = false;
					$CategoryIDerror = "The category ID you entered is not in the table";
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
		
	}
	
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
			
			$sql = "UPDATE Category SET CategoryID = :CategoryID, Description = :Description WHERE CategoryID = :CategoryID";
		//	$sql = "UPDATE category SET title = :title, rid = :rid WHERE rid = :rid";
			$sth = $conn->prepare($sql);
			$sth -> bindParam(':CategoryID', $CategoryID, PDO::PARAM_INT);
			$sth -> bindParam(':Description', $Description, PDO::PARAM_STR, 75);

			
			$sth->execute(); // comment off so that it isn't actually executed
			
//			echo "New record created successfully";
		}
		catch(PDOException $e)
		{
			echo $sql . "Connection failed: <br>" . $e->getMessage();
		}

		
		header("Location: categorylist.php"); //when redirecting, its best not to have any anything output at all
		die;
	}
	if(($recordfound or !$validform) and $validIDform and $CategoryIDvalid)
	{
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
					<div class="form-style-5">
						<fieldset>
							<?php
								if($validform == false)
								{
									echo "<span style='color:red;'>Your record was not saved. Some errors were reported.</span><br>";
								}
							?>
						
										
							<form action="updatecategory.php" method="post">
							<br/>
							<legend><span class="number">1</span>Modify Category <?php echo $CategoryID; ?></legend>
								<br/>
								Category ID: <?php echo $CategoryID; ?>
								<input type="hidden" name="CategoryID" value="<?php echo $CategoryID; ?>">
								<br/><br/>
								Enter Description: <input type="text" name="Description" placeholder="*Required" value="<?php echo $Description; ?>"><br />
								<?php
								if($Descriptionvalid == false){
									echo "<span style='color:red;'>Error: ". $Descriptionerror ."</span><br /><br />";
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
		  <title>Update Category</title>
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
			
			<body>
				<?php
					if($validform == false)
					{
						echo "<span style='color:red;'>Cannot find record. Some errors were reported.</span><br>";
					}
				?>
				<div class="form-style-5">
					<fieldset>
						<form action="updatecategory.php" method="post">
						<br/>
						<legend><span class="number">3</span>Modify A Category </legend>
						
							Enter the category ID of the Category you want to Modify: <input type="text" name="CategoryID" placeholder="*Required" size="10" value="<?php echo $CategoryID; ?>"><br>
							
							<?php
								if($CategoryIDvalid == false)
								{
									echo "<span style='color:red;'> Error: ". $CategoryIDerror ."</span><br>";
								}
							?>
							<br>
							
					
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

