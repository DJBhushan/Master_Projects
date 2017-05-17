<!DOCTYPE html>
<html lang="en">
    <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

		<!-- Website CSS style -->
		<link rel="stylesheet" type="text/css" href="css/main.css">

		<!-- Website Font style -->
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
		
		<!-- Google Fonts -->
		<link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

		<title>Login Page</title>
	</head>
	<body>
		<?php
			require_once('DBConnect.php');
			session_start();
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				if (empty($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) 
				{
					$email = "Email is required";
				}
				
				if (empty($_POST["password"])) 
				{
					$password = "Password is required";
				}
				if(!empty($_POST["email"] && $_POST["password"]))
				{
					//$salt = sha1(md5($password));
					//$salt = "bhushan";
					//$password_hash = md5($_POST["password"].$salt);
					//echo $password_hash;
					$sql = "SELECT * FROM Users WHERE email = '" . $_POST["email"] . "'";
					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) == 1) 
					{
						$row = $result->fetch_assoc();
						//$salt = "bhushan";
						//$Password = password_hash($_POST["password"] . $salt, PASSWORD_DEFAULT);
						if(password_verify($_POST["password"], $row["password"]))
						{
							$_SESSION['email'] = $_POST["email"];
							$_SESSION['userID'] = $row["UserID"];
							$_SESSION['firstname'] = $row["FirstName"];
							$_SESSION['lastname'] = $row["LastName"];
							$_SESSION['start'] = time();
							$_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
							header('location: MessageBoard.php');
						}
						else 
						{
							$error = "Authentcation failed...!!! <br>";
						}
					}
					else 
						{
							$error = "Invalid Login...!!! <br>";
						}
					$conn->close();
				}
			}
		?>
		<div class="container">
			<div class="panel-heading">
        <div class="panel-title text-center">
          <h1 class="title">Login Page</h1>
          <hr />
        </div>
      </div> 
			<div class="row main">
				<div class="main-login main-center">
					<form class="form-horizontal" method="post" action="login.php">
						
						<div class="form-group">
							<span class="error"><?php echo $error; ?></span></br>
								<span class="error">* <?php echo $email; ?></span></br>
							<label for="email" class="cols-sm-2 control-label">Your Email</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="email" id="email"  placeholder="Enter your Email" value = "<?php echo $_POST['email']; ?>" /><br>
								</div>
							</div>
						</div>
            
            <div class="form-group">
							<span class="error">* <?php echo $password; ?></span></br>
							<label for="password" class="cols-sm-2 control-label">Password</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
									<input type="password" class="form-control" name="password" id="password"  placeholder="Enter your Password" value = "<?php echo $_POST['password']; ?>" />
								</div>
							</div>
						</div>

            <div class="form-group ">
							<button type="submit" name = "submit" value = "submit" class="btn btn-primary btn-lg btn-block login-button" style = "width: 100%;">Login</button>
						</div>
						<div class="login-register">
							<a href="signup.php">Sign Up</a>
						</div>
						<div class="login-register">
							<a href="ForgotPassword.php">Forgot Password?</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>