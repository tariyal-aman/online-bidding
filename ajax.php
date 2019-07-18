<?php
	require_once 'lib/core.php';
	$status=$_POST["status"];
	if($status==1)
			{
					$last_bid_id=$_POST["last_bid_id"];
					$sql="select bid.vendor_id,bid.id,bid.amount,user.username from bid_desc as bid,signup as user where bid.id > $last_bid_id AND user.id=bid.vendor_id AND bid_id=".$_SESSION["bid_id"];
					$result=$conn->query($sql);
					if($result->num_rows>0)
							{
								while($row=$result->fetch_assoc())
										{
											$bid_details[]=$row;
										}
								$bids=json_encode($bid_details);
								echo $bids;
							}
						else
							{
								echo "no new";				//if there is no new bid	
							}
							
			}
	else if($status==2)
			{
					date_default_timezone_set('Asia/Kolkata');					//to set indian time zone
					$start_time = $_SESSION["start_time"];
					$end_time = $start_time+1800;								//to convert end time to unix standard time
					$current_time=strtotime(date("Y-m-d H:i:s"));				//to get and covnvert current time
					$time_diff=round(($end_time - $current_time) / 60,2);			//to get the difference b/w two times ie start or current
					if($time_diff<0)
						{
								echo "false";
						}
					else
						{
								echo $time_diff;
						}
			}
	else if($status==3)
			{
					$bid_id=$_SESSION["bid_id"];
					$sql="select id,start_time from bid where id=".$bid_id." AND  vendor_id = -1";
					if($result=$conn->query($sql))
						{
							$row=$result->fetch_assoc();
							$start_time=$row["start_time"];
							if($result->num_rows==1)
									{
										$sql="select min(amount) as final_bid,vendor_id from bid_desc where bid_id=".$bid_id;
										if($result=$conn->query($sql))
											{
												if($result->num_rows>0)
														{
															$row=$result->fetch_assoc();
															$final_bid=$row["final_bid"];
															$vendor_id=$row["vendor_id"];
														}
													else
														{
															$final_bid=0;
															$vendor_id=0;	
														}
												$sql="update bid set start_time='$start_time',amount='$final_bid',vendor_id='$vendor_id' where id=".$bid_id;
												if($conn->query($sql))
														{
															if($vendor_id!=0)
																{
																		$data[0]=$final_bid;
																		$sql="select username from signup where id=".$vendor_id;
																		if($result=$conn->query($sql))
																			{
																					$row=$result->fetch_assoc();	
																			}
																		$data[1]=$row["username"];
																		$final_data=json_encode($data);
																		echo $final_data;
																}
															else
																{
																		echo "yes";
																}
														}
													else
														{
															echo "no";	
														}
											}
										else
											echo  "no";

									}
									else
										echo "no";
						}
						else
							echo "no";
						
			}

?>