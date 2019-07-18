<?php
	require_once 'lib/core.php';
	if(isset($_GET["id"]))
			{
				$bid_id=$_GET["id"];
				$_SESSION["bid_id"]=$bid_id;
			}
	if($_SERVER["REQUEST_METHOD"]=="POST")
			{
				if(isset($_POST["submit"]))
					{
						$bid_amount=$_POST["bid_amount"];
						$sql="insert into bid_desc(bid_id,amount,vendor_id) values($bid_id,$bid_amount,".$_SESSION["user_id"].")";
						if($conn->query($sql))
								{
									$success=true;
								}
							else
								{
									$error=true;	
								}
					}
			}
	$sql="select * from bid where id=".$bid_id;
	$result=$conn->query($sql);
	if($result->num_rows>0)
		{
			$row=$result->fetch_assoc();
			$bid=$row;
		}
	date_default_timezone_set('Asia/Kolkata');					//to set indian time zone
	$start_time = strtotime($bid["start_time"]);				//to convert start time to unix standard time
	$_SESSION["start_time"]=$start_time;
	$end_time = $start_time+1800;								//to convert end time to unix standard time
	$current_time=strtotime(date("Y-m-d H:i:s"));				//to get and covnvert current time
	$time_diff=round(($end_time - $current_time) / 60,2);		//to get the difference b/w two times ie start or current
	if($time_diff<0)
		{
			$bid_status="BID ENDED";							//if bid time is over
		}
	else
		{
			$bid_status=$time_diff;								//to store the time difference	
		}
?>
<!DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<title>Put a bid</title>
</head>
<body onload="get_bids()">
	<h2 style="text-align: right;"><a href="logout.php">logout</a></h2>
		<h4>BID NAME : <i><?=$bid["name"]?></i></h4>
		<h4>BID DESCRIPTION : <i><?=$bid["description"]?></i></h4>
		<?php
			if($bid["vendor_id"]!=-1&&$bid["vendor_id"]!=0)
				{
					$sql="select username from signup where id=".$bid["vendor_id"];
					$result=$conn->query($sql);
					if($result->num_rows>0)
							{
								$row=$result->fetch_assoc();
							}
					?>
						<h4 id="bid_amount">BID AMOUNT :<i><?=$bid["amount"]?>(WON BY - <?=$row["username"]?>)</i></h4>
					<?php
				}
			else if($bid["vendor_id"]==0)
				{
					?>
						<h4 id="bid_amount">BID AMOUNT :<i><?=$bid["amount"]?>(NO-ONE BIDDED IN THIS BID)</i></h4>
					<?php	
				}
			else
				{
					?>
						<h4 id="bid_amount">BID AMOUNT :<i><?=$bid["amount"]?></i></h4>
					<?php	
				}
		?>
		<h4>BID START TIME:<?=$bid["start_time"]?></h4>
		<h2>BIDS</h2>
		<h4 id="time_left">BID STATUS:<?=$bid_status?></h4>
		<p>vendor name----bid amount</p>
		<p id="bid_details"></p>
		<form method="POST">
				YOUR BID:
				<input type="text" name="bid_amount" required><br><br><br> 	
				<?php
						if(isset($success))
								{
									?>
										<p>Your bid has added</p>
									<?php
								}
							else if(isset($error))
								{
										?>
											<p>There is something wrong!!!try again</p>
										<?php
								}
				?>
				<input type="submit" name="submit">
		</form>
</body>
</html>
<script type="text/javascript">
	function get_bids()
		{
			var last_bid_id=0;											//to store recent bid id
			var time_interval=setInterval(function()					//to get the latest time left
				{
					$.ajax({
						   type:"post",
						   data:{status:"2"},
						   url:"ajax.php",
						   success:function(result)
						   	{
						   		if(result=='false')
						   				{
						   					clearInterval(time_interval);
						   					document.getElementById("time_left").innerHTML="";
						   					document.getElementById("time_left").innerHTML="BID STATUS:BID ENDED";
						   					$.ajax({
													   type:"post",
													   data:{status:"3"},
													   url:"ajax.php",
													   success:function(result)
													   	{
													   			clearInterval(time_interval);
													   			location.reload();
														}
									 	  		});		
						   				}
						   			else
							   			{
							   				document.getElementById("time_left").innerHTML="";
						   					document.getElementById("time_left").innerHTML="TIME LEFT - "+result+" minutes only";	
							   			}
							}
		 	  		});		
					$.ajax({
						   type:"post",
						   data:{status:"1",last_bid_id:last_bid_id},
						   url:"ajax.php",
						   success:function(result)
						   {
						   		if(result!="no new")			//no new is there is no new bid
						   			{
										   		var bid_details=JSON.parse(result);
												for(i=0;i<bid_details.length;++i)
													{
															var latest_bid_id=parseInt(bid_details[i]["id"]);	//to convert bid id from 															string to int
															document.getElementById("bid_details").innerHTML+=(bid_details[i]["username"]+"  -------  "+bid_details[i]["amount"]+"<br><br>");
															if(last_bid_id<latest_bid_id)
																	last_bid_id=bid_details[i]["id"];
													}								   		
									}
							}
		 	  		});				
				}
					,1000);
		}
	
</script>