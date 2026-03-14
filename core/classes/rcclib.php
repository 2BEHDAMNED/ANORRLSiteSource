<?php

class RCCServiceSoap {
	public $ip;
	public $port;
	public $url;
	public $renderFix;

	function __construct($ip = "127.0.0.1", $port = 64989, $url = "roblox.com", $renderFix = true) {
		$this->ip = $ip;
		$this->port = $port;
		$this->url = $url;
		$this->renderFix = $renderFix;
	}

	function requestUrl($url, $xml, $action) {
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_HTTPHEADER, [ "Content-Type: text/xml", "SOAPAction: $action" ]); //con.setRequestProperty("SOAPAction", action);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$result = str_replace(
			[ "<ns1:value>", "</ns1:value>", "</ns1:OpenJobResult>", "<ns1:OpenJobResult>", "<ns1:type>", "</ns1:type>", "<ns1:table>", "</ns1:table>", "</ns1:OpenJobResult>", "</ns1:OpenJobResponse>", "</SOAP-ENV:Body>", "</SOAP-ENV:Envelope>" ],
			"",
			strstr(
				str_replace(
					[ "LUA_TSTRING", "LUA_TNUMBER", "LUA_TBOOLEAN", "LUA_TTABLE" ],
					"",
					curl_exec($ch)
				),
				"<ns1:value>"
			)
		);

		// FIX FOR SOME RENDERS!
		if($this->renderFix) {
			$position = strpos($result, "<ns1:LuaValue>");
			if($position !== false)
				$result = substr($result, 0, $position);
		}

		return $result;
	}

	function execScript($script = 'print("Hello World!")', $jobId = "helloworld", $jobExpiration = 0.1) {
		$url = $this->url;
		$script = str_replace("&", "&amp;", $script);
		$script = str_replace("<", "&lt;",  $script);
		$script = str_replace(">", "&gt;",  $script);

		$xml = <<<EOT
		<?xml version="1.0" encoding="UTF-8"?>
		<SOAP-ENV:Envelope
			xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
			xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
			xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
			xmlns:ns2="http://$url/RCCServiceSoap"
			xmlns:ns1="http://$url/" 
			xmlns:ns3="http://$url/RCCServiceSoap12">
			<SOAP-ENV:Body>
				<ns1:OpenJob>
					<ns1:job>
						<ns1:id>$jobId</ns1:id>
						<ns1:expirationInSeconds>$jobExpiration</ns1:expirationInSeconds>
						<ns1:category>1</ns1:category>
						<ns1:cores>1</ns1:cores>
					</ns1:job>
					<ns1:script>
						<ns1:name>GameScript</ns1:name>
						<ns1:script>
							$script
						</ns1:script>
					</ns1:script>
				</ns1:OpenJob>
			</SOAP-ENV:Body>
		</SOAP-ENV:Envelope>
		EOT;
		
		return $this->requestUrl("http://".$this->ip.":".$this->port, $xml, "OpenJob");
	}

	function execute($script, $jobID) {
		$url = $this->url;
		$xml = <<<EOT
		<?xml version="1.0" encoding="UTF - 8"?>
		<SOAP-ENV:Envelope 
			xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
			xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:xsd="http://www.w3.org/2001/XMLSchema"
			xmlns:ns2="http://$url/RCCServiceSoap"
			xmlns:ns1="http://$url/" 
			xmlns:ns3="http://$url/RCCServiceSoap12">
			<SOAP-ENV:Body>
				<ns1:Execute>
					<ns1:jobID>$jobID</ns1:jobID>
					<ns1:script>
						<ns1:name>Script</ns1:name>
						<ns1:script>
							$script
						</ns1:script>
					</ns1:script>
				</ns1:Execute>
			</SOAP-ENV:Body>
		</SOAP-ENV:Envelope>
		EOT;
		
		return $this->requestUrl("http://".$this->ip.":".$this->port, $xml, "Execute");
	}

	function closeJob($jobID) {
		$url = $this->url;
		$xml = <<<EOT
		<?xml version="1.0" encoding="UTF - 8"?>
		<SOAP-ENV:Envelope 
			xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
			xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:xsd="http://www.w3.org/2001/XMLSchema"
			xmlns:ns2="http://$url/RCCServiceSoap"
			xmlns:ns1="http://$url/" 
			xmlns:ns3="http://$url/RCCServiceSoap12">
			<SOAP-ENV:Body>
				<ns1:CloseJob>
					<ns1:jobID>$jobID</ns1:jobID>
				</ns1:CloseJob>
			</SOAP-ENV:Body>
		</SOAP-ENV:Envelope>
		EOT;
		
		return $this->requestUrl("http://".$this->ip.":".$this->port, $xml, "CloseJob");
	}
}