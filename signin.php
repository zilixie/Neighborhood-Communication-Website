<?php
	session_start();
    function sign_in_user($email, $psw) {
    	$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "select * from user where email='".$email."';";
		$find_user = $conn->query($sql);
		if ($find_user->num_rows == 0) {
			echo "<p style='color:red;'> user does not exists. </p>";
		} else {
			$sql = "select * from user where email='".$email."' and password=MD5('".$psw."');";
			$match_password = $conn->query($sql);
			if ($match_password->num_rows == 0) {
				echo "<p style='color:red;'> password not correct. </p>";
			}
			else {
				$row = $match_password->fetch_assoc();
				$_SESSION['current_user_name'] = ucfirst($row[first_name])." ".ucfirst($row[last_name]);
				$_SESSION['current_user_email'] = $email;
				$_SESSION['current_uid'] = $row[rid];
				$_SESSION['current_status'] = $row[status];
				$_SESSION['current_address'] = $row[st_number]." ".$row[street];
				echo '<script>window.location.href = "home.php";</script>';
			}
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>My Neigborhood</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/style.css">
</head>
<body style="background-color:#EFEFEF; ">
	<div class="jumbotron text-center" style="margin-bottom:0; height: 400px; background-size: cover; background-image: url('source1.gif');">
		<h1 style="margin-top:240px;">My Neigborhood</h1>
		<h3>A website that helps you learn your neighbor better.</h3>
	</div>

	<nav class="navbar navbar-expand-sm navbar-dark" style="background-color:#A693B0; ">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
			<span class="navbar-toggler-icon" ></span>
		</button>
		<div class="collapse navbar-collapse" id="collapsibleNavbar" >
			<ul class="navbar-nav">
				<li class="nav-item">
				<a class="nav-link" href="http://localhost:8000/signup.php">About the Website</a>
				</li>
				<li class="nav-item">
				<a class="nav-link" href="http://localhost:8000/signup.php">Sign Up</a>
				</li>

			</ul>
		</div>  
	</nav>

	<div class="container" style="margin-top:30px; margin-bottom:30px; ">
		<div class="row align-items-center justify-content-center">
			<div class="card">
				<h2 style="text-align: center;">Sign In</h2>
				<?php
					if (isset($_POST['email']) && 
						isset($_POST['password'])) {
						sign_in_user($_POST['email'], $_POST['password']);
					}
				?> 
				
				<form id='signup' action='' method='post' accept-charset='UTF-8' enctype="multipart/form-data">

				<input type='text' name='email' id='email' maxlength="50" placeholder="Email"/><br><br>

				<input type='password' name='password' id='password' maxlength="50" placeholder="Password"/><br><br>

				<p></p>
				<input id='submit' type='submit' name='submit' value='Sign In'  onclick="" />
				</form><br>



			</div>

		</div>
	</div>


</body>
</html>

