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

		<title>Profile Edit Page</title>
	</head>
	<body>
    <?php
			require_once('fileupload.php');
			require_once('DBConnect.php');
			session_start();
			$userID = $_SESSION['userID'];
			if (!isset($_SESSION['userID'])) 
			{
					header('location: SessionExpire.php');
			}
			else
			{
				$now = time(); // Checking the time now when home page starts.
				if ($now > $_SESSION['expire']) 
				{
						unset($_SESSION['userID']);
          	session_destroy();
						header('location: SessionExpire.php');
				}
				else
				{
					$sql = "SELECT * FROM Users WHERE UserID = '" . $userID . "'";
					$result = mysqli_query($conn, $sql);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc())
						{
							$FirstName = $row['FirstName'];
							$LastName = $row['LastName'];
							$Email = $row['email'];
							$ImageName = $row['image_name'];
							$Password = $row['password'];
						}
					}
					if(!empty($_POST['update']))
					{

						if($_POST['firstname'] != "")
							$FirstName = $_POST['firstname'];
						else
							$FirstName = $FirstName;

						if($_POST['lastname'] != "")
							$LastName = $_POST['lastname'];
						else
							$LastName = $LastName;

						if($_POST['email'] != "")
							$Email = $_POST['email'];
						else
							$Email = $Email;

						if($_POST['password'] != "")
							$Password = $_POST['password'];
						else
							$Password = $Password;

						if($_FILES["fileToUpload"]["name"] != "")
							$ImageName =  $userID . "_" . $_FILES["fileToUpload"]["name"];
						
						$result = uploadImage($ImageName);
						//echo $result;
						$pos = strpos($result, "Sorry,");
						if($pos === false )
						{
							//$salt = "bhushan";
							//$Password = password_hash($Password . $salt, PASSWORD_DEFAULT);
							$Password = password_hash($Password, PASSWORD_DEFAULT);
							$sql = "UPDATE Users SET FirstName = '" . $FirstName . "', LastName = '" . $LastName . "', email = '" . $Email. "', password = '" . $Password . "', image_name = '" . $ImageName .  "'				 WHERE UserID = '" . $userID ."'";
							if ($conn->query($sql) === TRUE) 
							{
								$already_exist =  "Profile Updated Successfully";
							} 
							else 
							{
								echo "Error: " . $sql . "<br>" . $conn->error;
							}
						}
						else{
							$already_exist = $result;
						}
					}
					$conn->close();
				?>
				<div class="container">
					<div class="panel-heading">
						<div class="panel-title text-center">
							<h1 class="title">Profile Edit Page</h1>
							<hr />
						</div>
					</div> 
					<div class="row main">
						<div class="main-login main-center">
							<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
								<div class="form-group">
									<a href="Profile.php?userid=<?php echo $userID; ?> ">Go To Profile</a><br>
									<span class="error"><?php echo $already_exist; ?></span><br>
									<label for="name" class="cols-sm-2 control-label">Your First Name</label>
									<a href="logout.php" style="float: right;">Logout</a>
									<div class="cols-sm-10">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
											<input type="text" class="form-control" name="firstname" id="firstname"  placeholder="Enter first your Name" value = "<?php echo $FirstName; ?>" >
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="name" class="cols-sm-2 control-label">Your Last Name</label>
									<div class="cols-sm-10">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
											<input type="text" class="form-control" name="lastname" id="lastname"  placeholder="Enter lastname your Name" value = "<?php echo $LastName; ?>" />
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="email" class="cols-sm-2 control-label">Your Email</label>
									<div class="cols-sm-10">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
											<input type="text" class="form-control" name="email" id="email"  placeholder="Enter your Email" value = "<?php echo $Email; ?>" />
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="password" class="cols-sm-2 control-label">Password</label>
									<div class="cols-sm-10">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
											<input type="password" class="form-control" name="password" id="password"  placeholder="Enter your Password"/>
										</div>
									</div>
								</div>

								<div class="form-group">
									<label for="password" class="cols-sm-2 control-label">Upload Image</label>
									<div class="cols-sm-10">
										<div class="input-group">
											<input type="file" name="fileToUpload" id="fileToUpload">
										</div>
									</div>
								</div>

								<div class="form-group ">
									<button type="submit" name = "update" value = "update" class="btn btn-primary btn-lg btn-block login-button" style = "width: 100%" >Update</button>
								</div>

							</form>
						</div>
					</div>
				</div>
			</body>
		</html>
	<?php
				}
		}
?>