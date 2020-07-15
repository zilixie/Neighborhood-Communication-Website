<?php
session_start();

function add_friend() {
	$servername = "127.0.0.1";
	$username = "root";
	$password = "root";
	$dbname = "neighborhood";
	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$conn->query("CALL OPEN_REQUEST ('{$_SESSION['current_uid']}', 'friend', '{$_POST['message-text']}')");
	$sql = "CALL SEND_REQ_TO_MEM ('{$_SESSION['current_uid']}', '{$_POST['uid']}')";
	$_SESSION['sql'] = $sql;
	$conn->query($sql);
}

if (isset($_POST["add-friend-submit"]) && isset($_POST["message-text"])) {
	add_friend();
	//echo "<script type='text/javascript'>alert('Friend request has been sent.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>My Neigborhood</title>
  <meta charset="utf-8">
  <meta http-equiv="refresh" content="3;URL=relation1.php">
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
 				 <a href="http://localhost/home.php"><i class="fa fa-fw fa-home"></i> Home</a>
 				 <a href="#status"><i class="fa fa-fw fa-star"></i> Status</a>
  				<a href="#topics"><i class="fa fa-fw fa-globe"></i> Topics</a>
  				<a href="http://localhost/relation1.php"><i class="fa fa-fw fa-user"></i> Relations</a>

				</div>
		<div class="navbar">
		
				<a href="#home"> </a>
				<span style="margin-bottom:2px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
				<div class="search-container">
   					 <form action="/action_page.php">
      					<input type="text" placeholder="Search new friend by email" name="search">
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
				
				<a href="#status"><i class="fa fa-fw fa-bell"></i> </a>
				<a href="http://localhost/message.php"><i class="fa fa-fw fa-comments"></i> </a>
				<a href="http://localhost/profile.php"><i class="fa fa-fw fa-user"></i> </a>
			
</div> 	


	<div class="row">
  <div class="leftcolumn">
    <div class = "card">
   

    <div class="container">
    <div class = "name lead" style = "font-weight:700;font-size:25px; padding-top:5px"> Quest has been sent out. Please just wait for being approved.</div> 
    <div style = "display:inline"> You will back to Relation page after <span style = "display:inline" id="timer"></span> seconds. </div>	<i style = "display:inline"class="fa fa-fw fa-spinner"></i>
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

  document.getElementById('timer').innerHTML =
000 + ":" + 3;
startTimer();

function startTimer() {
  var presentTime = document.getElementById('timer').innerHTML;
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var s = checkSecond((timeArray[1] - 1));
  if(s==59){m=m-1}
  //if(m<0){alert('timer completed')}
  
  document.getElementById('timer').innerHTML =
    m + ":" + s;
  console.log(m)
  setTimeout(startTimer, 1000);
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}
	

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

