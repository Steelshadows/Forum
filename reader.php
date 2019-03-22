<?php
session_start();
$currentItem = $_GET['item'];

function hardreload(){
    global $currentItem;
    echo '<script>
            baseurl = location.protocol + \'//\' + location.host + location.pathname+"?item="'.$currentItem.';
            window.location = baseurl;</script>';
}


$dir= 'Posts';
$handle = fopen($dir.'/Posts.txt','a+');

$fileContent = explode('<--->',fread($handle,filesize($dir.'/Posts.txt')));
array_pop($fileContent);

for($x=0;$x<count($fileContent);$x++){
    $fileContent[$x] = json_decode($fileContent[$x]);
    };
if (isset($_POST['submitComment'])){
    $currentComment = htmlspecialchars($_POST['commentbox']);
    $fileContent[searchForId()]->comments .= '<>'.$_SESSION['currentuser'].' said:<br>'.$currentComment;
//    echo $fileContent[searchForId()]->comments;
}
$allcomments = explode('<>',$fileContent[searchForId()]->comments);
if(isset($_SESSION['currentuser'])) {
    $commentboxes = '<div><hr><form method="post" action="reader.php?item=' . $currentItem . '"><textarea name="commentbox" placeholder="comments" required></textarea><input type="submit" name="submitComment"></form>';
}
else {
    $commentboxes =  '<hr><div class="yellowtext">login to comment</div>';
//    var_dump($_SESSION);
}
for($x=0;$x<count($allcomments);$x++){
//    var_dump($allcomments);
    $commentboxes .= '<div><hr>'.$allcomments[$x].'</div>';

};
$commentboxes .= '</div>';

function searchForId() {
    global $currentItem;
    global $fileContent;
    for($x=0;$x<count($fileContent);$x++){
        if ($fileContent[$x]->title == $currentItem){
            return $x;
        }
    };
}
$title = 'Forum BvH - '.$fileContent[searchForId()]->title;
$newbuild = "";
if (isset($_POST['submitComment'])){
    for($x=0;$x<count($fileContent);$x++){
       $newbuild .= json_encode($fileContent[$x]).'<--->';
    }
//    echo $newbuild;
    $overwrite = fopen($dir.'/Posts.txt','w+');
    fwrite($overwrite,$newbuild);
    fclose($overwrite);
    hardreload();
}




include 'parts/top.php';
echo '<div><a href="index.php"><button>return</button></a></div><hr><div>Message: </div><br>';
echo $fileContent[searchForId()]->message;
echo $commentboxes;
include 'parts/bottom.php';
?>