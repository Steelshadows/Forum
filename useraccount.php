<?php
session_start();
$userviewer = $_GET['user'];
$title = $userviewer."'s account";
function searchForId() {
    global $userviewer;
    global $userDB;
    for($x=0;$x<count($userDB);$x++){
        if ($userDB[$x]->username == $userviewer){
            return $x;
        }
    };
}
function hardreload(){
    global $userviewer;
    echo '<script>
            baseurl = location.protocol + \'//\' + location.host + location.pathname+"?user="'.$userviewer.';
            window.location = baseurl;</script>';
}


$loginsignupdir = 'users';
$loginsignuphandle = fopen($loginsignupdir.'/users.txt','a+');
$userDB = explode('<--->',fread($loginsignuphandle,filesize($loginsignupdir.'/users.txt')));
array_pop($userDB);
for($x=0;$x<count($userDB);$x++){
    $userDB[$x] = json_decode($userDB[$x]);
};
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
//        if ($_FILES["picture"]["size"] > 500000) {
//            echo "Sorry, your file is too large.";
//            $uploadOk = 0;
//        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
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
    $new_bio = htmlspecialchars($_POST["bio_edit"]);
    $userDB[searchForId()]->bio = $new_bio;


    for($x=0;$x<count($userDB);$x++){
        $newbuild .= json_encode($userDB[$x]).'<--->';
    }
//    echo $newbuild;
    $overwrite = fopen('users/users.txt','w+');
    fwrite($overwrite,$newbuild);
    fclose($overwrite);
    hardreload();


}

if(isset($_SESSION['currentuser'])) {
    if ($_SESSION['currentuser'] == $userviewer){
        $editaccountinfo = '
        <div><hr><form method="post" action="useraccount.php?user=' . $userviewer . '" enctype="multipart/form-data">
        <textarea name="bio_edit" placeholder="your personal bio" required>'.$userbio.'</textarea>
        <input type="file" name="picture" placeholder="profile image">
        <input type="submit" name="submit_edit"></form>';
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


include 'parts/top.php';
echo '<div><a href="index.php"><button>return</button></a></div><hr>';
echo $userbio;
echo $userlogo;
echo $editaccountinfo;
include 'parts/bottom.php';
?>