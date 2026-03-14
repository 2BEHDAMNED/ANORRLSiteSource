<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Welcome to ANORRL!</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/new/main.css">
		<link rel="stylesheet" href="/css/new/frontpage.css?v=1">
		<script src="/js/core/jquery.js"></script>
		<script src="/js/main.js?t=1771413807"></script>
		<script src="/js/showcase.js"></script>
	</head>
	<body>
		<div id="Container">
			<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<div id="IntroductionBox">
						<h2 style="width: -moz-available;">What is ANORRL?</h2>
						<p><b>ANORRL</b> is a 2016 "Old Roblox Revival" which aims to bring a fresh new look at the past!</p>
						<p><b>ANORRL</b> stands for <code>"<b>AN</b>other <b>O</b>ld <b>R</b>oblox <b>R</b>evival <b>L</b>ol"</code></p>
						<div style="margin-left: 5px;">
							<h3 style="width: -moz-available;">So what is a "Old Roblox Revival"</h3>
							<p>
								It is a project that aims to recreate and bring life into relatively old clients that ROBLOX has created in the past.<br>
								<b>Please note that</b>: These revivals do tend to be private (along with this one) as to ensure the maximum security because not only are These
								clients old, they are also insecure!
							</p>
							<p>
								However, note that this <i>IS</i> a <b>friends-only</b> revival. The clients you see here were built by me!!!! (So you can trust it I think!)
							</p>
						</div>
						
					</div>
					<div id="ShowcaseContainer">
						<h3>Showcase</h3>
						<div id="ShowcaseBox">
							<img id="Showcase" src="/media/showcase_anorrlmonday.png">
							<video id="Showcase" src="" style="display:none" controls></video>
							<div id="Carousel">
								<img title="From grace, with Isamarah" class="selected" src="/media/showcase_anorrlmonday.png">
								<img title="From grace, with Markinator" src="/media/showcase_amicute.png">
								<img title="From grace, with DarthRevan" src="/media/showcase_darthrevanamogus.png">
								<img title="From Markinator" src="/media/showcase_penguin.png">
								<img title="From Markinator" src="/media/showcase_explosion.jpg">
								<img title="From grace, with Markinator" src="/media/showcase_dancecuck.jpg">
							</div>
						</div>
					</div>
					<br style="clear:both">

					<div id="NewUsersContainer">
						<h3>New Users!</h3>
						<table id="NewUsersBox">
							<?php 
								$users = UserUtils::GetLatestUsers(100);
								$users_count = count($users);
							?>
							<tr>
								<?php  
									foreach($users as $user) {
										if($user instanceof User) {
											$user_id = $user->id;
											$user_name = $user->name;
											$profile = $user->setprofilepicture ? "profile" : "headshot";
											if(UserSettings::Get(UserUtils::RetrieveUser())->headshots_enabled && UserUtils::RetrieveUser() != null) {
												$profile = "headshot";
											}
											echo <<<EOT
												<td>
													<div class="User" title="$user_name">
														<a href="/users/$user_id/profile">
															<img src="/thumbs/$profile?id=$user_id&sxy=100">
															<span>$user_name</span>
														</a>
													</div>
												</td>
											EOT;
										}
									}

									if($users_count < 7) {
										$count = 7 - $users_count;
										for($i = 0; $i < $count; $i++) {
											echo <<<EOT
												<td></td>
											EOT;
										}
 									}
								?>
							</tr>
							<tr id="Other">
								<?php 
									foreach($users as $user) {
										if($user instanceof User) {
											$user_id = $user->id;
											$user_name = $user->name;
											$profile = $user->setprofilepicture ? "profile" : "headshot";
											if(UserSettings::Get(UserUtils::RetrieveUser())->headshots_enabled && UserUtils::RetrieveUser() != null) {
												$profile = "headshot";
											}
											echo <<<EOT
												<td>
													<div class="User" title="$user_name">
														<a href="/users/$user_id/profile">
															<img src="/thumbs/$profile?id=$user_id&sxy=100">
															<span>$user_name</span>
														</a>
													</div>
												</td>
											EOT;
										}
									}

									if($users_count < 7) {
										$count = 7 - $users_count;
										for($i = 0; $i < $count; $i++) {
											echo <<<EOT
												<td></td>
											EOT;
										}
 									}
								?>
							</tr>
						</table>
					</div>
					<div style="margin: 10px auto;width: 60%;"><img src="/images/epicbazookaquote.png" style="width: 100%;border: 2px solid black;"></div>
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>
