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

		<title>Reset Password Page</title>
	</head>
	<body>
		<?php
			require_once('DBConnect.php');
			session_start();
      
			if($_SERVER["REQUEST_METHOD"] == "POST")
      {
       	if (empty($_POST["password"])) 
				{
					$password = "Password is required";
				}
				if(!empty($_POST) && $_POST["password"] != "")
				{
          $Password = password_hash($_POST['password'], PASSWORD_DEFAULT);
					$sql = "UPDATE Users SET password = '" . $Password . "' WHERE UserID = '" . $_SESSION['userID'] . "'";
          if ($conn->query($sql) === TRUE) 
          {
            $already_exist =  "Password Updated Successfully";
          } 
          else 
          {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
				} 
					$conn->close();
			}
		?>
		<div class="container">
			<div class="panel-heading">
        <div class="panel-title text-center">
          <h1 class="title">Reset Password Page</h1>
          <hr />
        </div>
      </div> 
			<div class="row main">
				<div class="main-login main-center">
					<form class="form-horizontal" method="post" action = "ResetPassword.php" >
						
						<div class="form-group">
              <span class="error"><?php echo $already_exist; ?></span></br>
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
							<button type="submit" name = "submit" value = "submit" class="btn btn-primary btn-lg btn-block login-button" style ="width: 100%">Update Password</button>
						</div>
            
            <div class="login-register">
              <a href="login.php">Login</a>
            </div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>