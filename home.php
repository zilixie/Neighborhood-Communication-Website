<?php
	session_start();

	function find_neighbor() {
		$servername = "127.0.0.1";
			$username = "root";
			$password = "root";
			$dbname = "neighborhood";
			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
		}

		$hid_bid = $conn->query("select bid, hid from user, address where user.street = address.street and user.st_number = address.st_number and uid = '{$_SESSION['current_uid']}'");
		$result = $hid_bid->fetch_assoc();
		$hid_name = $conn->query("select name from hood where hid = '{$result["hid"]}'");
		$bid_name = $conn->query("select name from block where bid = '{$result["bid"]}'");
		$result1 = $hid_name->fetch_assoc();
		$result2 = $bid_name->fetch_assoc();
		if($hid_bid)
		{
		  echo "$result1[name]";
		  echo ", ";
		  echo "$result2[name]";
		}
	}

	function get_block_user(){
		$servername = "127.0.0.1";
			$username = "root";
			$password = "root";
			$dbname = "neighborhood";
			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
		}
		$bid = $conn->query("select bid from user, address where user.street = address.street and user.st_number = address.st_number and uid = '{$_SESSION['current_uid']}'");
		$result = $bid->fetch_assoc();
		$block_peo_num = $conn->query("select count(uid) as cu from user, address where user.street = address.street and user.st_number = address.st_number and address.bid = '{$result["bid"]}'");
		$result1 = $block_peo_num->fetch_assoc();
		if($block_peo_num) {
		  echo "$result1[cu]";
		}
	}
	
	function get_hood_user(){
		$servername = "127.0.0.1";
			$username = "root";
			$password = "root";
			$dbname = "neighborhood";
			$conn = new mysqli($servername, $username, $password, $dbname);

			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
		}
		$hid = $conn->query("select hid from user, address where user.street = address.street and user.st_number = address.st_number and uid = '{$_SESSION['current_uid']}'");
		$result = $hid->fetch_assoc();
		$block_peo_num = $conn->query("select count(uid) as cu from user, address where user.street = address.street and user.st_number = address.st_number and address.hid = '{$result["hid"]}'");
		$result1 = $block_peo_num->fetch_assoc();
		if($block_peo_num){
		  echo "$result1[cu]";
		}
	}


	function find_all_user_in_hood() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "select uid, bid, hid from user u inner join address a on a.street = u.street and a.st_number = u.st_number where u.email = '{$_SESSION['current_user_email']}';";
		//echo $sql;
		$user_profile = $conn->query($sql);
		$row = $user_profile->fetch_assoc();
		$uid = $row['uid'];
		$bid = $row['bid'];
		$hid = $row['hid'];

		$a = [];
		$b = [];

		$sql = "select * from user u inner join address a on a.street = u.street and a.st_number = u.st_number where a.hid = {$hid};";
		$all_hood_user = $conn->query($sql);
		while ($row = $all_hood_user->fetch_assoc()) {
			array_push($a, ucfirst($row[first_name])." ".ucfirst($row[last_name]));
			array_push($b, $row[uid]);
		}
		return array(
		    'name' => $a,
    		'uid' => $b
    	);
	}

	function get_user_status() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "select * from user where email='".$_SESSION['current_user_email']."';";
		$find_user = $conn->query($sql);
		$row = $find_user->fetch_assoc();
		//echo $sql;
		$_SESSION['current_uid'] = $row['uid'];
		//echo "<h1>uid is: {$_SESSION['current_uid']}</h1>";
		if ($row['status'] == 'pending') {
			include("./index1.php");
		}
		else {
			include("./index2.php");
		}
	}

	function count_alert() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);


		$sql = "select * from user where email='".$_SESSION['current_user_email']."';";
		$find_user = $conn->query($sql);
		$row = $find_user->fetch_assoc();
		//echo $sql;
		$_SESSION['current_uid'] = $row['uid'];

		$sql = "select r.rid, u.first_name, u.last_name, r.request_content,r.uid, r.type from reqrecipient rr inner join request r on rr.rid = r.rid inner join user u on u.uid = r.uid where rr.uid={$_SESSION['current_uid']};";
		$all_notifications = $conn->query($sql);

		if ($all_notifications->num_rows > 0) {
			$_SESSION['alert_count'] = $all_notifications->num_rows;
		} else {
			$_SESSION['alert_count'] = 0;
		}
	}

	if (!isset($_SESSION['current_user_name'])) {
		echo '<script>window.location.href = "signin.php";</script>';
	}
	if (isset($_GET['logout'])) {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "update user set logout_time=now() where uid={$_SESSION['current_uid']};";
		echo $sql;
		$run = $conn->query($sql);
		session_destroy();
		echo '<script>window.location.href = "signin.php";</script>';
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

      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
<script>
	function openNav() {
		document.getElementById("mySidenav").style.width = "250px";
		document.getElementById("main").style.marginLeft = "250px";
		document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
	}

	function closeNav() {
		document.getElementById("mySidenav").style.width = "0";
		document.getElementById("main").style.marginLeft= "0";
		document.body.style.backgroundColor = "white";
	}
	/* When the user clicks on the button, 
	toggle between hiding and showing the dropdown content */
	function myFunction() {
		document.getElementById("myDropdown").classList.toggle("show");
	}

	// Close the dropdown if the user clicks outside of it
	window.onclick = function(event) {
		if (!event.target.matches('.dropbtn')) {
			var dropdowns = document.getElementsByClassName("dropdown-content");
			var i;
			for (i = 0; i < dropdowns.length; i++) {
				var openDropdown = dropdowns[i];
				if (openDropdown.classList.contains('show')) {
					openDropdown.classList.remove('show');
				}
			}
		}
	}

	var map;
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: 40.7128, lng: -74.0060},
		zoom: 12
		});
	};

	$(document).ready(function(){
		$('.removable').click(function(){
			$(this).parent().parent().hide();
		});
	});

