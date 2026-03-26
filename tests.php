<?php
// Array with names
 

// get the q parameter from URL
$q = $_REQUEST["d"];
if($q==1){
    echo "OK";
}

// lookup all hints from array if $q is different from "" 
 // Output "no suggestion" if no hint was found or output correct values 
//echo $q;
?>