<?php
session_start();
$currentItem = $_GET['item'];
include 'parts/functs.php';
function searchInArray($search_value,$search_array,$key) {
    $it = $search_value;
    $ar = $search_array;
    for($x=0;$x<count($ar);$x++){
        if ($ar[$x]->$key == $it){
            return $x;
        }
    };
    return false;
}
$rebuild = 0;
if (isset($_POST['submitComment'])){
    $currentComment = htmlspecialchars($_POST['commentbox']);
    $fileContent[searchForId()]->comments .= '<>'.$_SESSION['currentuser'].'>--< said:<br>'.$currentComment;
    $rebuild++;
}
if (isset($_POST['killPost'])){
    array_splice($fileContent,searchForId(),1);
    $rebuild++;
}
if (isset($_POST['submit_edit'])){
    $edit_data = htmlspecialchars($_POST['post_edit']);
    $editcomment = '<>' . $_SESSION['currentuser'] . '>--< edited this post on: ' . date("Y-m-d H:i:s");



    $fileContent[searchForId()]->message = $edit_data;
    $fileContent[searchForId()]->comments .= $editcomment;
    $rebuild++;
}
$allcomments = explode('<>',$fileContent[searchForId()]->comments);
if(isset($_SESSION['currentuser'])) {
    $commentboxes = '<div><hr><form method="post" action="reader.php?item=' . $currentItem . '"><textarea name="commentbox" placeholder="comments" required></textarea><input type="submit" name="submitComment"></form>';
}
else {
    $commentboxes =  '<hr><div class="yellowtext">login to comment</div>';
}
for($x=0;$x<count($allcomments);$x++){
    $displaynames = explode('>--<',$allcomments[$x]);
    $commentboxes .= '<div><hr>'.$userDB[searchInArray($displaynames[0],$userDB,'username')]->displayname."".$displaynames[1].'</div>';

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
        $killswitch = '<form action="" method="post"><input type="submit" value="delete this post" name="killPost"></form>';
    }
}
$title = 'Forum BvH - '.$fileContent[searchForId()]->title;
$newbuild = "";
include 'parts/top.php';

if ($rebuild != 0){
    for($x=0;$x<count($fileContent);$x++){
        $newbuild .= json_encode($fileContent[$x]).'<--->';
    }
    $overwrite = fopen($dir.'/Posts.txt','w+');
    fwrite($overwrite,$newbuild);
    fclose($overwrite);
    if (isset($_POST['killPost'])){
        hardreload('home');
    }
    else{
        hardreload('?item='.$currentItem);
    }

}
if(isset($_SESSION['currentuser'])) {
    if ($_SESSION['currentuser'] == $fileContent[searchForId()]->user){
        $editpost = '
        <hr>
        <button onclick="document.getElementById(\'edit_post\').style.display=\'table\';this.style.display=\'none\'">edit post</button>
        <div style="display: none;" id="edit_post"><form method="post" action="reader.php?item=' . $currentItem . '" enctype="multipart/form-data">
        <div><textarea name="post_edit" class="responsive_100_wide" placeholder="your post" required>'.$fileContent[searchForId()]->message.'</textarea></div>
        <div><input type="submit" name="submit_edit" value="commit"></form></div></div>';
    }
}
else {
    $editpost = null;
}


echo $killswitch;
echo '<div>
            <a href="index.php">
                <button>return</button>
            </a>
        </div>
        <hr>
        <div>'.$userDB[searchInArray($fileContent[searchForId()]->user,$userDB,"username")]->displayname.'\'s message: </div><br>';
echo $fileContent[searchForId()]->message;
echo $editpost;
echo $commentboxes;
include 'parts/bottom.php';
?>