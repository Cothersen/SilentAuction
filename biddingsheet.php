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
		$LotIDerror = "The ID number you entered can't be zero or less. " . $LotID;
	}
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


if(!$formisempty and $validform){
	$servername = "";
		$databasename="";
		$username = ""; 
		$password = "";
	//try block is an entire set of commands you want to succeed. catch block handles errors
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

		$sql = "SELECT Item.ItemID, Item.Description, Item.RetailValue, Item.DonorID, Item.LotID, Donor.DonorID, Donor.BusinessName FROM Item, Donor";
	
		if(!$formisempty){
			if($validform){
				$sql .= " WHERE Item.LotID = :LotID"; //dont forget that everything a user enters is a parameter
				$sql .= " AND Item.DonorID = Donor.DonorID";
			}
		}
		$sth  = $conn->prepare($sql);
		$sth -> bindParam(':LotID', $LotID, PDO::PARAM_INT); //use to avoid sql injection
		$sth ->execute(); // when SELECTing, execute holds the datasets in memory of database
		$result = $sth->fetch(PDO::FETCH_ASSOC);  // this command brings the first row fetch into a PHP array
	
		}
	catch(PDOException $e){
		echo "Connection failed: " . $e->getMessage() ."<br />";
		echo "<br />SQL statement was " .$sql;
		}

	

//echo $LotID;
//echo $result['Description'];
//echo $DonorResult['BusinessName'];

?>

<?php
 //Look up FPDF Documentary to understand http://www.fpdf.org/
require('FPDF/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
class PDF extends FPDF
{
// Page header

function Header() //Automatically called when pdf is created
{
}
function Info($LotID, $Description, $Donator, $RetailValue){ //must be called


	$this->SetFont('Arial','',15);
	// Move to the right
    $this->Cell(80); //A cell is a rectangular area, possibly framed, which contains a line of text. 
					//It is output at the current position. We specify its dimensions, its text (centered or aligned), if borders should be drawn, 
					//and where the current position moves after it (to the right, below or to the beginning of the next line).
	// Title
    $this->Cell(30,10,'W.H. Taylor Elementary PTA',0,0,'C');
	$this->Ln(5);
	
	$this->Cell(80);
	$this->Cell(30,10,'2014 Silent Auction',0,0,'C');


	$this->SetFont('Arial','',15);
	//Lot Number
	$this->Ln(15);
	$this->Cell(160);
	$this->Cell(30,10,"Lot # ". $LotID,0,0,'R');
	
	//Item Description
	$this->Ln(15);
	$this->Cell(1);
	$this->Cell(30,10,"Item Description: ". $Description,0,0,'L');
	
	//Donated By
	$this->Ln(15);
	$this->Cell(1);
	$this->Cell(30,10,"Donated By: ". $Donator,0,0,'L');
	
	//RetailValue
	$this->Ln(0);
	$this->Cell(160);
	$this->Cell(30,10,"Retail Value: ". $RetailValue,0,0,'R');
	
	//Minimum Increment
	$this->SetFont('Arial','',10);
	$this->Ln(15);
	$this->Cell(80);
	$this->Cell(30,10,"Minimum $5 Increment",0,0,'C');
	$this->Ln(8);
}
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col) //for each item in $header, read it as $col
        $this->Cell(48,10,$col,1);
    $this->Ln();
	
	foreach($data as $row) //for each item in $Outerarray, read it as a row
    {
		foreach($row as $col){ //for each row, read each item as a column
			$this->Cell(48,10,$col,1);	
		}
		$this->Ln();
    }
   
}
}

$pdf = new PDF();
//echo $pages;
do{
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	$pdf->Info($LotID, $result['Description'], $result['BusinessName'], $result['RetailValue']);

	$header = array('Bidder Number', 'Bid Amount', 'Bidder Number', 'Bid Amount'); //Header array
	$Outerdata=array(); //Array of rows that contains all the rows

	for ($x = 1; $x <= 10; $x++) {
		$p = $x + 10; 
		$Innerdata=array(); //Array of data for each row
		$Innerdata[] = "" .$x. ".";
		$Innerdata[] = " ";
		$Innerdata[] = "" .$p. ".";
		$Innerdata[] = " ";

		$Outerdata[]=$Innerdata; //Put row array into out array that contains rows
	} 
	//print_r($Outerdata);
	$pdf->BasicTable($header,$Outerdata);
}while($result = $sth->fetch(PDO::FETCH_ASSOC));
$pdf->SetTitle('W.H. Taylor Elementary PTA 2014 Silent Auction Lot ' . $LotID);
$pdf->Output();

}else{
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
			<?php
				if($validform == false){
					echo "<span style='color:red;'>Your record was not saved. Some errors were reported.</span><br /><br />";
				} 
				?>
				<div class="form-style-5">
					<fieldset>
						<form action="biddingsheet.php" method="post">
						<br/>
						<legend><span class="number">1</span>View Bidding Sheet for Lot </legend>
						Enter the LotID: <input type="text" name="LotID" placeholder="*Required" value="<?php echo $LotID; ?>">
						<?php
						//var_dump($rid);
						
						if($LotIDvalid == false){
							echo "<span style='color:red;'>Error: ". $LotIDerror ."</span><br /><br />";
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

<?php
}
?>