<?php
session_start();
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
  background-color: inherit;
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

.badge {
  position: absolute;
  top: -7px;
  right: -8px;
  padding: 1px 5px;
  border-radius: 50%;
  background-color: red;
  font-family: "Lato", sans-serif;
  color: white;
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
 				 <a href="http://localhost/home.php"><i class="fa fa-fw fa-home"></i> Home</a>
 				 <a href="#status"><i class="fa fa-fw fa-star"></i> Status</a>
  				<a href="#topics"><i class="fa fa-fw fa-globe"></i> Topics</a>
  				<a href="http://localhost/relation.php"><i class="fa fa-fw fa-user"></i> Relations</a>

				</div>
		<div class="navbar">
		
				<a href="#home"> </a>
				<span style="font-size:30px;margin-bottom:2px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
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
				
				<a href="http://localhost/albert.php"><i class="fa fa-fw fa-bell"></i> <span class="badge">3</span></a>
				<a href="http://localhost/message.php"><i class="fa fa-fw fa-comments"><span class="badge">6</span></i> </a>
				<a href="http://localhost/profile.php"><i class="fa fa-fw fa-user"></i> </a>
			
</div> 	


	<div class="row">
  <div class="leftcolumn">
    <div class="card">
	<?php

    ?>

	  <h2>Dear Yuhan,</h2>
	 
	  <button style="color:black;background:#ddd;font-size: 20px;cursor: pointer;width: 100%;padding: 20px;
  font-weight: bold"  class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Post your message here!</button>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
	    <a style = "color:#A693B0;left:3px;margin-top:7px;height:18px; padding-right:10px" href="#"><i class="fa fa-envelope"></i></a>
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
		  <div class="form-group">
			<label for="recipient-name" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Recipient:</label>
    		<select class="form-control" id="exampleFormControlSelect1">
      		<option>1</option>
      		<option>2</option>
      		<option>3</option>
      		<option>4</option>
      		<option>5</option>
    		</select>
          </div>
          <div class="form-group">
            <label for="subject-name" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Subject:</label>
            <input type="text" class="form-control" id="subject-name">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
		  </div>
  			<div class="form-group">
    			<label for="exampleFormControlFile1" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Add photos:</label>
   				<input type="file" class="form-control-file" id="exampleFormControlFile1">
 			 </div>

        </form>
      </div>
      <div class="modal-footer">
        <button style="color:black;background:#ddd" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button style="color:black;background:#ddd" type="button" class="btn btn-primary">Post message</button>
      </div>
    </div>
  </div>
</div>
    </div>
    <div class="card">
	<div class="container mt-3">
  <div class="media border p-3">
    <img src="image_user.png" alt="John Doe" class="mr-3 mt-3 rounded-circle" style="width:60px;">
    <div class="media-body">
      <h4>John Doe <small><i>Posted on February 19, 2016</i></small></h4>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      <div class="media p-3">
        <img src="image_user.png" alt="Jane Doe" class="mr-3 mt-3 rounded-circle" style="width:45px;">
        <div class="media-body">
          <h4>Jane Doe <small><i>Posted on February 20 2016</i></small></h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
      </div>  
    </div>
  </div>
</div>
    </div>
	<div class="card">
      <h2>Topics I follow</h2>
      <div class="fakeimg" style="height:200px;">Image</div>
      <p>Some text..</p>
      <p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
    </div>
  </div>
  <div class="rightcolumn">
  <div class="card">
      <h2>Neighbor info</h2>
	  
	  <div id="map" style="display:block;"></div>
      <p>Some text about me in culpa qui officia deserunt mollit anim..</p>
</div>
    <div class="card">
      <h3>Popular Post</h3>
      <div class="fakeimg">Image</div><br>
      <div class="fakeimg">Image</div><br>
      <div class="fakeimg">Image</div>
    </div>
    <div class="card">
      <h3>Recent activity</h3>
      <p>Some text..</p>
    </div>
  </div>
</div>

<div class="footer">
  <h2>Footer</h2>
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

