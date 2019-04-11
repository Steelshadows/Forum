<?php
session_start();
$userviewer = $_GET['user'];
include 'parts/functs.php';
//var_dump($fileContent);

function searchForId() {
    global $userviewer;
    global $userDB;
    for($x=0;$x<count($userDB);$x++){
        if ($userDB[$x]->username == $userviewer) {
            return $x;
        }
//        else if (json_decode($userDB[$x])){
//            if (json_decode($userDB[$x])->username == $userviewer){
//                return $x;
//            }
//        }
    };
}
$userviewerBlanked = $userviewer;
if(!isset($_SESSION['currentuser'])){
    for($y=5;$y<(strlen($userviewerBlanked)-3);$y++)
        $userviewerBlanked[$y] = "*";
}
//$loginsignupdir = 'users';
//$loginsignuphandle = fopen($loginsignupdir.'/users.txt','a+');
//$userDB = explode('<--->',fread($loginsignuphandle,filesize($loginsignupdir.'/users.txt')));
//array_pop($userDB);
//for($x=0;$x<count($userDB);$x++){
//    $userDB[$x] = json_decode($userDB[$x]);
//};
//var_dump($userDB);
//echo searchForId();
$userbio = $userDB[searchForId()]->bio;


if (file_exists($userDB[searchForId()]->src))
{$userlogo = '<img class="logo" src="'.$userDB[searchForId()]->src.'">';}
else{
    $userlogo = '<img class="logo" src="userImage/guest.png">';
}
if(isset($_POST['submit_edit'])){
    $newbuild= "";
    if(isset($_FILES["picture"])&&$_FILES["picture"]['name'] != '') {
        $target_dir = "userImage/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($userviewer . '.' . $imageFileType);

        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if ($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["picture"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Swuploaded.";
//            $target_file = 'http://oakclifffilmfestival.com/assets/placeholder-user.png';
        } else {

                if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                    echo "The file " . basename($_FILES["picture"]["name"]) . " has been uploaded.";
                    $userDB[searchForId()]->src = $target_file;
                }

            else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

//    for($x=0;$x<count($userDB);$x++){
//    $userDB[$x] = json_decode($userDB[$x]);
//    };
    $new_bio = htmlspecialchars($_POST["bio_edit"]);
    $userDB[searchForId()]->bio = $new_bio;
    $new_displayname = htmlspecialchars($_POST['displayname_edit']);
    $userDB[searchForId()]->displayname = $new_displayname;


    for($x=0;$x<count($userDB);$x++){
        $newbuild .= json_encode($userDB[$x]).'<--->';
    }
//    echo $newbuild;
    $overwrite = fopen('users/users.txt','w+');
//    var_dump(file_get_contents('users/users.txt'));
    fwrite($overwrite,$newbuild);
//    var_dump(file_get_contents('users/users.txt'));
    fclose($overwrite);
    hardreload("?user=".$userviewer);


}

if(isset($_SESSION['currentuser'])) {
    if ($_SESSION['currentuser'] == $userviewer){
        $editaccountinfo = '
        <div><hr><form method="post" action="useraccount.php?user=' . $userviewer . '" enctype="multipart/form-data">
        <div><textarea name="bio_edit" class="responsive_100_wide" placeholder="your personal bio" required>'.$userbio.'</textarea></div>
        <div><input type="text" class="responsive_100_wide" name="displayname_edit" placeholder="display name" value="'.$userDB[searchForId()]->displayname.'"></div>
        <div><input type="file" class="responsive_100_wide" name="picture" placeholder="profile image"></div>
        <input type="submit" name="submit_edit" value="commit"></form>';
    }
    else {
        $editaccountinfo = null;
    }

}

else {
    $editaccountinfo =  '<hr><div class="yellowtext">login to edit</div>';
//    var_dump($_SESSION);
}
if($userbio == ""){
    $userbio= "this user has not put anything in their bio yet";
}


$title = $userDB[searchForId()]->displayname."'s account";
include 'parts/top.php';
echo '<div><a href="index.php"><button>return</button></a></div><hr>';
echo $userbio;
echo $userlogo;
echo $editaccountinfo;
include 'parts/bottom.php';
?>