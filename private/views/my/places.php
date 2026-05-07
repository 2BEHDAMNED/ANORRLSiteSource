<?php
	use anorrl\Page;
	use anorrl\utilities\FileSplasher;
	use anorrl\utilities\ClientDetector;
	use anorrl\utilities\UtilUtils;

	$user = SESSION->user;

	$isclient = ClientDetector::IsAClient();
	if(!$isclient)
		die("Hey something isn't right here... You sure you're using the right studio?");

	$extra_places = [];
	if(isset($_GET['filepath'])) {
		$raw_crap = explode("&", str_replace("/my/places?", "", $_SERVER['REQUEST_URI']));

		$filenames = [];
		$filepaths = [];

		foreach($raw_crap as $bit) {
			if(strlen(trim($bit)) != 0) {
				if(str_starts_with($bit, "filename="))
					$filenames[] = urldecode(str_replace("filename=", "", $bit));
				elseif(str_starts_with($bit, "filepath="))
					$filepaths[] = urldecode(str_replace("filepath=", "", $bit));
			}
		}

		if(count($filenames) == count($filepaths)) {
			for($i = 0; $i < count($filenames); $i++) {
				$extra_places[] = [
					"name" => $filenames[$i],
					"path" => $filepaths[$i]
				];
			}
		}
	}
	//print_r($extra_places);
	

	$places = $user->getPlaces(false);
	$teamplaces = $user->getPlaces(true);

	$domain = CONFIG->domain;

	$splash = new FileSplasher("didyouknow")->getRandomSplash();

	$page = new Page("ANORRL Studio");
	$page->clearAll();
	$page->addScript("/js/core/jquery.js");
	$page->addStylesheet("/css/new/my/places.css");
	$page->loadBasicHeader();
?>
<style>
	.Place {
		margin: 5px;
	}
</style>
<script>
	$(function() {
		$(".Place").on("click", function() {
			var placeid = $(this).attr("data-place-id");
			window.external.StartGame("http://<?= $domain ?>/","http://<?= $domain ?>/","http://<?= $domain ?>/game/edit.slua?placeId=" + placeid);
		});

		function onResizeWindow() {
			var n = $("#PlacesContainer:visible");

			$(window).height() < n.height() ?
				$("#Sidebar").height(n.height()) :
				$("#Sidebar").height($(window).height()-114), n.height($(window).height()-114);

			var j = $("#Places");
			$(window).width() < 300 ?
				j.width(300) :
				j.width($(window).width() - 280);
		}

		$(window).resize(onResizeWindow);

		onResizeWindow(); // set the heights and stuff when it loads

		
		$("#Sidebar a").each(function() {
			$(this).attr("href", "#");

			$(this).on("click", function() {
				var view = $(this).attr("data-view");

				$("#Places > div").each(function() {
					$(this).css("display", "none");
				});

				$("#Sidebar a").each(function() {
					$(this).removeAttr("selected");
				})

				$(this).attr("selected", "true");

				$("#"+view+"ProjectsView").css("display", "block");
			})
		})
	});
</script>
<div id="Header">
	<img src="/public/images/ide/studio_title.png">
</div>
<div id="Separator"></div>
<div id="PlacesContainer">
	<div id="Sidebar">
		<div id="SidewaySeparator"></div>
		<ul>
			<li><a href="" data-view="Main" selected>Your Projects</a></li>
			<li><a href="" data-view="Collaborative">Collaborated Projects</a></li>
			<?php if(count($extra_places) != 0): ?>
			<li><a href="" data-view="RecentlyOpened">Recently Opened Files</a></li>
			<?php endif ?>
		</ul>
		<div id="DidYouKnow">
			<p style="font-size: 16px"><b>Did you know?</b></p>
			<p><?= $splash ?></p>
		</div>
	</div>
	<div id="Places">
		<div id="MainProjectsView">
			<?php
				foreach($places as $place) {

					$place_timeago = UtilUtils::GetTimeAgo($place->last_updatetime);

					echo <<<EOT
					<div class="Place" data-place-id="{$place->id}" title="{$place->name}">
						<a href="#">
							<img src="{$place->getThumbsUrl(229, 132)}">
							<div id="Name">{$place->name}</div>
							<div id="LastEdited">Last edited: {$place_timeago}</div>
						</a>
					</div>
					EOT;
				}
			?>
		</div>
		<div id="CollaborativeProjectsView" style="display: none">
			<?php
				foreach($teamplaces as $place) {

					$place_timeago = UtilUtils::GetTimeAgo($place->last_updatetime);

					echo <<<EOT
					<div class="Place" data-place-id="{$place->id}">
						<a href="#">
							<img src="{$place->getThumbsUrl(229, 132)}">
							<div id="Name">{$place->name}</div>
							<div id="LastEdited">Last edited: {$place_timeago}</div>
						</a>
					</div>
					EOT;
				}
			?>
		</div>
		<div id="RecentlyOpenedProjectsView" style="display: none">
			<?php
				foreach($extra_places as $place) {
					$filename = $place["name"];
					$filepath = $place["path"];
					echo <<<EOT
					<td>
						<div class="Place" data-place-id="$filepath">
							<a href="#">
								<img src="/public/images/rejected.png">
								<div id="Name">$filename</div>
							</a>
						</div>
					</td>
					EOT;
				}
			?>
		</div>
	</div>
</div>

