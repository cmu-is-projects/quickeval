<?php
function scanDirectories($rootDir, $allData=array()) {
    // set filenames invisible if you want
    $invisibleFileNames = array(".", "..", ".htaccess", ".htpasswd");
    // run through content of root directory
    $dirContent = scandir($rootDir);
    foreach($dirContent as $key => $content) {
        // filter all files not accessible
        $path = $rootDir.'/'.$content;
        if(!in_array($content, $invisibleFileNames)) {
            // if content is file & readable, add to array
            if(is_file($path) && is_readable($path)) {
                // save file name with path
                $allData[] = $path;
            // if content is a directory and readable, add path and name
            }elseif(is_dir($path) && is_readable($path)) {
                // recursive callback to open new directory
                $allData = scanDirectories($path, $allData);
            }
        }
    }
    return $allData;
}
$files = scanDirectories(".");
$phpfiles = array();
foreach ($files as $f){
	if (substr(strtolower($f), -3, 3)=="php"){
		$phpfiles[] = $f;
	}
}
$total_lines = 0;
$total_chars = 0;
foreach ($phpfiles as $f){
	$fc = file_get_contents($f);
	$lines = explode("\n", $fc);
	$total_chars += strlen($fc);
	$total_lines += count($lines);
}
echo "Total Lines: $total_lines\n";
echo "Total Characters: $total_chars\n";
?>