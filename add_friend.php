<?php
session_start();
  
	function serach_friends() {
	$servername = "127.0.0.1";
	$username = "root";
	$password = "root";
	$dbname = "neighborhood";
	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}
	$seach_key = $_POST['search'];
	$search_info = $conn->query("SELECT first_name, last_name, uid from USER WHERE USER.email like '$seach_key%' or USER.first_name like'$seach_key%' or USER.last_name like'$seach_key%' ");
	if ($search_info->num_rows > 0) {
    while($row = $search_info->fetch_assoc()) {
            $full_name = ucfirst($row[first_name])." ".ucfirst($row[last_name]);
            echo  "
                <li class='list-group-item' style='border-left:none;border-right:none'>
                <div class='row w-100' style='border-left:none;border-right:none'>
                    <div style='position: relative; left:10px' class='col-12 col-sm-6 col-md-3 px-0'>   
                        <img src='http://demos.themes.guide/bodeo/assets/images/users/m101.jpg' alt='Mike Anamendolla' class='rounded-circle mx-auto d-block img-fluid'>                
                    </div>

                    <div style='position: relative; top:30px; left:50px' class='col-12 col-sm-6 col-md-9 text-center text-sm-left'>           
                        <label class='name lead'>{$full_name}</label>
                    </div>

                    <div style='position: relative; bottom: 80px; left:280px' class='col-12 col-sm-6 col-md-9 text-center text-sm-left'>
                        <button style='background:none;font-size: 20px;cursor: pointer;padding: 0px;font-weight: bold'  class='btn btn-primary' data-toggle='modal' data-target='#exampleModal-{$row[uid]}' data-whatever='@getbootstrap'><a style = 'color:#A693B0;left:3px;margin-top:7px;padding-right:10px' ><i class='fa fa-plus'></i></a></button>

                        <div class='modal fade' id='exampleModal-{$row[uid]}' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <a style = 'color:#A693B0;left:3px;margin-top:7px;height:18px; padding-right:10px' href='#'></a>
                                    <h5 class='modal-title' id='exampleModalLabel-{$row[uid]}'>Add Neighbors</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body'>
                                    <form autocomplete='off' id='add_friends' action='add_neighbor_after.php' method='post' accept-charset='UTF-8' enctype='multipart/form-data'>

                                    <p>Do you want to add {$full_name} as your neighborhood?</p>
                                    <div class='modal-footer'>
                                        <input class='form-control' id='uid' name = 'uid' type = 'hidden' value = {$row[uid]}>
                                        <button style='color:black;font-weight:300;background:#ddd' type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                        <input style='color:black;font-weight:500;background:#ddd' id='submit' value='Confirm' type='submit' class='btn btn-primary'></input>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>

                        <button style='background:none;font-size: 20px;cursor: pointer;padding: 0px;font-weight: bold'  class='btn btn-primary' data-toggle='modal' data-target='#exampleModal1-{$row[uid]}' data-whatever='@getbootstrap'><a style = 'color:#A693B0;left:3px;margin-top:7px;padding-right:10px'><i class='fa fa-user-plus'></i></a></button>
                        
                        <div class='modal fade' id='exampleModal1-{$row[uid]}' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <a style = 'color:#A693B0;left:3px;margin-top:7px;height:18px; padding-right:10px' href='#'></a>
                                    <h5 class='modal-title' id='exampleModalLabel'>Add friends</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body'>
                                    <form autocomplete='off' id='add_friends' action='add_friend_after.php' method='post' accept-charset='UTF-8' enctype='multipart/form-data'>

                                    <div class='form-group'>
                                        <input class='form-control' id='uid' name = 'uid' type = 'hidden' value = {$row[uid]}>
                                        <label for='message-text' class='col-form-label' style='font-family: 'Lato', sans-serif;text-align:left;font-size:17px'>Message:</label>
                                        <textarea class='form-control' id='message-text' name = 'message-text' placeholder = 'Leave some message that helps others identify you.'></textarea>
                                    </div>

                                    <div class='modal-footer'>
                                        <button style='color:black;font-weight:300;background:#ddd' type='botton' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                        <input style='color:black;font-weight:500;background:#ddd' name = 'add-friend-submit' id='submit' value='Confirm' type='submit' class='btn btn-primary'></input>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>

                    </div>
                </div>
                </li>";
        }
	}
}

// function add_friend() {
// 	$servername = "127.0.0.1";
// 	$username = "root";
// 	$password = "root";
// 	$dbname = "neighborhood";
// 	$conn = new mysqli($servername, $username, $password, $dbname);

// 	if ($conn->connect_error) {
// 		die("Connection failed: " . $conn->connect_error);
// 	}

// 	$conn->query("CALL OPEN_REQUEST ('{$_SESSION['current_uid']}', 'friend', '{$_POST['message-text']}')");
// 	$sql = "CALL SEND_REQ_TO_MEM ('{$_SESSION['current_uid']}', '{$_POST['uid']}')";
// 	$_SESSION['sql'] = $sql;
// 	$conn->query($sql);
// }

// if (isset($_POST["add-friend-submit"]) && isset($_POST["message-text"])) {
// 	add_friend();
// 	//echo "<script type='text/javascript'>alert('Friend request has been sent.'); window.location = 'relation1.php';</script>";
// }




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
             <form autocomplete="off" id='update' method='post' accept-charset='UTF-8' enctype="multipart/form-data" action="/add_friend.php">
                <input type="text" placeholder="Search people by email or name" id = "search" name="search">
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
			<div class = "card">
				<div class="container">
				<div class = "name lead" style = "font-weight:700;font-size:25px; padding-top:5px"> Results</div> 
				<!-- contacts card -->
				<div class="card card-default" id="card_contacts" style="border-left:none;border-right:none">
				<div id="contacts" class="panel-collapse collapse show" aria-expanded="true" style="border-left:none;border-right:none">
				<ul class="list-group pull-down" style="border-left:none;border-right:none" id="contact-list">
				<?php serach_friends(); ?>
				</ul>
				</div>
				</div>
				</div>
			</div>
		</div>

 
 		<div class="rightcolumn">
			<div class="card2">
				<h2>Neighbor info</h2>
				<!-- <?php echo $_SESSION['sql'];?> -->
				<div id="map"></div>
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
				<p>Some text about me in culpa qui officia deserunt mollit anim..</p>
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


</script>
<script>
  import Avatar from 'vue-avatar-component'
  export default {
    components: { Avatar }
  }
</script>

</body>
</html>