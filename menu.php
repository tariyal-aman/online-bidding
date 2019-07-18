<?php
	require_once 'lib/core.php';
	if(isset($_GET["user_type"]))
		{
			if($_GET["user_type"]==0)
			$user_type="c";
			else
				{
					$user_type="v";
					$sql="select name,id from bid where vendor_id='-1'";
					$result=$conn->query($sql);
					if($result->num_rows>0)
						{
								while($row=$result->fetch_Assoc())
								{
										$open_bids[]=$row;
								}
								$user_type="vc";				//if there is atleast one open bid
						}			

			}
		}
	else
		{
				$user_type="null";
		}
?>
<!DOCTYPE html>
<html>
<head>
	<title>main menu</title>
</head>
<body>
<h1>MAIN MENU</h1>
<h2 style="text-align: right;"><a href="logout.php">logout</a></h2>
	<?php
			if($user_type=="c")
				{	
					?>
						<a href="create_bid.php">CREATE A NEW BID</a>
						<br><br><br>
						<a href="view_bid.php">VIEW ALL BIDS</a>
					<?php
				}
			else if($user_type=="v")
				{
					?>
						<h2>No new Bid....Please check after some time</h2>
					<?php
				}
			else if($user_type=="vc")
				{
					foreach($open_bids as $bid)
						{
					?>
							<a href="put_bids.php?id=<?=$bid['id']?>"><?=$bid["name"]?></a><br>
					<?php
						}	
				}
	?>	
</body>
</html>