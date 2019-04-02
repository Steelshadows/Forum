<?php
session_start();
$currentItem = $_GET['item'];
function searchInArray($search_value,$search_array,$key) {
    $it = $search_value;
    $ar = $search_array;
    for($x=0;$x<count($ar);$x++){
//        var_dump($ar[$x]);
//        echo $ar[$x]."<br>".var_dump(json_decode($ar[$x]))."<br><br>";
        if (json_decode($ar[$x])){
//            echo"true";
            if(json_decode($ar[$x])->$key == $it){
//                echo"true";
                return $x;
            }
        }
        else if ($ar[$x]->$key == $it){
//            echo"false";
            return $x;
        }
    };
    return false;
}

$dir = 'Posts';
$handle = fopen($dir.'/Posts.txt','a+');
$fileContent = explode('<--->',fread($handle,filesize($dir.'/Posts.txt')));
array_pop($fileContent);
$loginsignupdir = 'users';
$loginsignuphandle = fopen($loginsignupdir.'/users.txt','a+');
$userDB = explode('<--->',fread($loginsignuphandle,filesize($loginsignupdir.'/users.txt')));
array_pop($userDB);


//echo json_decode($userDB[searchInArray($fileContent[searchForId()]->user,$fileContent,"username")])->displayname;


$fileContentblanked = $fileContent;
for($x=0;$x<count($fileContentblanked);$x++) {
    $fileContentblanked[$x] = json_decode($fileContentblanked[$x]);
}
if(!isset($_SESSION['currentuser'])){
    for($x=0;$x<count($fileContentblanked);$x++){
        for($y=5;$y<(strlen($fileContentblanked[$x]->user)-3);$y++)
            $fileContentblanked[$x]->user[$y] = "*";
    }
}



for($x=0;$x<count($fileContent);$x++){
    $fileContent[$x] = json_decode($fileContent[$x]);
    };
if (isset($_POST['submitComment'])){
    $currentComment = htmlspecialchars($_POST['commentbox']);
    $fileContent[searchForId()]->comments .= '<>'.$_SESSION['currentuser'].'>--< said:<br>'.$currentComment;
//    echo $fileContent[searchForId()]->comments;
}
if (isset($_POST['killPost'])){
    array_splice($fileContent,searchForId(),1);
//    $fileContent[searchForId()]->comments .= '<>'.$_SESSION['currentuser'].' said:<br>'.$currentComment;
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
    $displaynames = explode('>--<',$allcomments[$x]);
//    echo $displaynames[0]."<br>";
//    echo json_decode($userDB[searchInArray($displaynames[0],$userDB,'username')])->displayname;
    $commentboxes .= '<div><hr>'.json_decode($userDB[searchInArray($displaynames[0],$userDB,'username')])->displayname."".$displaynames[1].'</div>';

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
$killswitch = null;
if(isset($_SESSION['currentuser'])){
    if($_SESSION['currentuser'] == $fileContent[searchForId()]->user||$_SESSION['currentuser'] == "its.a.me.brand@gmail.com"){
//      echo "<button onclick=\"alert('please set fire to servers to delete message')\">delete post</button>";
        $killswitch = '<form action="" method="post"><input type="submit" value="delete this post" name="killPost"></form>';
    }

}
$title = 'Forum BvH - '.$fileContent[searchForId()]->title;
$newbuild = "";
include 'parts/top.php';

if (isset($_POST['submitComment'])){
    for($x=0;$x<count($fileContent);$x++){
       $newbuild .= json_encode($fileContent[$x]).'<--->';
    }
//    echo $newbuild;
    $overwrite = fopen($dir.'/Posts.txt','w+');
    fwrite($overwrite,$newbuild);
    fclose($overwrite);
    hardreload('?item='.$currentItem);
}
if (isset($_POST['killPost'])){
    for($x=0;$x<count($fileContent);$x++){
        $newbuild .= json_encode($fileContent[$x]).'<--->';
    }
//    echo $newbuild;
    $overwrite = fopen($dir.'/Posts.txt','w+');
    fwrite($overwrite,$newbuild);
    fclose($overwrite);
    hardreload("home");
}



echo $killswitch;
echo '<div><a href="index.php"><button>return</button></a></div><hr><div>'.json_decode($userDB[searchInArray($fileContent[searchForId()]->user,$userDB,"username")])->displayname.'\'s message: </div><br>';
echo $fileContent[searchForId()]->message;
echo $commentboxes;
include 'parts/bottom.php';
?>