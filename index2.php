<?php
	session_start();


	function delete_my_join_req() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);
		$sql = "select * from request where uid={$_SESSION['current_uid']} and type='join_block'";
		$fetch = $conn->query($sql);

		$row = $fetch->fetch_assoc();
		$sql = "delete from reqrecipient where rid={$row[rid]};";
		$del = $conn->query($sql);

		$sql = "delete from request where rid={$row[rid]};";
		$del = $conn->query($sql);
	}

	function post_message() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$first_spc = strpos($_POST['recipient-name'], ' ');
		$fn = substr($_POST['recipient-name'], 0, $first_spc);
		$ln = substr($_POST['recipient-name'], $first_spc + 1);

		$sql = "select * from user where first_name='{$fn}' and last_name='{$ln}';";

		$get_user = $conn->query($sql);
		$row = $get_user->fetch_assoc();

		$sql = "CALL COMPOSE_MESSAGE('{$_POST['subject-name']}','{$_POST['message-text']}', {$_SESSION['current_uid']});";
		$compose = $conn->query($sql);

		// $sql = "select * from message where author={$_SESSION['current_uid']} order by timestamp desc limit 1;";
		// $last_msg = $conn->query($sql);
		// $m = $last_msg->fetch_assoc();
		// echo $sql;

		$sql = "CALL DIRECT_MESSAGE({$_SESSION['current_uid']}, {$row[uid]});";
		$direct = $conn->query($sql);

		$sql = "CALL DIRECT_MESSAGE({$_SESSION['current_uid']}, {$_SESSION['current_uid']});";
		$direct = $conn->query($sql);
	}

	function post_message_to_block() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);


		$sql = "CALL COMPOSE_MESSAGE('{$_POST['subject-name']}','{$_POST['message-text']}', {$_SESSION['current_uid']});";
		$compose = $conn->query($sql);

		$sql = "select x.* from (select u.uid, a.* from user u inner join address a on u.street = a.street and u.st_number = a.st_number) x inner join (select bid from user u inner join address a on u.street = a.street and a.st_number = u.st_number and u.uid = {$_SESSION['current_uid']}) y on x.bid = y.bid;";

		$uid_block = $conn->query($sql);
		while ($row = $uid_block->fetch_assoc()) {
			//if ($row[uid] != $_SESSION['current_uid']) {
				$sql = "CALL DIRECT_MESSAGE({$_SESSION['current_uid']}, {$row[uid]});";
				$direct = $conn->query($sql);
			//}
		}
	}

	function post_message_to_hood() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);


		$sql = "CALL COMPOSE_MESSAGE('{$_POST['subject-name']}','{$_POST['message-text']}', {$_SESSION['current_uid']});";
		$compose = $conn->query($sql);

		$sql = "select x.* from (select u.uid, a.* from user u inner join address a on u.street = a.street and u.st_number = a.st_number) x inner join (select hid from user u inner join address a on u.street = a.street and a.st_number = u.st_number and u.uid = {$_SESSION['current_uid']}) y on x.hid = y.hid;";

		$uid_block = $conn->query($sql);
		while ($row = $uid_block->fetch_assoc()) {
			//if ($row[uid] != $_SESSION['current_uid']) {
				$sql = "CALL DIRECT_MESSAGE({$_SESSION['current_uid']}, {$row[uid]});";
				$direct = $conn->query($sql);
			//}
		}
	}

?>

<?php echo delete_my_join_req();

if (isset($_POST['submit_post']) &&
	isset($_POST['all-block']) &&
	isset($_POST['message-text'])) {
	post_message_to_block();
}
else if (isset($_POST['submit_post']) &&
	isset($_POST['all-hood']) &&
	isset($_POST['message-text'])) {
	post_message_to_hood();
}
else if (isset($_POST['submit_post']) &&
	isset($_POST['recipient-name']) &&
	isset($_POST['message-text'])) {
	post_message();
}
?>

<div class="card">
	<h2>Hi, <?php echo " {$_SESSION['current_user_name']}" ?></h2>
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
					<form action='' method='post' autocomplete="off">
						<div class="form-group">
						<label  for="recipient-name" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Recipient:</label>
						<input class="autocomplete" id="myInput" type="text" name="recipient-name" placeholder="Recipient Name"><br><br>
						<input type="checkbox" name="all-block" style="width:30px; height: 25px;" >all block members<br>
						<input type="checkbox" name="all-hood" style="margin-left:-30px; width:30px; height: 25px;" >all hood members<br>
						<div id='dropdown' style="margin-top:8px; margin-left: 100px;"></div>
						</div>

						<div class="form-group" style="margin-top:-30px;">
						<label for="subject-name" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Subject:</label>
						<input type="text" class="form-control" id="subject-name" name="subject-name">
						</div>
						<div class="form-group">
						<label for="message-text" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Message:</label>
						<textarea class="form-control" id="message-text" name="message-text"></textarea>
						</div>
						<div class="form-group">
						<label for="exampleFormControlFile1" style="font-family: 'Lato', sans-serif;text-align:left;font-size:17px">Add photos:</label>
						<input type="file" class="form-control-file" id="exampleFormControlFile1">
						</div>

						<div class="modal-footer">
							<input style="color:black;background:#ddd" id='submit_post' type='submit' name='submit_post' value='Post Message' onclick="" />
							<input style="color:black;background:#ddd" id='submit_close' type='submit' name='submit_close' value='Close' onclick="" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- style="color:black;background:#ddd;font-size: 20px;cursor: pointer;width: 32%;padding: 20px;
	  font-weight: bold"  class="btn btn-primary" -->
<div class="card">

	<span style="display: inline;">
	<input type="button" onclick="location.href='http://localhost:8000/home.php?allmsg=true';" 
		value="All Messages" style="color:black;background:#ddd;font-size: 20px;cursor: pointer;width: 32%;padding: 20px; margin-right: 10px;
	  font-weight: bold"  class="btn btn-primary"></input>
	<input type="button" onclick="location.href='http://localhost:8000/home.php?blockmsg=true';" 
		value="Block Messages" style="color:black;background:#ddd;font-size: 20px;cursor: pointer;width: 32%;padding: 20px; margin-right: 10px;
	  font-weight: bold"  class="btn btn-primary"></input>
	<input type="button" onclick="location.href='http://localhost:8000/home.php?friendmsg=true';" 
		value="Friends Messages" style="color:black;background:#ddd;font-size: 20px;cursor: pointer;width: 32%;padding: 20px;
	  font-weight: bold"  class="btn btn-primary"></input>
	</span>
</div>


<?php include("./index3.php");?>


<!-- <div class="card">
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
</div> -->

