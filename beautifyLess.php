<?
/*
*
*	Less Beautifyer v1
*	by @betoayesa 
*	use: php clean_less.php [path to folder]
*	MIT LICENSE
*
*/

if (!isset($argv[1])){
	die("No Path specified");
}

$path = $argv[1]; 

readFolder($path);

function readFolder($folder){
	//echo "folder: ".$folder.PHP_EOL;
	$dirHandle = opendir($folder);
	while($file = readdir($dirHandle)){
		if(is_dir($folder . $file) && $file != '.' && $file != '..'){
			readFolder($folder.$file."/");
		}else{
			if (strstr($file,".less")){
				beautify($folder.$file);
			}
		}
	} 
}

function beautify($filename){

	$open_brackets = 0;
	$inside_parenthesis = false;

	if ($file = fopen($filename, "r")) {
		$output = "";
	    while(!feof($file)) {
	        $line = fgets($file);	        
	        $line = str_replace("\n","",$line);
	        $line = str_replace(PHP_EOL,"",$line);

			if (trim($line) != ""){
	        	if (strstr($line,"}")){
	        		$open_brackets--;
	        	}
	        	// Insert TABS
	        	if ($inside_parenthesis < 1){
	        		for($i=0;$i < $open_brackets;$i++):
	        			$output .= chr(9);
	        		endfor;
	        	}
	        	
	        	// Count open brackets
	        	if (strstr($line,"{")){
	        		$open_brackets++;
	        	}

	        	// Beautify it
	        	if (strstr($line,":") and strstr($line,";") and substr_count($line,":") == 1){
	        		$line = str_replace(" ;",";", $line);
	        		$aux = explode(":",$line);
	        		$output .= trim($aux[0]).": ".trim($aux[1]);
	        	}else if (strstr($line,"}") and $open_brackets == 0){
	        		$output .= trim($line).PHP_EOL;
	        	} else {
	        		$output .= trim($line);
	        	
	        	}

	        	$inside_parenthesis += substr_count($line,"(") - substr_count($line,")");

	        	if ($inside_parenthesis > 0){
	        		$output .= " ";
	        	}else{
	        		$output .= PHP_EOL;
	        	}
	    	}
	    }
	    fclose($file);
	    file_put_contents($filename,$output);
	    
	}
} 
