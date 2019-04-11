<?php
session_start();
include 'parts/functs.php';
//var_dump($fileContent);
function searchForId($search_value,$search_array,$key) {
//    var_dump($search_value);
//    var_dump($search_array);
//    var_dump($key);
    $it = $search_value;
    $ar = $search_array;
    for($x=0;$x<count($ar);$x++){
//        if (json_decode($ar[$x])->$key == $it){
//            return $x;
//        }

        if($ar[$x]->$key == $it){return $x;}
    };
    return false;
}

$title = 'Forum BvH';
include 'parts/top.php';


//$dir= 'Posts';
//$handle = fopen($dir.'/Posts.txt','a+');
//$fileContent = explode('<--->',fread($handle,filesize($dir.'/Posts.txt')));
//array_pop($fileContent);
//var_dump($_POST);


//$fi = new FilesystemIterator('parts', FilesystemIterator::SKIP_DOTS);
//printf("There were %d Files", iterator_count($fi));


//$files = scandir($dir);
//array_shift($files);
//array_shift($files);
//array_reverse($files);

//var_dump(json_decode($userDB[searchForId($)])->src);
$userDBblanked = $userDB;
$fileContentblanked = $fileContent;

//for($x=0;$x<count($userDBblanked);$x++) {
//    $userDBblanked[$x] = json_decode($userDBblanked[$x]);
//}
//for($x=0;$x<count($fileContentblanked);$x++) {
//    $fileContentblanked[$x] = json_decode($fileContentblanked[$x]);
//}
//if(!isset($_SESSION['currentuser'])){
//    for($x=0;$x<count($userDB);$x++){
//        for($y=5;$y<(strlen($userDBblanked[$x]->username)-3);$y++)
//            $userDBblanked[$x]->username[$y] = "*";
//    }
//    for($x=0;$x<count($fileContentblanked);$x++){
//        for($y=5;$y<(strlen($fileContentblanked[$x]->user)-3);$y++)
//            $fileContentblanked[$x]->user[$y] = "*";
//    }
//}

if(isset($_POST['submitlogin'])){
//    var_dump($_POST);
    $attemptusername = htmlspecialchars($_POST['username']);
    $attemptpassword = hash('sha256',htmlspecialchars($_POST['password']));
    for ($x=0;$x<count($userDB);$x++){
//        echo $userDB[$x];
//        $userDB[$x] = json_decode($userDB[$x]);
        if ($attemptusername == $userDB[$x]->username && $attemptpassword == $userDB[$x]->password){
            $_SESSION['currentuser'] = $attemptusername;
            $_SESSION['sessid'] = session_id();
            setcookie('PHPSESSID',$_SESSION['sessid'],time() + 3600, "/");
            hardreload(null);
        }

    }
}


