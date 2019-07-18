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
						if(!login($username,$user_type,$password,$conn))
							{	
								$error="True";
							}
					}
			}

?>
<!DOCTYPE html>
<html>
<head>
	<title>bid login</title>
</head>
<body>
	<h1>LOGIN</h1>
<form method="POST">
		USERNAME:
		<input type="text" required name="username">
		<br><br>
		PASSWORD
		<input type="password" required name="password">
		<br><br>
		USERTYPE
		<br><br>
		<input required type="radio" name="user_type" value="customer">CUSTOMER
		<input type="radio" name="user_type" value="vendor">VENDOR
		<br><br>
				<?php
				if(isset($error))
					echo "PLEASE ENTER A VALID USERNAME AND PASSWORD<br>";
		?>
		<input type="submit" name="submit">
	</form>
	<a href="index.php">Not registered...!!!</a>
</body>
</html>