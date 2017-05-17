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

		<title>Sign Up Page</title>
	</head>
	<body>
		<?php
			require_once('DBConnect.php');
			session_start();
					if (!empty($_POST))
					{
						$FirstName = $_POST['firstname'];
						$LastName = $_POST['lastname'];
						$email = $_POST['email'];
						$password = $_POST['password'];
						if (empty($_POST["firstname"])) 
						{
							$FirstName_error = "First Name is required";
						}
						else 
						{
							$FirstName = $_POST["firstname"];
						}
					 if (empty($_POST["lastname"])) 
						{
							$LastName_error= "Last Name is required";
						}
						else 
						{
							$LastName = $_POST["lastname"];
						}
						if (empty($_POST["email"]) || !filter_var($email, FILTER_VALIDATE_EMAIL)) 
						{
							$email_error = "Email is required/Invalid";
						}
						else 
						{
							$email = $_POST["email"];
						}
						if (empty($_POST["password"])) 
						{
							$password_error = "Password is required";
						}
						else 
						{
							$password = $_POST["password"];
						}
						if($FirstName != "" && $LastName != "" && (filter_var($email, FILTER_VALIDATE_EMAIL)) && $password != "")
						{
							$sql = "SELECT * FROM Users WHERE email = '" . $email . "'";
							$result = mysqli_query($conn, $sql);
							if (mysqli_num_rows($result) == 1) {
								$already_exist = "Email ID Already Exist...!!!";
							}
							else
							{
								//$salt = sha1(md5($password));
								//$salt = "bhushan";
								//$password = md5($password.$salt);
								$password = password_hash($password, PASSWORD_DEFAULT);
								$sql = "INSERT INTO Users (UserID, FirstName, LastName, email, password) VALUES ('', '" . $FirstName . "', '" . $LastName . "', '" . $email. "', '" . $password . "')";
								if ($conn->query($sql) === TRUE) {
									$already_exist =  'New record added successfully...!!! <br><a href="login.php" class="btn btn-primary btn-lg btn-block login-button" style = "width: 40%; float: right;">Login</a>';
								} 
								else 
								{
									echo "Error: " . $sql . "<br>" . $conn->error;
								}
							}
							$conn->close();
						}
					}
				?>
						<div class="container">
								<div class="panel-heading">
								<div class="panel-title text-center">
									<h1 class="title">Sign Up Page</h1>
									<hr />
								</div>
							</div>
							<div class="row main">
							<div class="main-login main-center">
								<form class="form-horizontal" method="post" action="">

									<div class="form-group">
										<span class="error"><?php echo $already_exist; ?></span><br>
										<label for="name" class="cols-sm-2 control-label">Your First Name</label>
										<div class="cols-sm-10">
											<span class="error">* <?php echo $FirstName_error; ?></span>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
												<input type="text" class="form-control" name="firstname" id="firstname"  placeholder="Enter first your Name" value = "<?php echo $_POST['firstname']; ?>" >
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="name" class="cols-sm-2 control-label">Your Last Name</label>
										<div class="cols-sm-10">
											<span class="error">* <?php echo $LastName_error; ?></span>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
												<input type="text" class="form-control" name="lastname" id="lastname"  placeholder="Enter lastname your Name" value = "<?php echo $_POST['lastname']; ?>" />
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="email" class="cols-sm-2 control-label">Your Email</label>
										<div class="cols-sm-10">
											<span class="error">* <?php echo $email_error; ?></span>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
												<input type="text" class="form-control" name="email" id="email"  placeholder="Enter your Email" value = "<?php echo $_POST['email']; ?>" />
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="password" class="cols-sm-2 control-label">Password</label>
										<div class="cols-sm-10">
											<span class="error">* <?php echo $password_error; ?></span>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
												<input type="password" class="form-control" name="password" id="password"  placeholder="Enter your Password" value = "<?php echo $_POST['password']; ?>" />
											</div>
										</div>
									</div>

									<div class="form-group ">
										<button type="submit" class="btn btn-primary btn-lg btn-block login-button" style = "width: 100%;">Register</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</body>
</html>
