<?php
	session_start();
	$a = [];
	$b = [];

	$c = [];
	$d = [];

	function list_notifications($a, $b, $c, $d) {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "select r.rid, u.first_name, u.last_name, r.request_content,r.uid, r.type from reqrecipient rr inner join request r on rr.rid = r.rid inner join user u on u.uid = r.uid where rr.uid={$_SESSION['current_uid']};";
		$all_notifications = $conn->query($sql);

		if ($all_notifications->num_rows) {
			while ($row = $all_notifications->fetch_assoc()) {
				if ($row[type] == 'join_block') {
					array_push($a, $row[uid]);
					array_push($b, $row[rid]);
					echo "
						<div class='card'>
							<h3>Request to Join Block</h3>
							<p>{$row['last_name']} {$row['first_name']} wants to join as block member.</p>
							<p>{$row['request_content']} </p>
							<form action='' method='post'>
								<input type='submit' name='{$row['uid']}-approve-join' value='Approve' style='float: right; '/>
								<input type='submit' name='{$row['uid']}-dismiss-join' value='Dismiss' style='float: right; '/>

							</form>
						</div>
					";
				} else if ($row[type] == 'friend') {
					array_push($c, $row[uid]);
					array_push($d, $row[rid]);
					echo "
						<div class='card'>
							<h3>Friend Request</h3>
							<p>{$row['last_name']} {$row['first_name']} wants to add you.</p>
							<p>{$row['request_content']} </p>
							<form action='' method='post'>
								<input type='submit' name='{$row['uid']}-approve-add' value='Approve' style='float: right; '/>
								<input type='submit' name='{$row['uid']}-dismiss-add' value='Dismiss' style='float: right; '/>

							</form>
						</div>
					";
				}
			}
		}
		else {
			echo "
				<div class='card'>
				<p>no new notifications.</p>
				</div>";
		}

		for ($i = 0; $i < count($a); $i++) {
			if (isset($_POST["{$a[$i]}-approve-join"])) {
				approve_join_req($a[$i]);
				dismiss_join_req($b[$i]);
				echo '<script>window.location.href = "alert.php";</script>';
			} else if (isset($_POST["{$a[$i]}-dismiss-join"])) {
				dismiss_join_req($b[$i]);
				echo '<script>window.location.href = "alert.php";</script>';
			}
		}
		for ($i = 0; $i < count($c); $i++) {
			if (isset($_POST["{$c[$i]}-approve-add"])) {
				approve_add_req($c[$i]);
				dismiss_add_req($d[$i]);
				echo '<script>window.location.href = "alert.php";</script>';
			} else if (isset($_POST["{$c[$i]}-dismiss-add"])) {
				dismiss_add_req($d[$i]);
				echo '<script>window.location.href = "alert.php";</script>';
			}
		}
	}

	function approve_join_req($uid) {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "CALL APPROVE_BLOCK_REQ ({$uid})";
		$approve = $conn->query($sql);
	}

	function dismiss_join_req($rid) {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "delete from reqrecipient where uid={$_SESSION['current_uid']} and rid={$rid}";
		$approve = $conn->query($sql);
	}

	function approve_add_req($uid) {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "CALL APPROVE_FRIEND_REQ ({$uid}, {$_SESSION['current_uid']} )";
		$approve = $conn->query($sql);
	}

	function dismiss_add_req($rid) {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "delete from reqrecipient where uid={$_SESSION['current_uid']} and rid={$rid}";
		$approve = $conn->query($sql);
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
	<script src="/Users/yuhanchen/node_modules/vue/dist/vue.min.js"></script>
	<script src="/Users/yuhanchen/node_modules/vue-avatar/dist/vue-avatar.min.js"></script>
	<link rel="stylesheet" href="css/style.css">


</head>
<body style="background-color:#EFEFEF; ">

<div id="main">
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
		<form action="/action_page.php">
		<input type="text" placeholder="Search.." name="search">
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

		<a href="http://localhost:8000/alert.php"><i class="fa fa-fw fa-bell"></i><span class="badge"><?php echo $_SESSION['alert_count']?></span> </a>
		<a href="http://localhost:8000/message.php"><i class="fa fa-fw fa-comments"></i> </a>
		<a href="http://localhost:8000/profile.php"><i class="fa fa-fw fa-user"></i> </a>

</div>

<div class="row">
<div class="leftcolumn">
  
	<div class="card" style="background-color:#ddd; border:none">
	<h2>New Notifications</h2>

</div>
	<?php list_notifications($a, $b, $c, $d);
	?>
</div>
</div>

</div>
</div>


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
  

</script>
<script>
$(document).ready(function(){
  $('.toast').toast('show');
});
</script>
<script>
  import Avatar from 'vue-avatar-component'
  export default {
    components: { Avatar }
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDj0IDdq-STkufIp27dbqZgU8EClSWaXTc&callback=initMap"
  async defer></script>

  <avatar username="VueJS" initials="VUE" :size="100"></avatar>
</body>
</html>