if(isset($_POST['submitsignup'])) {
    $username = htmlspecialchars($_POST['username']);
    $displayname = htmlspecialchars($_POST['displayname']);
    $password = hash('sha256',htmlspecialchars($_POST['password']));
    $passwordcheck = hash('sha256',htmlspecialchars($_POST['passwordcheck']));



    //    var_dump($_POST);
//    var_dump($_FILES);


// Check if image file is a actual image or fake image
//    echo isset($_FILES["picture"]);
    if(isset($_FILES["picture"])&&$_FILES["picture"]['name'] != '') {
        $target_dir = "userImage/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $target_file = $target_dir . basename($username . '.' . $imageFileType);

        $check = getimagesize($_FILES["picture"]["tmp_name"]);
        if ($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
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
            echo "Sorry, your file was not uploaded.";
//            $target_file = 'http://oakclifffilmfestival.com/assets/placeholder-user.png';
        } else {
            if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["picture"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }












    $y = 0;
    for ($x = 0; $x < count($userDB); $x++) {
//        $fileContent[$x] = json_decode($fileContent[$x]);
        if ($username != json_decode($userDB[$x])->username) {
            $y += 0;
        } else {
            $y++;
        }
    }
//    echo $y;
    if ($y == 0) {
        if ($password == $passwordcheck) {
            $currentUser = [
                "username" => $username,
                "displayname" => $displayname,
                "password" => $password,
                "bio"=>"",
                "src" => ""
            ];
            if ($uploadOk == 1){
//                var_dump( $_FILES["picture"]["name"]);
//                var_dump($target_file);

                $userimage = ["src"=>$target_file];
//                echo $userimage;
                $currentUser = array_replace($currentUser,$userimage);

            }
            fwrite($loginsignuphandle, json_encode($currentUser));
            fwrite($loginsignuphandle, '<--->');
            fclose($loginsignuphandle);
            $_SESSION['currentuser'] = $username;
            $_SESSION['sessid'] = session_id();
            setcookie('PHPSESSID',$_SESSION['sessid'],time() + 3600, "/");
            hardreload(null);
//            echo "<script>window.location = useraccount.php?user=".$username."</script>";

        } else {
            echo "error";
        }
    } else {
        echo '<script>alert("username already exists");</script>';
    }
}


$cookie_name = 'currentuser';
$create_post = '<div class="yellowtext">login to create posts</div>';
$userlogo = null;
//var_dump($_COOKIE);

if(!isset($_SESSION[$cookie_name])) {
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
    <form name="signup" method="post" id="signupform" style="display:none" enctype="multipart/form-data">
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
<div><a href="useraccount.php?user='.$currentusername.'"><button>your user bio</button></a></div>
<form action="" method="post"><input type="submit" name="logout" value="logout"></form>';



//    echo json_decode($userDB[searchForId($_SESSION[$cookie_name],$userDB,"username")])->src;
//    var_dump($userDB);
    if (file_exists($userDB[searchForId($_SESSION[$cookie_name],$userDB,"username")]->src)) {
//            var_dump($userDB);
            $userlogo = '<img class="logo" src="'.$userDB[searchForId($_SESSION[$cookie_name],$userDB,"username")]->src.'">';
    }
    else{
        $userlogo = '<img class="logo" src="userImage/guest.png">';
    }
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
    $create_post = null;
    $create_post .= '
    <button onclick="document.getElementById(\'createPost\').style.display=\'table\';this.style.display=\'none\'">create post</button>
    <form id="createPost" style="display:none" method="post">
        <h4>create post</h4>
<!--        <label for="user">user: </label><input type="email" name="user" required><br><br>-->
        <div class="row">
            <input class="cell" type="hidden" name="user" required value="'.$_SESSION[$cookie_name].'">
            <label class="cell responsive_hide" for="title">title: </label><input class="responsive_100_wide" type="text" name="title" maxlength="20" placeholder="Title" required><br><br>
        </div>
        <div class="row">
            <label class="cell responsive_hide" for="message">post: </label><textarea class="responsive_100_wide" name="message" placeholder="Post" required></textarea><br><br>
        </div>
        <div class="row">
            <div class="cell responsive_hide">
                Recapcha:
            </div>
            <div class="cell">
                <div class="g-recaptcha responsive_100_wide" data-sitekey="6LdmupkUAAAAAMojiX7nQeH7jUE-YqIEQPpoWxAe"></div>
            </div>
        </div>
        <div class="row">
            <div class="cell responsive_hide">
            </div>
                <input class="responsive_100_wide" type="submit" name="submit" value="submit post">
            </div>
        </div>
    </form></div>';
    $loginbuttons = "";

}

if(isset($_POST['submit'])&&isset($_SESSION['currentuser'])&&$response != null && $response->success){
//    var_dump($_POST['g-recaptcha-response']);
    $postTitel = Substr(htmlspecialchars($_POST['title']), 0, 20);
    $postUser = htmlspecialchars($_SESSION['currentuser']);
    $postMessage =htmlspecialchars($_POST['message']);
    $y = 0;
    for($x=0;$x<count($fileContent);$x++){
//        $fileContent[$x] = json_decode($fileContent[$x]);
        if ($postTitel != $fileContent[$x]->title){
            $y += 0;
        }
        else {$y++;}
    }
//    echo $y;
    if ($y == 0){
        $post = ['title'=>$postTitel,'user'=>$postUser,'message'=>$postMessage,'comments'=>$postUser.'>--< created this post on: '.date("Y-m-d H:i:s")];
        fwrite($handle,json_encode($post));
        fwrite($handle,'<--->');
        hardreload(null);
    }
    else{
        echo '<script>alert("titel bestaat al");</script>';
    }
//    $jCode = json_encode($post);

    //    json_encode($post);
}

$postList = '<div>Posts:</div><div class="table">' ;
$postListresponsive = "<div class=\"responsive_table\">";



for($x=0;$x<count($fileContent);$x++){
//    $fileContent[$x] = json_decode($fileContent[$x]);
    $postList .= '<div class="row">';
//    var_dump($fileContent[$x]);
    $postList .= '<a href="reader.php?item='.$fileContent[$x]->title.'">  <div class="cell">titel:</div>';
    $postList .= '<div class="cell">'.$fileContentblanked[$x]->title.'</div></a>';
    $postList .= '<div class="cell">user:</div>';
    $postList .= '<a href="useraccount.php?user='.$fileContent[$x]->user.'"><div class="cell" style="font-size: 0.5em;">';
    $postList .= $userDB[searchForId($fileContent[$x]->user,$userDB,"username")]->displayname.'</div></a>';
    $postList .= '</div>';

    $postListresponsive .= '<div class="row">';
    $postListresponsive .= '<a href="reader.php?item='.$fileContent[$x]->title.'">  <div class="cell">titel:</div>';
    $postListresponsive .= '<div class="cell">'.$fileContentblanked[$x]->title.'</div></a>';
    $postListresponsive .= '</div><div class="row">';
    $postListresponsive .= '<a href="useraccount.php?user='.$fileContent[$x]->user.'"><div class="cell">user:</div>';
    $postListresponsive .= '<div class="cell">'.$userDB[searchForId($fileContent[$x]->user,$userDB,"username")]->displayname.'</div></a>';
    $postListresponsive .= '</div><div class="row"><hr></div>';

}
$postList .= '</div>'.$postListresponsive."</div>" ;

//echo var_dump($files);
//echo'<div class="itemList">';
//var_dump($fileContent);
//for($x=0;$x < count($files);$x++){
//    $currentItem = explode('.',$files[$x]);
//    $filePath = 'Artiesten/'.$currentItem[0].'/image/logo.jpg';
////    echo $filePath;
//    if (file_exists($filePath)) {
//        $images = '<img class="previews" src="'.$filePath.'" alt= "'.$currentItem[0].'">';
//    } else {
//        $images =  $currentItem[0];
//    };
//    echo"
//    <a href='Artiesten/$currentItem[0]'>
//    <div class=\"item\" id=\"$currentItem[0]\">".$images."</div>
//    </a>
//    ";
//}
//echo"</div>";
fclose($handle);

?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
<?=$userlogo?>



<?=$postList?>

<?=$create_post?>





<?php
include 'parts/bottom.php';
?>