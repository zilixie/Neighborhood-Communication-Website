<?php
	session_start();

	function send_req() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "CALL OPEN_REQUEST({$_SESSION['current_uid']}, 'join_block', '{$_POST['message-text']}');";
		$sendreq = $conn->query($sql);
		if ($sendreq) {
			//$sql = "CALL SEND_REQ_TO_BLOCK ({$_SESSION['current_uid']})";
			//echo $sql;
			$sendreq2block = $conn->query("CALL SEND_REQ_TO_BLOCK ({$_SESSION['current_uid']})");
			if ($sendreq2block) {
				echo "<p>request has been send.</p>";
				echo "<p>0/3 approvals have been collected.</p>";
			}
		}
	}

	function req_has_been_send() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "select * from request where type='join_block' and uid={$_SESSION['current_uid']} and num_approved <=3;";
		$sendreq_check = $conn->query($sql);
		if ($sendreq_check->num_rows > 0) {
			$row = $sendreq_check->fetch_assoc();
			return $row['num_approved'];
		}
		else{
			return -1;
		}
	}
?>
<div class="card">
	<h2>Hi, <?php echo " {$_SESSION['current_user_name']}" ?>  </h2>

	<p>You are not a member in your block yet. </p>
	<?php
		$num_app = req_has_been_send();
		if ($num_app >= 0) {
			echo "<p>{$num_app}/3 approvals have been collected.</p>";
		}
		else if (isset($_POST['submit_send'])) {
			send_req();
			//echo "<script type='text/javascript'>alert('Request has been sent.');</script>";
		}
		else {
			echo "<p>Please click the send request to finalize you registration. </p>";
			echo "
	<button style='color:black;background:#ddd;font-size: 20px;cursor: pointer;width: 100%;padding: 20px;
	  font-weight: bold'  class='btn btn-primary' data-toggle='modal' data-target='#exampleModal' data-whatever='@getbootstrap'>Send request here!</button>
			";
		}
	?> 


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

					<form autocomplete="off" id='sendrequest' action='send_req_after.php' method='post' accept-charset='UTF-8' enctype="multipart/form-data">
						<div class="form-group">
						<label for="message-text" class="col-form-label" style="font-family: 'Lato', sans-serif;text-align:left;">Message:</label>
						<textarea class="form-control" id="message-text" name="message-text" placeholder="Say something that introduce you."></textarea>
						</div>

						<div class="modal-footer">
							<input style="color:black;background:#ddd" id='submit_send' type='submit' name='submit_send' value='Send Request' onclick="" />
							<input style="color:black;background:#ddd" id='submit_close' type='submit' name='submit_close' value='Close' onclick="" />
							<!--<button style="color:black;background:#ddd" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button style="color:black;background:#ddd" type="button" class="btn btn-primary">Send Request</button>-->
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- <div class="card">
        <h2>My family</h2>
        <div class="fakeimg" style="height:200px;">Image</div>
        <p>Some text..</p>
        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
      </div> -->
