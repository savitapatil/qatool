<?php
$filepath = __DIR__ . "/" . $argv[1] . "/reports/codesniffer/phpcssummary.csv";
$handle = fopen($filepath, "r");
$lineNo = 0;
$handle1 = fopen(__DIR__ . "/new-" . $argv[1] . ".csv", 'a+');
ftruncate($handle1, 0);
if ($handle) {
    while (($line = fgets($handle)) !== false) {
	$lineNo++;
	if($lineNo >= 6){
        	// process the line read.
		$line = substr($line, 4);
		$line = preg_replace("/\s+/", ' ', $line);
		explode(" ",$line);
		if(count(explode(" ",$line)) == 3){
			$handle2 = fopen(__DIR__ . "/new-" . $argv[1] . ".csv", 'a+');
			fwrite($handle2,$line. PHP_EOL);
		}
	}
	//echo $line. PHP_EOL;
    }

    fclose($handle);
} else {
    // error opening the file.
} 




