<?php
	require_once 'lib/core.php';
	if($_SERVER["REQUEST_METHOD"]=="POST")
		{
			if(isset($_POST["submit"]))
				{
					$bid_name=$_POST["bid_name"];
					$bid_desc=$_POST["bid_desc"];
					$bid_amount=$_POST["bid_amount"];
					date_default_timezone_set('Asia/Kolkata');					//to set indian time zone
					$start_date=date("Y-m-d H:i:s");
					$sql="insert into bid(name,description,amount,user_id,start_time) values('$bid_name','$bid_desc',$bid_amount,'".$_SESSION["user_id"]."','$start_date')";
					if($conn->query($sql))
							{
								echo "<script>alert('bid created succefully');</script>";
								$user_type=$_SESSION["user_type"];
								if($user_type==0)
	                            header('Location:menu.php?user_type=0');
	                            else
	                            header('Location:menu.php?user_type=1');
							}
				}
		}
?>
<!DOCTYPE html>
<html>
<head>
	<title>New Bid</title>
</head>
<body>
	<h2 style="text-align: right;"><a href="logout.php">logout</a></h2>
	<h1>Start a new bid</h1>
	<form method="POST">
		BID NAME:
		<input type="text" name="bid_name" required=>
		<br><br>
		BID DESCRIPTION:
		<textarea name="bid_desc" height="10" width="30"></textarea>
		<br><br>
		BID AMOUNT:
		<input type="text" name="bid_amount">
		<br><br>
		<input type="submit" name="submit">
	</form>
</body>
</html>