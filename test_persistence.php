<?php

$incomingdata = <<<EOT
<Table>
	<Entry>
		<Key>boolkey</Key>
		<Value Type="boolean">true</Value>
	</Entry>
	<Entry>
		<Key>boolfuckedkey</Key>
		<Value Type="boolean">fuck</Value>
	</Entry>
	<Entry>
		<Key>numberkey</Key>
		<Value Type="number">1</Value>
	</Entry>
	<Entry>
		<Key>numberfuckedkey</Key>
		<Value Type="number">fuuuck</Value>
	</Entry>
	<Entry>
		<Key>floatkey</Key>
		<Value Type="number">0.55554999999999999</Value>
	</Entry>
	<Entry>
		<Key>floatfuckedkey</Key>
		<Value Type="number">ia.amaaa</Value>
	</Entry>
	<Entry>
		<Key>part</Key>
		<Value Type="instance">
			<roblox>
				<External>null</External>
				<External>nil</External>
			</roblox>
		</Value>
	</Entry>
	<Entry>
		<Key>key</Key>
		<Value Type="string">string</Value>
	</Entry>
</Table>
EOT;

/*
boolean 7
number 6
instance 8
string 5
*/

	//echo $incomingdata;
	header("Content-Type: text/plain");

	$data = explode("<Entry>", str_replace("<Table>", "", str_replace("</Table>", "", $incomingdata)));

	$data_reconstructed = [];
	$current_index = 0;
	foreach($data as $part) {
		
		if(trim($part) == "") {
			continue;
		}

		$part_predata = str_replace("</Entry>", "", $part);
		
		$part_dataarray = preg_split("/\r\n|\n|\r/", $part_predata);
		$part_key = "";
		$part_type = "";
		$part_data = "";
		foreach($part_dataarray as $line) {
			$trimmed_line = trim($line);
			if($trimmed_line != "") {
				if($part_key == "" && str_starts_with($trimmed_line, "<Key>") && str_ends_with($trimmed_line, "</Key>")) {
					$part_key = str_replace("<Key>", "", str_replace("</Key>", "", $trimmed_line));
					break;
				}
			}
		}



		if($part_key != "") {
			$value_predata = str_replace("\t\t<", "<", str_replace("</Value>", "", explode("<Key>$part_key</Key>", $part_predata)[1]));
			$value_dataarray = preg_split("/\r\n|\n|\r/", trim($value_predata));
			
			if(count($value_dataarray) == 1) {
				$type =  explode(">", $value_dataarray[0])[0];
				$type = str_replace("<Value Type=\"", "", $type);
				$type = str_replace("\"", "", $type);

				$value = explode(">", $value_dataarray[0])[1];

				if($type == "boolean" || $type == "string" || $type == "number") {
					if($type == "boolean") {
						if($value != "true" && $value != "false") {
							continue;
						}
					}

					if($type == "number") {
						if(!str_contains($value, ".")) {
							$parsed_int = intval($value);
							if($value != "$parsed_int") {
								continue;
							}
						} else {
							$parsed_float = floatval($value);
							if($value != "$parsed_float") {
								continue;
							}
						}
						
					}

					$part_type = $type;
					$part_data = $value;
				}
				
			} else {
				// has to be instance then...
				if(count($value_dataarray) != 0) {
					$value = "";
					$type = "";
					foreach($value_dataarray as $line) {
						$trimmed_line = trim($line);

						if(str_starts_with($trimmed_line, "<Value Type=\"")) {
							$type = str_replace("<Value Type=\"", "", $trimmed_line);
							$type = str_replace("\">", "", $type);

							if($type != "instance") {
								continue;
							}

							
						} else {
							if($trimmed_line != "") {
								$value .= str_replace("\t<", "<", $line). ($trimmed_line != "</roblox>" ? "\n" : "");
							}
						}

						
					}

					if($type != "" && $value != "") {
						$part_type = $type;
						$part_data = $value;
					}
				}
			}
		}

		$data_reconstructed[$current_index]['key'] = $part_key;
		$data_reconstructed[$current_index]['type'] = $part_type;
		$data_reconstructed[$current_index]['value'] = $part_data;

		$current_index += 1;
	}

	print_r($data_reconstructed);
?>