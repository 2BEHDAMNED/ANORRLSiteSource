<?php 
	if(!SESSION) {
		die("Hey have you tried logging in before doing this? <br><a href='javascript:window.close()'>No...</a>");
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Upload</title>
		<link href="/css/RobloxOld.css" rel="stylesheet" type="text/css" />
	</head>
	<body scroll="no">
		<form name="Form1" method="post" action="Upload.aspx" id="Form1">

			<script type="text/javascript">
				var redirectLoginUrl = '';
				var publishOnPageLoad = false;

				window.onload = function()
				{
					if (publishOnPageLoad)
						publish();
				}
				
				function publish()
				{
					if (redirectLoginUrl !== "")
					{
						window.location = redirectLoginUrl;
						return;
					}

					document.getElementById("Uploading").style.display='block';
					try
					{
						window.external.Save();
						document.getElementById("DialogResult").value='1';
						window.close();
					}
					catch (ex)
					{
						try
						{
							window.external.Save();
							document.getElementById("DialogResult").value='1';
							window.close();
						}
						catch (ex2)
						{
							document.getElementById('ErrorLabel').style.display = '';
							document.getElementById("NormalSaveButton").style.display = 'none';
							document.getElementById("NormalSaveText").style.display = 'none';

							document.getElementById("LocalSaveButton").style.display = 'block';
							document.getElementById("LocalSaveText").style.display = 'block';
						}
					}
					document.getElementById("Uploading").style.display='none';
				}
			</script>
			<table height="100%" cellpadding="12" width="100%">
				<tr valign="top">
					<td colspan="2">
						<p>You are about to leave your Place. Do you wish to save changes made to your Place before exiting?</p>
						<div id="Uploading" style="DISPLAY: none; FONT-WEIGHT: bold; COLOR: royalblue">Uploading. Please wait...</div>
						<span id="ErrorLabel" style="color: Red; display: none">Upload Failed!</span>
						<input id="DialogResult" type="hidden" />
					</td>
				</tr>
				<tr>
					<td width="120">
						<div id="NormalSaveButton" style="display:block;"><input type="button" style="WIDTH: 100%" value="Save" class="OKCancelButton" onclick="return publish();" /></div>
						<div id="LocalSaveButton"  style="display:none;"><input class="OKCancelButton" style="WIDTH: 100%" onclick="DialogResult.value='3'; window.close(); return false" type="button" value="Save Local" /></div>
					</td>
					<td>
						<div id="NormalSaveText" style="display:block;"><strong>Save changes to my Place to ANORRL.</strong> (You will leave your place after the save has completed.)</div>
						<div id="LocalSaveText"  style="display:none;"><strong>Save a local copy of my Place instead of uploading.</strong> ("You can open the file you save using ANORRL Studio.")</strong></div>
					</td>
				</tr>
				<tr>
					<td width="120"><input class="OKCancelButton" style="WIDTH: 100%" onclick="DialogResult.value='1'; window.close(); return false" type="button" value="Don't Save" /></td>
					<td><strong>Leave my Place on ANORRL unchanged.</strong> (You will lose any changes you made during your visit.)</td>
				</tr>
				<tr>
					<td width="120"><input class="OKCancelButton" style="WIDTH: 100%" onclick="DialogResult.value='2'; window.close(); return false" type="button" value="Cancel" /></td>
					<td><strong>Keep playing and exit later.</strong></td>
				</tr>
			</table>
		</form>
	</body>
</html>