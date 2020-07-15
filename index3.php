<?php
	session_start();


	function make_reply() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "CALL COMPOSE_MESSAGE('reply','{$_POST['reply-text']}', {$_SESSION['current_uid']});";
		$compose = $conn->query($sql);

		// $sql = "select * from message where author={$_SESSION['current_uid']} order by timestamp desc limit 1;";
		// $last_msg = $conn->query($sql);
		// $m = $last_msg->fetch_assoc();
		// echo $sql;


		$sql = "CALL REPLY_MESSAGE({$_SESSION['current_uid']}, {$_POST["reply_to_mid"]});";
		$direct = $conn->query($sql);
	}

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

	function msg_send_to_me() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "select * from messagerecipient mr inner join message m on mr.mid = m.mid inner join user u on u.uid = m.author where mr.receive_uid={$_SESSION['current_uid']} and m.subject <> 'reply' order by timestamp desc;";
		$msg_to_me = $conn->query($sql);


		while ($row = $msg_to_me->fetch_assoc()) {
			$cur_mid = $row[mid];
			$sql2 = "select * from message m inner join reply r on r.mid_reply = m.mid where r.mid_initial = {$cur_mid}";
			$reply_to_this_msg = $conn->query($sql2);

			$full_name = ucfirst($row[first_name]). " ". ucfirst($row[last_name]);
			echo "
			<div class='card'>
				<div class='container mt-3'>
					<div class='media p-10'>
						<img src='image_user.png' alt='{$full_name}' class='mr-3 mt-3 rounded-circle' style='width:60px;''>
						<div class='media-body'>
							<h4>{$full_name} <small><i>{$row[timestamp]}</i></small></h4><p>";
			echo htmlspecialchars("{$row[subject]}"); echo "</p><p>";
			echo htmlspecialchars("{$row[text_content]}");echo "</p>";

				  	echo		"<button style='color:black;background:#ddd;font-size: 18px;cursor: pointer;width: 20%; font-weight: bold'
							class='btn btn-default btn-block p-1' data-toggle='modal' data-target='#modal-{$cur_mid}' data-whatever='@getbootstrap' >Reply</button>";

			while ($row2 = $reply_to_this_msg->fetch_assoc()) {
				$cur_reply_id = $row2[mid_reply];
				$sql3 = "select * from message m inner join user u on m.author = u.uid where m.mid = {$cur_reply_id}";
				$tmp = $conn->query($sql3);
				$row3 = $tmp->fetch_assoc();

				$full_reply_name = ucfirst($row3[first_name]). " ". ucfirst($row3[last_name]);
				echo "
				<div class='media p-3'>
					<img src='image_user.png' alt='{$full_reply_name}' class='mr-3 mt-3 rounded-circle' style='width:45px;'>
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


	function block_msg_send_to_me() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "CALL BLOCK_FEED({$_SESSION['current_uid']})";
		$block_msg_to_me = $conn->query($sql);
		$sql = "select * from temp m inner join user u on u.uid = m.author;";
		$block_msg_to_me = $conn->query($sql);

		while ($row = $block_msg_to_me->fetch_assoc()) {
			$cur_mid = $row[mid];
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

	function friend_msg_send_to_me() {
		$servername = "127.0.0.1";
		$username = "root";
		$password = "root";
		$dbname = "neighborhood";
		$conn = new mysqli($servername, $username, $password, $dbname);

		$sql = "CALL FRIEND_FEED({$_SESSION['current_uid']})";
		$friend_msg_to_me = $conn->query($sql);
		$sql = "select * from temp2 m inner join user u on u.uid = m.author;";
		$friend_msg_to_me = $conn->query($sql);

		while ($row = $friend_msg_to_me->fetch_assoc()) {
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


	if (isset($_POST["submit_reply"]) && isset($_POST["reply_to_mid"])) {
		make_reply();
	}

	if (isset($_GET['allmsg'])) {
		msg_send_to_me();
	}
	else if (isset($_GET['blockmsg'])) {
		block_msg_send_to_me();
	}
	else if (isset($_GET['friendmsg'])) {
		friend_msg_send_to_me();
	} 
	else {
		msg_send_to_me();
	}

			// while ($row2 = $reply_to_this_msg->fetch_assoc()) {
			// 	echo "

			// 	<div class='media border p-3'>
			// 	<img src='image_user.png' alt='{$full_name}' class='mr-3 mt-3 rounded-circle' style='width:60px;''>
			// 	<div class='media p-3'>
			// 	<h4>{$full_name} <small><i>{$row2[timestamp]}</i></small></h4>
			// 	<p>{$row2[text_content]}</p>
			// 	</div>

			// 		";
			// }

?>
<div class="card">
	<div class="container mt-3">
	
			<div class="media p-10">
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

