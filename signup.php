<?php
	session_start();
	function sign_up_user() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$first_spc = strpos($_POST['address'], ' ');
		$no = substr($_POST['address'], 0, $first_spc);
		$str = substr($_POST['address'], $first_spc + 1);

		$sql = "CALL SIGN_UP ('{$_POST['fn']}', '{$_POST['ln']}',
					'{$_POST['email']}', MD5('{$_POST['password']}'), 'C:/url', '{$str}', {$no});";
		$signup = $conn->query($sql);

		if ($signup) {
			$_SESSION['current_user_email'] = $_POST['email'];

			// echo "<p>$str</p>";
			// echo "<p>$no</p>";
			echo "<p> sign up succeed </p>";
			$request = $conn->query("CALL OPEN_REQUEST({$uid}, 'join_block', '{$_POST['req_content']}');");

			$_SESSION['current_user_name'] = ucfirst($_POST['fn'])." ".ucfirst($_POST['ln']);
			$_SESSION['current_address'] = $_POST['address'];
			$_SESSION['current_status'] = 'pending';
			echo '<script>window.location.href = "home.php";</script>';
		} else {
			echo "<p style='color:red;'> sign up failed </p>";
		}
	}

	function find_all_address() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "select concat(st_number, ' ', street) as addr from address;";
		$address_list = $conn->query($sql);
		$a = [];
		while($row = $address_list->fetch_assoc()){
			array_push($a, $row[addr]);
		}
		return $a;

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
    <style>
		/* Always set the map height explicitly to define the size of the div
		* element that contains the map. */
		#map {
		height: 50%;
		width: 50%;
		}
		/* Optional: Makes the sample page fill the window. */
		html, body {
		height: 100%;
		margin: 0;
		padding: 0;
		}
    </style>
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
				<a class="nav-link" href="http://localhost:8000/signin.php">Sign in</a>
				</li>

			</ul>
		</div>
	</nav>

	<div class="container" style="margin-top:30px; margin-bottom:30px; ">
		<div class="row align-items-center justify-content-center">
			<div class="card">

				<h2 style="text-align: center;">Sign Up</h2>
				<?php
					find_all_address();
					if (isset($_POST['submit']) &&
						isset($_POST['fn']) &&
						isset($_POST['ln']) &&
						isset($_POST['email']) &&
						isset($_POST['address'])) {
						sign_up_user();
					}
				?>

				<form autocomplete="off" id='signup' action='' method='post' accept-charset='UTF-8' enctype="multipart/form-data">

				<input type='text' name='fn' id='fn' maxlength="50" placeholder="First Name" /><br><br>

				<input type='text' name='ln' id='ln' maxlength="50" placeholder="Last Name"/><br><br>

				<input type='text' name='email' id='email' maxlength="50" placeholder="Eamil"/><br><br>

<!-- 				<input type='text' name='street' id='street' maxlength="50" placeholder="Street"/><br><br>

				<input type='text' name='num' id='num' maxlength="50" placeholder="No."/><br><br> -->

				<input class="autocomplete" id="myInput" type="text" name="address" placeholder="Address"><br><br>

				<input type='password' name='password' id='password' maxlength="50" placeholder="Password"/><br><br>

				<div id='dropdown'></div>
				<input id='submit' type='submit' name='submit' value='Create User' onclick="" />
				</form><br>

			</div>

		</div>
	</div>


<script>
function autocomplete(inp, arr) {
	var currentFocus;
	inp.addEventListener("input", function(e) {
		var a, b, i, val = this.value;
		closeAllLists();
		if (!val) { return false;}
		currentFocus = -1;
		a = document.createElement("DIV");
		a.setAttribute("id", this.id + "autocomplete-list");
		a.setAttribute("class", "autocomplete-items");
		document.getElementById('dropdown').appendChild(a);
		for (i = 0; i < arr.length; i++) {
			if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
				b = document.createElement("DIV");
				b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
				b.innerHTML += arr[i].substr(val.length);
				b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
				b.addEventListener("click", function(e) {
					inp.value = this.getElementsByTagName("input")[0].value;
					closeAllLists();
				});
				a.appendChild(b);
			}
		}
	});
	inp.addEventListener("keydown", function(e) {
		var x = document.getElementById(this.id + "autocomplete-list");
		if (x) x = x.getElementsByTagName("div");
		if (e.keyCode == 40) {
			currentFocus++;
			addActive(x);
		} else if (e.keyCode == 38) {
			currentFocus--;
			addActive(x);
		} else if (e.keyCode == 13) {
			e.preventDefault();
			if (currentFocus > -1) {
			  if (x) x[currentFocus].click();
			}
		}
	});
	function addActive(x) {
	if (!x) return false;
	removeActive(x);
	if (currentFocus >= x.length) currentFocus = 0;
	if (currentFocus < 0) currentFocus = (x.length - 1);
	x[currentFocus].classList.add("autocomplete-active");
	}
	function removeActive(x) {
		for (var i = 0; i < x.length; i++) {
			x[i].classList.remove("autocomplete-active");
		}
	}
	function closeAllLists(elmnt) {
		var x = document.getElementsByClassName("autocomplete-items");
		for (var i = 0; i < x.length; i++) {
			if (elmnt != x[i] && elmnt != inp) {
				document.getElementById('dropdown').removeChild(x[i]);
			}
		}
	}
	document.addEventListener("click", function (e) {
		closeAllLists(e.target);
	});
}

var countries = [<?php echo '"'.implode('","',  find_all_address() ).'"' ?>]
autocomplete(document.getElementById("myInput"), countries);
</script>
	<!--<div id="map" style="display:block; margin: 0 auto;"></div>
	<script>
	var map;
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: 40.7128, lng: -74.0060},
		zoom: 12
		});
	}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEp3GPAv657jGVqiFWCV6iPkLiQW6vHXU&callback=initMap"
	async defer></script>
	<br>-->


</body>
</html>
