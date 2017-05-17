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

		<title>Logout Page</title>
	</head>
	<body>
    <?php
			session_start();
			
      session_destroy();
		?>
		<div class="container">
      <div class="panel-heading">
        <div class="panel-title text-center">
          <h1 class="title">Logout Page</h1>
          <hr />
        </div>
      </div> 
			<div class="row main">
				<div class="main-login main-center">
					<form class="form-horizontal" method="post" action="login.php">
						<div class="form-group">
							<label for="email" class="cols-sm-2 control-label">Logout Successfully...!!!</label><br>
							<a href = 'login.php' class = "btn btn-primary btn-lg btn-block login-button" style = "width: 100%" >Login</a>
						</div>
          </form>
				</div>
			</div>
		</div>
	</body>
</html>