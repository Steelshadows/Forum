<?php

$dir= 'audio';
$files = scandir($dir);
array_shift($files);
array_shift($files);
array_reverse($files);
echo '<a href="../../index.php">ga terug naar home</a>';
echo '<form method="post" action="#"><ul>';
for($x=0;$x < count($files);$x++) {
    $currentItem = explode('.', $files[$x]);


    $filePath = 'audio/'.$files[$x];
    if (file_exists($filePath)) {
        $audio = '
        <audio controls>
        <source src="'.$filePath.'" type="audio/mpeg">
        Your browser does not support the audio element.
        </audio>
        
        ';
    } else {
        $audio =  $currentItem[0];
    };
    echo '<li class="audioPreview"> '.$currentItem[0]." ".$audio.'</li>';
}

echo '</ul>';