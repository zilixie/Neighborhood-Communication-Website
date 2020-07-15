<?php
	session_start();

	function help($mid) {
		echo "
		<div class='modal fade' id='modal-{$mid}' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
		<div class='modal-dialog' role='document'>
			<div class='modal-content'>
					<div class='modal-header'>
						<a style = 'color:#A693B0;left:3px;margin-top:7px;height:18px; padding-right:10px' href='#'><i class='fa fa-envelope'></i></a>
						<h5 class='modal-title' id='exampleModalLabel'>Reply</h5>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'>
						<span aria-hidden='true'>&times;</span>
						</button>
					</div>
					<div class='modal-body'>
						<form action='' method='post' autocomplete='off'>

							<div class='form-group'>
							<label for='message-text' class='col-form-label' style='font-family: 'Lato', sans-serif;text-align:left;font-size:17px'>Message:</label>
							<textarea class='form-control' id='message-text' name='reply-text'></textarea>
							<input type='hidden' name='reply_to_mid' value='{$mid}'/>
							</div>

							<div class='modal-footer'>
								<input style='color:black;background:#ddd' id='submit_reply' type='submit' name='submit_reply' value='Reply Message' onclick='' />
								<input style='color:black;background:#ddd' id='submit_close' type='submit' name='submit_close' value='Close' onclick='' />
							</div>
						</form>
					</div>
				</div>
			</div></div>
		";
	}

	function delete_my_join_req() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "delete from request where uid={$_SESSION['current_uid']} and type='join_block'";
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

	function search_msg_send_to_me() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$seach_key = $_POST['search'];

		$sql = "CALL SEARCH_MESSAGE({$_SESSION['current_uid']}, '{$seach_key}')";
		$search_msg_to_me = $conn->query($sql);
		$sql = "select * from temp m inner join user u on u.uid = m.author;";
		$search_msg_to_me = $conn->query($sql);

		while ($row = $search_msg_to_me->fetch_assoc()) {
			$cur_mid = $row[mid];
			//echo "<h1>{$cur_mid}</h2>";

			$sql2 = "select * from message m inner join reply r on r.mid_reply = m.mid where r.mid_initial = {$cur_mid}";
			$reply_to_this_msg = $conn->query($sql2);

			$full_name = ucfirst($row[first_name]). " ". ucfirst($row[last_name]);

			echo "
			<div class='card'>
				<div class='container mt-3'>
					<div class='media p-10'>
						<img src='image_user.png' alt='{$full_name}' class='mr-3 mt-3 rounded-circle' style='width:60px;''>
						<div class='media-body'>
							<h4>{$full_name} <small><i>{$row[timestamp]}</i></small></h4>
							<p>{$row[subject]}</p>
							<p>{$row[text_content]}</p>

				  			<button style='color:black;background:#ddd;font-size: 18px;cursor: pointer;width: 20%; font-weight: bold'
							class='btn btn-default btn-block p-1' data-toggle='modal' data-target='#modal-{$cur_mid}' data-whatever='@getbootstrap' >Reply</button>";


			while ($row2 = $reply_to_this_msg->fetch_assoc()) {
				$cur_reply_id = $row2[mid_reply];
				$sql3 = "select * from message m inner join user u on m.author = u.uid where m.mid = {$cur_reply_id}";
				$tmp = $conn->query($sql3);
				$row3 = $tmp->fetch_assoc();

				$full_reply_name = ucfirst($row3[first_name]). " ". ucfirst($row3[last_name]);
				echo "
				<div class='media p-3'>
					<img src='image_user.png' alt='{$full_reply_name}' class='mr-3 mt-3 rounded-circle' style='width:45px;''>
					<div class='media-body'>
					<h4>{$full_reply_name} <small><i>{$row2[timestamp]}</i></small></h4>
					<p>{$row2[text_content]}</p>
					</div>
				</div>
				";
			}
			echo "</div></div></div></div>";
			help($row[mid]);
		}
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

	<div class="card" style="background-color:#ddd; border:none">
	<?php echo "<h2>Search Results for: {$_POST['search']}</h2>" ?>

	<?php search_msg_send_to_me(); ?>
</div>


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

