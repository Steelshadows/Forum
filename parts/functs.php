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