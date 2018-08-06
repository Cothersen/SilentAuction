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
			<p>This is the Upload results page. </p><br /><br /> <br />
				<?php
				$validform = true;
				if (empty($_POST)){
					//echo "POST was empty";

				} else{
					$LotID =  htmlspecialchars(stripslashes(trim($_POST['LotID']))); 
					$LotIDerror="";
					if (!preg_match('/^[0-9]{1,10}$/',$LotID)){ //part in square brackets is the allowed text - preg_match is if it doesnt math the defined text
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

					if($validform = true){

						$target_dir = "uploads/";
						$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
						
						$uploadOk = 1;
						$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
						$target_file = $target_dir . "lot" . $LotID . "." . $imageFileType; //Change File name
						//echo $target_file . "-------------";
						// Check if image file is a actual image or fake image
						if(isset($_POST["submit"])) {
							$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
							if($check !== false) {
								echo "File is an image - " . $check["mime"] . ".<br/>";
								$uploadOk = 1;
							} else {
								echo "File is not an image.<br/>";
								$uploadOk = 0;
							}
						}
						// Check if file already exists
						/*
						if (file_exists($target_file)) {
							echo "Sorry, file already exists.";
							$uploadOk = 0;
						}
						*/
						// Check file size
						if ($_FILES["fileToUpload"]["size"] > 500000) {
							echo "Sorry, your file is too large.<br/>";
							$uploadOk = 0;
						}
						// Allow certain file formats
						if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
						&& $imageFileType != "gif" ) {
							echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br/>";
							$uploadOk = 0;
						}
						// Check if $uploadOk is set to 0 by an error
						if ($uploadOk == 0) {
							echo "Sorry, your file was not uploaded.<br/>";
						// if everything is ok, try to upload file
						} 
						else {
							//echo "<br\>". $target_file . "<br\>";
							if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
								echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded and renamed to " . "lot" . $LotID . "." . $imageFileType;
							} else {
								echo "Sorry, there was an error uploading your file.<br/>";
							}
						}
					}
					
				}
				?>
					<br/><br/>
					<div class="form-style-5">
						<fieldset>
							<form action="listlotbyid.php" method="post">
								<br/>
									<legend><span class="number">1</span>View Lot </legend>
								<input type="hidden" name="LotID" size="10" value=<?php echo $LotID; ?>>
								<input type="submit" value="View Lot">
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