<?php
$cookie_name = 'currentuser';
require_once "recapchalib.php";
if(!isset($title)){$title='';}
// your secret key
$secret = "6LdmupkUAAAAABk2sM3S7-ilP3-1SRRotiga_bQH";

// empty response
$response = null;

// check secret key
$reCaptcha = new ReCaptcha($secret);
if (isset($_POST["g-recaptcha-response"])) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );

}
//if ($response != null && $response->success) {
//    echo "Hi thanks for submitting the form!";
//}

function hardreload($newlocation){
    global $currentItem;
    if ($newlocation == null) {
        echo '<script>
            baseurl = location.protocol + \'//\' + location.host + location.pathname;
            window.location = baseurl;</script>';
    }
    else if($newlocation == "home"){
        echo '<script>window.location.href = "index.php";</script>';
    }
    else if($newlocation[0] = '?'){"
        baseurl = location.protocol + '//' + location.host + location.pathname".$newlocation.";
        window.location = baseurl;</script>";
    }
    else if($newlocation == "here"){
        echo '<script>window.location = document.location.href</script>';
    }
}
if(isset($_POST['submitlogin'])){
//    var_dump($_POST);
    $attemptusername = htmlspecialchars($_POST['username']);
    $attemptpassword = htmlspecialchars($_POST['password']);
    for ($x=0;$x<count($userDB);$x++){
//        echo $userDB[$x];
        $userDB[$x] = json_decode($userDB[$x]);
        if ($attemptusername == $userDB[$x]->username && $attemptpassword == $userDB[$x]->password){
            $_SESSION['currentuser'] = $attemptusername;
            $_SESSION['sessid'] = session_id();
            setcookie('PHPSESSID',$_SESSION['sessid'],time() + 3600, "/");
            hardreload("home");
        }

    }
}
if(isset($_POST['logout'])){
    echo "logout";
    session_destroy();
    hardreload("home");
}

