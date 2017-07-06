<?php

	include("connect.php");
	include("functions.php");

	$error = "";


	if(isset($_POST['submit']))
	{
		$firstName = mysql_real_escape_string($_POST['fname']);
		$lastName = mysql_real_escape_string($_POST['lname']);
		$email = mysql_real_escape_string($_POST['email']);
		$password = $_POST['password'];
		$passwordConfirm = $_POST['passwordConfirm'];

		$image = $_FILES['image']['name'];
		$tmp_image = $_FILES['image']['tmp_name'];
		$imageSize = $_FILES['image']['size']; 

		$conditions = isset($_POST['conditions']);

		$date= date("F, d y");

		if(strlen($firstName) < 3)
		{
			$error ="First name is too short";

		}
		elseif(strlen($lastName) < 3) 
		{
			$error = "Last name is too short";
			
		}
		elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$error = "Please enter valid email address";

		}
		elseif(email_exists($email, $con))
		{
			$error ="Someone is already registered with this email"; 
		}
		elseif(strlen($password) < 8)
		{
			$error = "Password must me greater than 8 characters";

		}
		elseif($password !== $passwordConfirm)
		{
			$error= "Password does not match";
		}
		elseif($image == ""){
			$error = "Please upload your image";
		}
		elseif($conditions)
		{
			$error = "You must be agree with the terms and conditions";
		}
		
		else
		{
			$password = md5($password);

			$imageExt = explode(".", $image);
			$imageExtension = $imageExt[1];

			if($imageExtension == 'PNG' || $imageExtension == 'png' || $imageExtension == 'JPG' ||$imageExtension == 'jpg')
			{
				$image = rand(0, 100000).rand(0, 100000).rand(0, 100000).time().".".$imageExtension;


				$insertQuery = "INSERT INTO users(firstName, lastName, email, password, image) VALUES ('$firstName', '$lastName', '$email', '$password', '$image')"	;
				if(mysqli_query($con, $insertQuery))
				{
					if(move_uploaded_file($tmp_image, "images/$image"))
					{
						$error = "You are successfully registered";

					}
					else
					{
						$error = "Image is not uploaded";

					}
					

				}	
			}
			else
			{
				$error = "File must me an image";
			}
		}

	}	

?>

<!DOCTYPE html>
<html>
<head>
	<title>Registration Page</title> 
	<link rel="stylesheet" href="styles.css">
</head>
<body>

	<div id="error" style=" <?php if($error !=""){ ?> display:block; <?php } ?>"><?php echo $error; ?></div>
<div id="wrapper">
<div id="menu">
			<a href="index.php">Sign Up</a>
			<a href="login.php">Login</a>
		</div>
<div id="formDiv">

<form method="POST" action="index.php" enctype="multipart/form-data">
<label>First Name</label>
<input type="text" name="fname" class="inputFields" required/><br/><br/>

<label>Last Name</label>
<input type="text" name="lname" class="inputFields" required/><br/><br/>

<label>Email</label>
<input type="text" name="email" class="inputFields" required/><br/><br/>

<label>Password</label>
<input type="password" name="password" class="inputFields" required/><br/><br/>

<label>Re-enter Password</label>
<input type="password" name="passwordConfirm" class="inputFields" required/><br/><br/>

<label>Image</label>
<input type="file" name="image" id="imageupload" /><br/><br/>

<input type="checkbox" name="conditions " />
<label>I agree with the terms and conditions</label><br/><br/>

<input type="submit" name="submit" class="theButtons" value="Submit" />
</form>
	</div>
</div>

</body>
</html>
