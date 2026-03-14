<?php
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/userutils.php';
	$user = UserUtils::RetrieveUser();

	if($user == null) {
		die(header("Location: /login"));
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Your Character - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/new/main.css">
		<link rel="stylesheet" href="/css/new/stuff.css?v=1">
		<link rel="stylesheet" href="/css/new/my/character.css">
		
		<script src="/js/core/jquery.js"></script>
		<script src="/js/core/jquery-modal.js"></script>
		<script src="/js/main.js?t=1771413807"></script>
		<script src="/js/character.js?t=1771413909"></script>
	</head>
	<body>
		<div id="Colours">

		</div>
		<div class="Asset" template>
			<div id="WearButton">[ wear ]</div>
			<a id="NameAndThumbs">
				<img src="">
				<span>AssetName</span>
			</a>
			<a id="Creator"><span>AssetCreator</span></a>
		</div>
		<div id="Container">
		<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/header.php'; ?>
			<div id="Body">
				<div id="BodyContainer">
					<h2 style="margin-bottom: 5px">Your Character</h2>
					<div id="CharacterContainer">
						<div id="CharacterLeftwardSide">
							<div id="Wardrobe">
								<h4>Wardrobe</h4>
								<div id="WardrobeHeader">
									<div>
										<a data_category="8">Hats</a>
										<a data_category="18">Faces</a>
										<a data_category="2">T-Shirts</a>
										<a data_category="11">Shirts</a>
										<a data_category="12">Pants</a>
										<a data_category="19">Gears</a>
										<a data_category="32">Outfits</a>
									</div>
									<hr>
									<div>
										<a data_category="32">Packages</a>
										<a data_category="17">Heads</a>
										<a data_category="27">Torsos</a>
										<a data_category="29">Left Arms</a>
										<a data_category="28">Right Arms</a>
										<a data_category="30">Left Legs</a>
										<a data_category="31">Right Legs</a>
									</div>
								</div>
								<div id="AssetsContainer">
									<div id="StatusText">
										<b id="Loading" style="">Loading assets...</b>
										<b id="NoAssets" style="display: none"><img src="/images/noassets.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: -92px;margin-top: 23px;">Seems barren, try buying some stuff!</b>
									</div>
									<table id="Assets" hidden>										
									</table>
									<div id="Paginator" style="display: block;">
										<a id="BackPager" href="javascript:ANORRL.Character.DeadvancePager()" style="display: none;">&lt;&lt; Back</a> <input type="text" id="NumberPutter" maxlength="3"> of <span id="Pages">1</span> <a id="NextPager" href="javascript:ANORRL.Character.AdvancePager()" style="display: none;">Next &gt;&gt;</a>
									</div>
								</div>

							</div>
							<div id="CurrentlyWearing">
								<h4>Currently Wearing</h4>
								<div id="AssetsContainer">
									<div id="StatusText">
										<b id="Loading" style="">Loading assets...</b>
										<b id="NoAssets" style="display: none"><img src="/images/noassets.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: -92px;margin-top: 23px;">Seems barren, try buying some stuff!</b>
									</div>
									<table id="Assets" hidden>										
									</table>
								</div>
							</div>
						</div>
						<div id="CharacterRightwardSide">
							<div id="AvatarRender">
								<h4>Avatar Render</h4>
								<div id="RenderContainer">
									<img id="PlayerRender" src="/thumbs/player?id=<?= $user->id ?>" width="260">
									<div style="margin-top: -10px;margin-bottom: 5px">
										<!--<button style="width: 105px;">Create Outfit</button>-->
										<button style="width: 90px;" onclick="ANORRL.Character.RenderPlayer(true);">Re-Render</button>
									</div>
								</div>
							</div>
							<div id="BodyColours">
								<h4>Body Colours</h4>
								<div id="BodyColoursContainer">
									<div id="HeadRow">
										<button data_bodytype="0"></button>
									</div>
									<div id="TorsoRow">
										<button data_bodytype="2"></button><button data_bodytype="1"></button><button data_bodytype="3"></button>
									</div>
									<div id="LegsRow">
										<button data_bodytype="5"></button><button data_bodytype="4"></button>
									</div>
									<div id="BodyPartInfo">&nbsp;</div>
								</div>
							</div>
						</div>
					</div>
					<br style="display:block; clear:both;">
				</div>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/core/ui/footer.php'; ?>
			</div>
		</div>
	</body>
</html>