//if(isset($_POST['submitsignup'])) {
//    $username = htmlspecialchars($_POST['username']);
//    $displayname = htmlspecialchars($_POST['displayname']);
//    $password = htmlspecialchars($_POST['password']);
//    $passwordcheck = htmlspecialchars($_POST['passwordcheck']);
//
//
//
//        var_dump($_POST);
////    var_dump($_FILES);
//
//
//// Check if image file is a actual image or fake image
////    echo isset($_FILES["picture"]);
//    if(isset($_FILES["picture"])&&$_FILES["picture"]['name'] != '') {
//        $target_dir = "userImage/";
//        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
//        $uploadOk = 1;
//        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
//        $target_file = $target_dir . basename($username . '.' . $imageFileType);
//
//        $check = getimagesize($_FILES["picture"]["tmp_name"]);
//        if ($check !== false) {
////        echo "File is an image - " . $check["mime"] . ".";
//            $uploadOk = 1;
//        } else {
//            echo "File is not an image.";
//            $uploadOk = 0;
//        }
//        if (file_exists($target_file)) {
//            echo "Sorry, file already exists.";
//            $uploadOk = 0;
//        }
//        if ($_FILES["picture"]["size"] > 500000) {
//            echo "Sorry, your file is too large.";
//            $uploadOk = 0;
//        }
//        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//            && $imageFileType != "gif") {
//            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//            $uploadOk = 0;
//        }
//        if ($uploadOk == 0) {
//            echo "Sorry, your file was not uploaded.";
////            $target_file = 'http://oakclifffilmfestival.com/assets/placeholder-user.png';
//        } else {
//            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
//                echo "The file " . basename($_FILES["picture"]["name"]) . " has been uploaded.";
//            } else {
//                echo "Sorry, there was an error uploading your file.";
//            }
//        }
//    }
//
//
//
//    $y = 0;
//    for ($x = 0; $x < count($userDB); $x++) {
////        $fileContent[$x] = json_decode($fileContent[$x]);
//        if ($username != json_decode($userDB[$x])->username) {
//            $y += 0;
//        } else {
//            $y++;
//        }
//    }
////    echo $y;
//    if ($y == 0) {
//        if ($password == $passwordcheck) {
//            $currentUser = [
//                "username" => $username,
//                "displayname" => $displayname,
//                "password" => $password,
//                "bio"=>"",
//                "src" => ""
//            ];
//            if ($uploadOk == 1){
////                var_dump( $_FILES["picture"]["name"]);
////                var_dump($target_file);
//
//                $userimage = ["src"=>$target_file];
////                echo $userimage;
//                $currentUser = array_replace($currentUser,$userimage);
//
//            }
//            fwrite($loginsignuphandle, json_encode($currentUser));
//            fwrite($loginsignuphandle, '<--->');
//            fclose($loginsignuphandle);
//            $_SESSION['currentuser'] = $username;
//            $_SESSION['sessid'] = session_id();
//            setcookie('PHPSESSID',$_SESSION['sessid'],time() + 3600, "/");
//            hardreload("home");
////            echo "<script>window.location = useraccount.php?user=".$username."</script>";
//
//        } else {
//            echo "error";
//        }
//    } else {
//        echo '<script>alert("username already exists");</script>';
//    }
//}
if (!isset($_SESSION[$cookie_name])) {
    $introtext = '<div>Welkom, guest<br>    
<button id="showhidelogin" onclick="document.getElementById(\'loginform\').style.display=\'table\';this.style.display=\'none\';document.getElementById(\'showhidesignup\').style.display=\'none\'">Login</button>
<form name="login" method="post" id="loginform" style="display:none">
    <div class="row">
        <div class="cell">username: </div>
        <div class="cell"><input type="text" name="username" placeholder="username" required></div>
    </div> 
    <div class="row">
        <div class="cell">password:</div>
        <div class="cell"><input type="password" name="password" placeholder="password" required></div>
    </div> 
    <div class="row">
        <div class="cell"></div>
        <div class="cell"><input type="submit" name="submitlogin" value="login"></div>
    </div> 
</form>
<button id="showhidesignup" onclick="document.getElementById(\'signupform\').style.display=\'table\';this.style.display=\'none\';document.getElementById(\'showhidelogin\').style.display=\'none\'">Signup</button>
<form name="signup" action="index.php" method="post" id="signupform" style="display:none" enctype="multipart/form-data">
    <div class="row">
        <div class="cell">Username: </div>
        <div class="cell"><input type="text" name="username" placeholder="username" required></div>
    </div>
    <div class="row">
        <div class="cell">Displayname: </div>
        <div class="cell"><input type="text" name="displayname" placeholder="username" required></div>
    </div> 
    <div class="row">
        <div class="cell">Password:</div>
        <div class="cell"><input type="password" name="password" placeholder="password" required></div>
    </div> 
    <div class="row">
        <div class="cell">Password check:</div>
        <div class="cell"><input type="password" name="passwordcheck" placeholder="password check" required></div>
    </div> 
    <div class="row">
        <div class="cell">Profile Picture:</div>
        <div class="cell"><input type="file" name="picture" placeholder="profile image"></div>
    </div> 
    <div class="row">
        <div class="cell"></div>
        <div class="cell"><input type="submit" name="submitsignup" value="sign-in"></div>
    </div> 
</form></div>';
} else {
    $currentusername = $_SESSION[$cookie_name];
    $introtext = '<h3>Welkom, ' . $currentusername . '</h3>
<div><a href="useraccount.php?user=' . $currentusername . '"><button>your user bio</button></a></div>
<form action="" method="post"><input type="submit" name="logout" value="logout"></form>';


//    echo json_decode($userDB[searchForId($_SESSION[$cookie_name],$userDB,"username")])->src;
//    if (file_exists(json_decode($userDB[searchForId($_SESSION[$cookie_name], $userDB, "username")])->src)) {
//        $userlogo = '<img class="logo" src="' . json_decode($userDB[searchForId($_SESSION[$cookie_name], $userDB, "username")])->src . '">';
//    } else {
//        $userlogo = '<img class="logo" src="userImage/guest.png">';
//    }
//    $postLis = null ;
//    for($x=0;$x<count($fileContent);$x++){
//        if (searchForId($_SESSION[$cookie_name],$fileContent,'user')) {
//            echo searchForId($_SESSION[$cookie_name],$fileContent,'user')d;
//            $fileContent[$x] = json_decode($fileContent[$x]);
//            $postLis .= '<a href="reader.php?item=' . $fileContent[$x]->title . '" class="row">';
//            $postLis .= '<div class="cell">titel:</div>';
//            $postLis .= '<div class="cell">' . $fileContent[$x]->title . '</div>';
//            $postLis .= '<div class="cell">user:</div>';
//            $postLis .= '<div class="cell" style="font-size: 0.5em;">' . $fileContent[$x]->user . '</div>';
//            $postLis .= '</a>';
//        }
//    }
//    if ($postLis != null){
//        echo "user posts: ";
//        $postLis .= "<hr>";
//        echo $postLis;
//    }
    $loginbuttons = "";
}

$loginsignupdir = 'users';
$loginsignuphandle = fopen($loginsignupdir.'/users.txt','a+');
$userDB = explode('<--->',fread($loginsignuphandle,filesize($loginsignupdir.'/users.txt')));
array_pop($userDB);
for($x=0;$x<count($userDB);$x++){
//    echo     $userDB[$x]."<br>";
    $userDB[$x] = json_decode($userDB[$x]);
}
$dir= 'Posts';
$handle = fopen($dir.'/Posts.txt','a+');
$fileContent = explode('<--->',fread($handle,filesize($dir.'/Posts.txt')));
array_pop($fileContent);
for($x=0;$x<count($fileContent);$x++){
    $fileContent[$x] = json_decode($fileContent[$x]);
}
//var_dump($userDB);
//var_dump(json_last_error());
//var_dump($fileContent);
//var_dump(json_last_error_msg());








$filePath = 'image/logo.jpg';
//    echo $filePath;
if (file_exists($filePath)) {
    $images = '<img class="logo" src="'.$filePath.'" alt= "'.$title.'">';
}else{$images = "";}
echo "

<!doctype html>
<html lang=\"nl\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
    <meta name=\"description\" content=\"Forum BVH\">
    <meta name=\"copyright\" content=\"Brand van Harn\">
    <meta name=\"web_author\" content=\"Brand van Harn\">
    <title>$title</title>
    <link rel=\"stylesheet\" href=\"/CSS/style.css  \"
</head>
<body>
<div class=\"masterContainer\">
<header>
$images
<h4>u bevind zich op $title</h4>
";
echo $introtext.'</header>';
?>