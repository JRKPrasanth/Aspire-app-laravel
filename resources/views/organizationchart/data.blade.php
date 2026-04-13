<?php


// Encode the data as JSON
$jsonData = $chartData;

// If the first and last characters of the JSON string are '[', and ']'
// respectively, remove them
if (substr($jsonData, 0, 1) === '[' && substr($jsonData, -1) === ']') {
    $jsonData = substr($jsonData, 1, -1);
}

// Echo the modified JSON string
echo $jsonData;

?>
