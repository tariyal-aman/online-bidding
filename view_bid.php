<?php
	require_once 'lib/core.php';
	$sql="select id,name,vendor_id from bid where user_id=".$_SESSION["user_id"];
	$result=$conn->query($sql);
	if($result->num_rows>0)
		{
				while($row=$result->fetch_assoc())
					{
						$bids[]=$row;
					}
		}
?>
<!DOCTYPE html>
<html>
<head>
	<title>View all bids</title>
</head>
<body>
	<h2 style="text-align: right;"><a href="logout.php">logout</a></h2>
	<h1>ALL BIDS</h1>
	<?php
		if(isset($bids))
		  {
			foreach($bids as $bid)
					{
						?>
							<a href='bid_description.php?id=<?=$bid['id']?>'><?=$bid["name"]?></a>
							<p>status-
						<?php
						if($bid["vendor_id"]==-1)
							{
									?>
												open</p><br><br><br>
									<?php
							}
						else if($bid["vendor_id"]==0)
							{
									?>
												closed but no have bid</p><br><br><br><br>
									<?php
							}
						else 
							{
									?>
												bid completed<br><br><br>
									<?php	
							}
					}
		}
		else
		{
			?>
				<p>No bids at all....</p>
			<?php
		}
	?>
</body>
</html>