</script>



</head>
    
<body style="background-color:#EFEFEF; ">

<div id="main">
  	<?php count_alert();
  	echo $_SESSION['reply_id'];
  	find_all_user_in_hood();?>
	<div id="mySidenav" class="sidenav">
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		<a href="http://localhost:8000/home.php"><i class="fa fa-fw fa-home"></i> Home</a>
		<a href="#status"><i class="fa fa-fw fa-star"></i> Status</a>
		<a href="#topics"><i class="fa fa-fw fa-globe"></i> Topics</a>
		<a href="http://localhost:8000/relation1.php"><i class="fa fa-fw fa-user"></i> Relations</a>
		<a href="http://localhost:8000/home.php?logout=true"><i class="fa fa-fw fa-sign-out"></i> Logout</a>
	</div>
	<div class="navbar">
		<a href="#home"> </a>
		<span style="margin-bottom:2px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
		<div class="search-container">
				 <form id='update' method='post' accept-charset='UTF-8' enctype="multipart/form-data" action="/search_message.php">
					<input type="text" placeholder="Search Message" name="search" >
					<button type="submit"><i class="fa fa-search"></i></button>
			</form>
			</div>
        <a href="#home"> </a>
		<a href="#home"> </a>
		<a href="#home"> </a>
		<a href="#home"> </a>
		<a href="#home"> </a>
		<a href="#home"> </a>
		<a href="#home"> </a>
		<a href="#home"> </a>
		<a href="#home"> </a>
		<a href="#home"> </a>
		<a href="#home"> </a>
		
		<a href="http://localhost:8000/alert.php"><i class="fa fa-fw fa-bell"></i> <span class="badge"><?php echo $_SESSION['alert_count']?></span></a>
		<a href="http://localhost:8000/message.php"><i class="fa fa-fw fa-comments"></i> </a>
		<a href="http://localhost:8000/profile.php" style="margin-right:2%;"><i class="fa fa-fw fa-user"></i> </a>	
	</div> 	


	<div class="row">
		<div class="leftcolumn">
			<?php  echo get_user_status() ?>
			<div class="card">
			<h2>My family</h2>
			<div class="fakeimg" style="height:200px;">Image</div>
			<p>Some text..</p>
			<p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
			</div>
			<div class="card">
			<h2>Topics I follow</h2>
			<div class="fakeimg" style="height:200px;">Image</div>
			<p>Some text..</p>
			<p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
			</div>
		</div>

		<div class="rightcolumn">
			<div class="card2">
				<h2>Neighbor info</h2>
				<?php find_neighbor(); ?>
				<div id="map"></div>
				<div>Block people number: <?php get_block_user();?></div>        
				<div>Hood people number: <?php get_hood_user();?></div>
				<script>
				var map;
				function initMap() {
				map = new google.maps.Map(document.getElementById('map'), {
				center: {lat: 40.7128, lng: -74.0060},
				zoom: 14
				});
				}
				</script>
				<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEp3GPAv657jGVqiFWCV6iPkLiQW6vHXU&callback=initMap"
				async defer></script>

			</div>
			<div class="card2">
				<h3>Popular Post</h3>
				<div class="fakeimg">Image</div><br>
				<div class="fakeimg">Image</div><br>
				<div class="fakeimg">Image</div>
			</div>
				<div class="card2">
				<h3>Recent activity</h3>
				<p>Some text..</p>
			</div>
		</div>
	</div>
</div>

<!-- 
<script>
function myFunction(){ 
	$("#exampleModal2").modal('toggle'); //see here usage
};
</script>
 -->


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

var contact = [<?php echo '"'.implode('","',  find_all_user_in_hood()["name"] ).'"' ?>]
autocomplete(document.getElementById("myInput"), contact);
</script>

<script>
  import Avatar from 'vue-avatar-component'
  export default {
    components: { Avatar }
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEp3GPAv657jGVqiFWCV6iPkLiQW6vHXU&callback=initMap"
	async defer></script>

<avatar username="VueJS" initials="VUE" :size="100"></avatar>
</body>
</html>

