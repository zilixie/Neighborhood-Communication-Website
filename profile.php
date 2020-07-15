<?php

  session_start();
  function update_basic_info() 
  {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
    
		if ($_POST['address'] != ""){
			$_SESSION['current_address'] = $_POST['address'];
			$firpos = strpos($_POST['address']," ");
			$st_number = substr($_POST['address'], 0, $firpos);
			$street = substr($_POST['address'], $firpos+1);

			$sql = "CALL UPDATE_ADDRESS ('$street', '$st_number', '{$_SESSION['current_uid']}');";
			echo $sql;
			$update_address = $conn->query($sql);
			if ($update_address) {
			echo '<script>window.location.href = "profile.php";</script>';
			} else {
			echo "<p style='color:red;'> update failed </p>";
			}
		}

		if($_POST['email'] != ""){
			$_SESSION['current_user_email'] = $_POST['email'];
			$email =  $_POST['email'];
			$sql = "CALL UPDATE_EMAIL ('$email', '{$_SESSION['current_uid']}');";
			echo $sql;
			$update_email = $conn->query($sql);
			if ($update_email) {
				echo '<script>window.location.href = "profile.php";</script>';
			} else {
				echo "<p style='color:red;'> update failed </p>";
			}
		}
  }
  
  function find_neighbor()
  {
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
  function get_block_user()
  {
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
    if($block_peo_num)
    {
      echo "$result1[cu]";
    }
  }
  function get_hood_user()
  {
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
    if($block_peo_num)
    {
      echo "$result1[cu]";
    }

  }


	if (($_POST['email'] != "" || $_POST['address'] != "") && isset($_POST['edit-submit'])) {
		update_basic_info();
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
    <div class="card">
    <span>  <h2 style ="display:inline">Basic Information</h2>
      <button style="background:none; width: 20px; display:inline; font-size: 20px;cursor: pointer;padding: 0px;font-weight: bold"  class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap"><a style = "color:#A693B0;display:inline"><i class="fa fa-edit"></i></a></button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
	                              <a style = "color:#A693B0;left:3px;margin-top:7px;height:18px; padding-right:10px" href="#"></a>
                                 <h5 class="modal-title" id="exampleModalLabel">Edit basic info</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                 </button>
                               </div>
                             <div class="modal-body">
                             <form  autocomplete="off" id='update' action='profile.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="subject-name" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Email</label>
                                    <input type="text" class="form-control" id="email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="subject-name" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                                <div class="form-group">
                                  <label for="message-text" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Introduction</label>
                                    <textarea class="form-control" id="introduction" name="introduction"></textarea>
		                             </div>
                              
                              </div>
                            <div class="modal-footer">
                             <button style="color:black;font-weight:300;background:#ddd" type="botton" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input style="color:black;font-weight:500;background:#ddd" id='submit' value='submit' type="submit" name="edit-submit" class="btn btn-primary"></input>
                            </form>
                          </div>
                           </div>
                            </div>  
                          </div> 
</span>        
    <img src='image_user.png' alt='{$full_reply_name}' class='mx-auto d-block rounded-circle' style='width:180px;'>

    <div><i class="fa fa-fw fa-envelope" style="color:#A693B0"></i>
    <?php echo " {$_SESSION['current_user_email']}" ?>  </div>
   <div> <i class="fa fa-fw fa-map" style="color:#A693B0"></i>
    <?php echo " {$_SESSION['current_address']}" ?> </div>
   <div> <i class="fa fa-fw fa-unlock" style="color:#A693B0"></i>
    <?php echo " {$_SESSION['current_status']}" ?> </div>
    <div><i class="fa fa-fw fa-heart" style="color:#A693B0"></i>  You can write your introduction here.</div>
    </div>
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
	  <div id="map" style="display:block;"></div>
      <div>Block people number: <?php get_block_user();?></div>        
      <div>Hood people number: <?php get_hood_user();?></div>
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
		zoom: 14
		});
	}
	
  new Vue({
  components: {
    'avatar': Avatar
  }
})
  
	
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEp3GPAv657jGVqiFWCV6iPkLiQW6vHXU&callback=initMap"
	async defer></script>
	
</body>
</html>

