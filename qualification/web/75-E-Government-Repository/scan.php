<?php
// Source: https://tutorialzine.com/2014/09/cute-file-browser-jquery-ajax-php

$dir = "files";

// Run the recursive function 

$response = scan($dir);


// This function scans the files folder recursively, and builds a large array

function scan($dir){

    $files = array();

    // Is there actually such a folder/file?

    if(file_exists($dir)){
    
        foreach(scandir($dir) as $f) {
        
            if(!$f || $f[0] == '.') {
                continue; // Ignore hidden files
            }

            if(is_dir($dir . '/' . $f)) {

                // The path is a folder

                $files[] = array(
                    "name" => $f,
                    "type" => "folder",
                    "path" => $dir . '/' . $f,
                    "items" => scan($dir . '/' . $f) // Recursively get the contents of the folder
                );
            }
            
            else {

                // It is a file

                $category = substr($dir, 6);

                $files[] = array(
                    "name" => $f,
                    "type" => "file",
                    "path" => "download.php?file=" . $f . "&token=" . base64_encode($f) . "&cat=" . $category,
                    "size" => filesize($dir . '/' . $f) // Gets the size of this file
                );
            }
        }
    
    }

    return $files;
}



// Output the directory listing as JSON

header('Content-type: application/json');

echo json_encode(array(
    "name" => "files",
    "type" => "folder",
    "path" => $dir,
    "items" => $response
));
