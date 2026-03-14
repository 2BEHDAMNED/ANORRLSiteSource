<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /login"));
	}

	include $_SERVER['DOCUMENT_ROOT']."/core/connection.php";

	$stmt = $con->prepare("SELECT * FROM `friends` WHERE (`sender` = ? OR `reciever` = ?) ORDER BY `status` ASC");
	$stmt->bind_param("ii", $user->id, $user->id);
	$stmt->execute();

	$result_stmt = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Your Friends - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/new/main.css">
		<link rel="stylesheet" href="/css/new/my/friends.css?v=1">
		<script src="/js/core/jquery.js"></script>
		<script src="/js/main.js?t=1771413807"></script>
		<script src="/js/friends.js?t=1771413807"></script>
	</head>
	<body>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2>Your Friends</h2>
					<div id="FriendsContainer">
						<?php if($result_stmt->num_rows != 0): ?>
						<table>
						<?php 
							$count = 0;
							while($row = $result_stmt->fetch_assoc()) {
								if($count == 0) {
									echo "<tr>";
								}

								$controlPanel = "";

								$friendo = $row['reciever'] == $user->id ? User::FromID($row['sender']) : User::FromID($row['reciever']);
								
								if($friendo == null) {
									// There's a person that's non existent somehow!
									continue;
								}

								$fid = $friendo->id;

								if($row['status'] == 1) {
									$controlPanel = <<<EOT
									<hr>
									<div id="ControlPanel" style="font-size: 11px">
										<a href="javascript:ANORRL.Friends.Remove($fid)">Remove</a>
									</div>
									EOT;
								} else {
									if($row['reciever'] == $user->id) {
										$controlPanel = <<<EOT
										<hr>
										<div id="ControlPanel" style="font-weight: bold;font-size: 13px">
											<a href="javascript:ANORRL.Friends.Accept($fid)">Accept</a>
											<span>|</span>
											<a href="javascript:ANORRL.Friends.Reject($fid)">Reject</a>
										</div>
										EOT;
									} else {
										$controlPanel = <<<EOT
										<hr>
										<div id="ControlPanel" style="font-weight: bold;font-size: 13px">
											<a href="javascript:ANORRL.Friends.Cancel($fid)">Cancel</a>
										</div>
										EOT;
									}
									
								}
								
								$profile = $friendo->setprofilepicture ? "profile" : "headshot";

								if(UserSettings::Get($user)->headshots_enabled) {
									$profile = "headshot";
								}

								$status = $friendo->IsOnline() ? "Online" : "Offline";
								
								$fname = $friendo->name;
								echo <<<EOT
								<td>
									<div class="Friend">
										<a href="/users/$fid/profile" title="$fname" target="_blank">
											<img src="/thumbs/$profile?id=$fid&sxy=100">
											<span><img src="/images/OnlineStatusIndicator_Is$status.png"> $fname</span>
										</a>
										$controlPanel
									</div>
										
								</td>
								EOT;

								$count++;

								if($count == $result_stmt->num_rows && $count%6 < 6) {
									for($i = 0; $i < 6-($count%6); $i++) {
										echo "<td style=\"width:142px;\"></td>";
									}
								}

								if($count%6 == 0) {
									echo "</tr>";
								}
							}
						?>
						</table>
						<?php endif ?>
					</div>
					
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>
