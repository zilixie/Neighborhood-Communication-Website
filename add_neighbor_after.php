<?php
session_start();


function add_neighbor() {
  $servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "neighborhood";
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$conn->query("CALL ADD_NEIGHBOR ('{$_SESSION['current_uid']}', '{$_POST['uid']}')");

}

if (isset($_POST['uid']))
  {
  add_neighbor();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>My Neigborhood</title>
  <meta charset="utf-8">
  <meta http-equiv="refresh" content="3;URL=relation_a.php">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="/Users/yuhanchen/node_modules/vue/dist/vue.min.js"></script>
	<script src="/Users/yuhanchen/node_modules/vue-avatar/dist/vue-avatar.min.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<style>
body {
  font-family: "Lato", sans-serif;
  transition: background-color .5s;
  background: #ddd;
}

.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
  
}

.sidenav a:hover {
  color: #f1f1f1;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

/* Create two unequal columns that floats next to each other */
/* Left column */
.user_info {
  padding: 10px;
  font-size: 40px;
  text-align: center;
}

.leftcolumn {   
  float: left;
  width: 70%;
  padding : 25px;
}

/* Right column */
.rightcolumn {
  float: left;
  width: 30%;
  padding : 25px;
}

/* Fake image */
.fakeimg {
  background-color: #aaa;
  width: 100%;
  padding: 20px;
}

/* Add a card effect for articles */
.card {
   background-color: white;
   padding: 10px;
   margin-top: 20px;
   margin-left: 55px;
   margin-right: 20px;
}

.btn {
  border: none;
  background-color: #A693B0;
  font-size: 20px;
  cursor: pointer;
  width: 100%;
  padding: 20px;
  font-weight: bold;

}

.btn :hover {background:#A693B0;}

.success {color:#ddd;}


/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Footer */
.footer {
  padding: 20px;
  text-align: center;
  background: #ddd;
  margin-top: 20px;
}

.navbar {
  width: 100%;
  background-color: #A693B0;
  overflow: visible;
  margin: 0;

}

.navbar a {
  float: none;
  position:relative;
  right: 12px;
  color: white;
  text-decoration: none;
  font-size: 27px;
  margin: 0;
}

.navbar .search-container {
  float: right;
}

.navbar input[type=text] {
  padding: 10.5px;
  margin-top: 2px;
  font-size: 17px;
  border: none;
  border-radius: 3px;
  width:370px;
}

.navbar .search-container button {
  float: right;
  padding: 8px 10px;
  margin-top: 2px;
  margin-right: 16px;
  background: #ddd;
  font-size: 17px;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}

.navbar .search-container button:hover {
  background: #ccc;
}

#map {
	padding: 80px;
   margin-top: 20px;
		}
		/* Optional: Makes the sample page fill the window. */
		html, body {
		height: 100%;
		margin: 0;
		padding: 0;
		}



#main {
  transition: margin-left .5s;
  padding: 0px;
  background: #ddd;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
  .navbar.search-container {
    float: none;
  }
  .navbar a, .navbar input[type=text], .topnav .search-container button {
    float: none;
    display: block;
    text-align: left;
    width: 100%;
    margin: 0;
    padding: 14px;
  }
  .navbar input[type=text] {
    border: 1px solid #ccc;  
  }
}
</style>

</head>
<body style="background-color:#EFEFEF; ">

<div id="main">
  

        <div id="mySidenav" class="sidenav">
  				<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
 				 <a href="http://localhost:8000/home.php"><i class="fa fa-fw fa-home"></i> Home</a>
 				 <a href="#status"><i class="fa fa-fw fa-star"></i> Status</a>
  				<a href="#topics"><i class="fa fa-fw fa-globe"></i> Topics</a>
  				<a href="http://localhost:8000/relation_a.php"><i class="fa fa-fw fa-user"></i> Relations</a>

				</div>
		<div class="navbar">
		
				<a href="#home"> </a>
				<span style="font-size:30px;margin-bottom:2px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
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
				<a href="http://localhost:8000/message.php"><i class="fa fa-fw fa-comments"></i> </a>
				<a href="http://localhost:8000/profile.php"><i class="fa fa-fw fa-user"></i> </a>
			
</div> 	


	<div class="row">
  <div class="leftcolumn">
    <div class = "card">
   

    <div class="container">
    <div class = "name lead" style = "font-weight:700;font-size:25px; padding-top:5px">Neighbor successfully added!</div> 
    <div style = "display:inline"> You will back to realtion page after <span style = "display:inline" id="timer"></span> seconds. </div>	<i style = "display:inline"class="fa fa-fw fa-spinner"></i>
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

