<!DOCTYPE html>
<html>
	<head>
		<title>Create Badge - ANORRL</title>
		<link rel="icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" href="/public/css/new/main.css?v=2">
		<link rel="stylesheet" href="/public/css/new/publish.css">
		<script src="/public/js/core/jquery.js"></script>
		<script src="/public/js/main.js?t=1776250887"></script>
		<style>
			h2 {
				margin-top: 0px;
			}
			#BodyContainer {
				border-top: 4px solid black;
			}
		</style>
	</head>
	<body>
		<div id="Container">
			<div id="Body">
				<div id="BodyContainer">
					<div id="PublishContainer">
						<h2>Publish your lovely little place...</h2>
						<div id="ItemDetails" style="background: #222">
							<form method="POST">
								<div id="DetailStack">
									<h4>Information</h4>
									<table>
										<tr>
											<td>Name</td>
											<td><input type="text" name="ANORRL$IDE$Publish$Place$Name" value="My Place" minlength="3" maxlength="128"></td>
										</tr>
										<tr>
											<td>Description</td>
											<td><textarea style="height: 50px;" name="ANORRL$IDE$Publish$Place$Description"></textarea></td>
										</tr>
										<tr>
											<td>Public</td>
											<td><input type="checkbox" name="ANORRL$IDE$Publish$Place$PublicBox" checked></td>
										</tr>
										<tr>
											<td>Enable Comments</td>
											<td><input type="checkbox" name="ANORRL$IDE$Publish$Place$CommentsBox" checked></td>
										</tr>
									</table>
								</div>
								<div id="DetailStack">
									<h4 style="margin-top: 10px">Place Settings</h4>
									<table>
										<tr>
											<td>Server Size</td>
											<td><input type="number" name="ANORRL$IDE$Publish$Place$ServerSize" value="12"></td>
										</tr>
										<tr>
											<td>Copylocked</td>
											<td><input type="checkbox" name="ANORRL$IDE$Publish$Place$Copylocked" checked></td>
										</tr>
										<tr>
											<td>Gears Enabled</td>
											<td><input type="checkbox" name="ANORRL$IDE$Publish$Place$GearsEnabled"></td>
										</tr>
										<tr>
											<td>Original</td>
											<td><input type="checkbox" name="ANORRL$IDE$Publish$Place$IsOriginal"></td>
										</tr>
									</table>
									<input type="submit" value="Publish" name="ANORRL$IDE$Publish$Place$Submit" style="text-align: center">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
