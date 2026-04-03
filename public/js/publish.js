if(ANORRL == undefined) {
	ANORRL = {};
}

ANORRL.Publish = {
	HandleAction: function(actionparams) {
		if(actionparams != "createnew" && actionparams != 0) {
			// perform publish.
			window.setTimeout(function() {
				$("#Container").remove();
				$("#PublishContent").css("display", "block");
				$("body").css("background", "buttonface");
			}, 1000);

			
			
			window.setTimeout(function() {
				try {
					window.external.SaveUrl('http://arl.lambda.cam/Data/Upload.ashx?assetid='+actionparams);
					document.getElementById("Uploading").style.display='none';
					document.getElementById("Confirmation").style.display='block';
				} catch (ex) {
					try {
						window.external.SaveUrl('http://arl.lambda.cam/Data/Upload.ashx?assetid='+actionparams);
						document.getElementById("Uploading").style.display='none';
						document.getElementById("Confirmation").style.display='block';
					} catch (ex2) {
						document.getElementById("Uploading").style.display='none';
						document.getElementById("Failure").style.display='block';
					}
				}
			}, 2000);
		} else {
			window.location.href = "/IDE/PublishNewPlace.aspx";
		}
		//alert(actionparams);
	}
};

$(function() {
	$("#PublishPlaces .Place").each(function() {
		$(this).click(function() {
			ANORRL.Publish.HandleAction($(this).attr("data-placeid"));
		});
	})
})
