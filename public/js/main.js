ANORRL = {
	Logout: function() {
		$.get( "/api/logout", function() {
			window.location.reload();
		});
	},
	GetInternetExplorerVersion: function() {
		// Returns the version of Internet Explorer or a -1
		// (indicating the use of another browser).
	
		var rv = -1; // Return value assumes failure.
	
		if (navigator.appName == 'Microsoft Internet Explorer')
		{
			var ua = navigator.userAgent;
			var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
	
			if (re.exec(ua) != null) rv = parseFloat( RegExp.$1 );
		}
		return rv;
	},
	GetDateFormatFromTimestamp: function(timestamp) {
		var time = timestamp;
		var d = new Date(time * 1000);
		return ("0" + d.getDate()).slice(-2) + "/" + ("0"+(d.getMonth()+1)).slice(-2) + "/" + d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
	},
	HideMobileWarning: function() {
		$(".DisplayMobileWarning").remove();
		$.cookie("MobileKnowsThis", "true");
	},
	ChangeUrl: function(title, url) {
		if (typeof (history.pushState) != "undefined") {
			var obj = { Title: title, Url: url };
			history.pushState(obj, obj.Title, obj.Url);
		} else {
			window.location.href = url;
		}
	},
	CollectStipend: function() {
		$("a[href='javascript:ANORRL.CollectStipend()']").attr("href", "");
		$.post( "/api/stipend", { imsohungry: "very"}, function(data) {
			if(data['error']) {
				alert(data['reason']);
			}
			window.location.reload();
		});
	}
};


if(ANORRL.GetInternetExplorerVersion() != -1) {
	$(function() {
		$("input[placeholder]").each(function() {
			this.value = $(this).attr('placeholder');
		});
		$("input[placeholder]").focus(function() {
			if (this.value == $(this).attr('placeholder')) {
				this.value = '';
			} 
		}).blur(function() {
			if (this.value == '')
				this.value = $(this).attr('placeholder'); 
		});
	})
}

$(function() {
	if('ontouchstart' in document.documentElement) {
		if($.cookie("MobileKnowsThis") == undefined) {
			$(".DisplayMobileWarning").css("display", "block");
		}
		
	} else {
		$(".DisplayMobileWarning").css("display", "none");
	}
})