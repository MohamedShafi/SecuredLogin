  <?php

	include("connect.php");
	include("functions.php");

	if(logged_in())
	{
		header("location: profile.php");
		exit();
	}


	$error = "";


	if(isset($_POST['submit']))
	{
		
		$email = mysql_real_escape_string($_POST['email']);
		$password = mysql_real_escape_string($_POST['password']);
		$checkbox = isset($_POST['keep']);
		if(email_exists($email, $con))
		{
			$result = mysqli_query($con, "SELECT password FROM users WHERE email='$email'");
			$retrievepassword = mysqli_fetch_assoc($result);

			if(md5($password) !== $retrievepassword['password'])
			{
				$error = "Password is incorrect";
			}
			else
			{
				$_SESSION['email'] = $email;

				if($checkbox == "on")
				{
					setcookie("email",$email, time()+3600);
				}
				header("location: profile.php");
			}
		}
		else
		{
			$error = "Email does not exists";
		}
		}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title> 
	<link rel="stylesheet" href="styles.css"
</head>
<body>

	<div id="error"><?php echo $error; ?></div>
	<div id="wrapper">
		<div id="menu">
			<a href="index.php">Sign Up</a>
			<a href="login.php">Login</a>
		</div>
		<div id="formDiv">

	<form method="POST" action="login.php">
<label>Email</label>
<input type="text" class="inputFields" name="email" required/><br/><br/>
  

<label>Password</label>
<input type="password" class="inputFields" name="password" required/><br/><br/>

<input type="checkbox" name="Keep">
<label>Keep me logged in</label><br/><br/>
<input type="submit" name="submit" class="theButtons" value="login"/>
</form>
	</div>
</div>

</body>
</html>
