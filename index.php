<?php
	require_once 'lib/core.php';
	if($_SERVER["REQUEST_METHOD"]=="POST")
			{
				if(isset($_POST["submit"]))
					{
						$username=$_POST["username"];
						$password=$_POST["password"];
						if($_POST["user_type"]=="customer")
							$user_type="0";					
						else
							$user_type="1";
						if(check_username($username,$conn))
							{
								if(!add_user($username,$user_type,$password,$conn))
										{
											echo "NOT SIGNED UP";	
										}
							} 
						else
							{	
								$error="True";
							}
					}
			}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home Page</title>
</head>
<body>
	<h1>SIGN-UP	</h1>
	<form method="POST">
		USERNAME:
		<input type="text" required name="username">
		<?php
				if(isset($error))
					echo "PLEASE ENTER A DIFFERENT USERNAME,THIS USERNAME IS TAKN BY SOMEONE ELSE";
		?>
		<br><br>
		PASSWORD
		<input type="password" required name="password">
		<br><br>
		USERTYPE
		<br><br>
		<input required type="radio" name="user_type" value="customer">CUSTOMER
		<input type="radio" name="user_type" value="vendor">VENDOR
		<br><br>
		<input type="submit" name="submit">
	</form>
	<a href="login.php">Have already registered...?</a>
</body>
</html>