<?php
session_start();
function searchForId($search_value,$search_array) {
    $currentItem = $search_value;
    $fileContent = $search_array;
    for($x=0;$x<count($fileContent);$x++){
        if ($fileContent[$x]->title == $currentItem){
            return $x;
        }
    };
}
function hardreload(){
    echo '<script>
            baseurl = location.protocol + \'//\' + location.host + location.pathname;
            window.location = baseurl;</script>';
}
$title = 'Forum BvH';
include 'parts/top.php';
$dir= 'Posts';
$handle = fopen($dir.'/Posts.txt','a+');
$fileContent = explode('<--->',fread($handle,filesize($dir.'/Posts.txt')));
array_pop($fileContent);
//var_dump($_POST);


//$fi = new FilesystemIterator('parts', FilesystemIterator::SKIP_DOTS);
//printf("There were %d Files", iterator_count($fi));


//$files = scandir($dir);
//array_shift($files);
//array_shift($files);
//array_reverse($files);
$loginsignupdir = 'users';
$loginsignuphandle = fopen($loginsignupdir.'/users.txt','a+');
$userDB = explode('<--->',fread($loginsignuphandle,filesize($loginsignupdir.'/users.txt')));
array_pop($userDB);
//var_dump(json_decode($userDB[searchForId($)])->src);


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
            hardreload();
        }

    }
}


if(isset($_POST['submitsignup'])) {
    $target_dir = "userImage/";
    $target_file = $target_dir . basename($_FILES["picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
//    var_dump($_POST);
//    var_dump($_FILES);


// Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["picture"]["tmp_name"]);
    if($check !== false) {
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
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["picture"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }









    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $passwordcheck = htmlspecialchars($_POST['passwordcheck']);


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
                "password" => $password,
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
            $_SESSION['currentuser'] = $attemptusername;
            $_SESSION['sessid'] = session_id();
            setcookie('PHPSESSID',$_SESSION['sessid'],time() + 3600, "/");
//            hardreload();
        } else {
            echo "error";
        }
    } else {
        echo '<script>alert("username moet uniek zijn");</script>';
    }
}


$cookie_name = 'currentuser';
$create_post = '<div class="yellowtext">login to create posts</div>';
//var_dump($_COOKIE);

if(!isset($_SESSION[$cookie_name])) {
    echo '<div>Welkom, guest<br>    
    <button id="showhidelogin" onclick="document.getElementById(\'loginform\').style.display=\'block\';this.style.display=\'none\';document.getElementById(\'showhidesignup\').style.display=\'none\'">Login</button>
    <form name="login" method="post" id="loginform" style="display:none"><br>
        <input type="email" name="username" placeholder="username" required><br>
        <input type="text" name="password" placeholder="password" required><br>
        <input type="submit" name="submitlogin">
    </form>
    <button id="showhidesignup" onclick="document.getElementById(\'signupform\').style.display=\'block\';this.style.display=\'none\';document.getElementById(\'showhidelogin\').style.display=\'none\'">Signup</button>
    <form name="signup" method="post" id="signupform" style="display:none" enctype="multipart/form-data">
        <input type="email" name="username" placeholder="username" required><br>
        <input type="text" name="password" placeholder="password" required><br>
        <input type="text" name="passwordcheck" placeholder="password check" required><br>
        <input type="file" name="picture" placeholder="profile image ">
        <input type="submit" name="submitsignup">
    </form></div>';
} else {

    echo "<div>Welkom, " . $_SESSION[$cookie_name] . '<br>';
    echo searchForId($_SESSION[$cookie_name],$userDB);
    echo json_decode($userDB[searchForId($_SESSION[$cookie_name],$userDB)])->src;
    $create_post = '
    
    <button onclick="document.getElementById(\'createPost\').style.display=\'table\';this.style.display=\'none\'">create post</button>
    <form id="createPost" style="display:none" method="post">
        <h4>create post</h4>
<!--        <label for="user">user: </label><input type="email" name="user" required><br><br>-->
        <div class="row">
            <input class="cell" type="hidden" name="user" required value="'.$_SESSION[$cookie_name].'">
            <label class="cell" for="title">title: </label><input type="text" name="title" required><br><br>
        </div>
        <div class="row">        
            <label class="cell" for="message">post: </label><textarea name="message" required></textarea><br><br>
            <input class="cell" type="submit" name="submit">
        </div>
    </form></div>';
    $loginbuttons = null;
}

if(isset($_POST['submit'])){
    $postTitel = htmlspecialchars($_POST['title']);
    $postUser = htmlspecialchars($_POST['user']);
    $postMessage =htmlspecialchars($_POST['message']);
    $y = 0;
    for($x=0;$x<count($fileContent);$x++){
//        $fileContent[$x] = json_decode($fileContent[$x]);
        if ($postTitel != json_decode($fileContent[$x])->title){
            $y += 0;
        }
        else {$y++;}
    }
//    echo $y;
    if ($y == 0){
        $post = ['title'=>$postTitel,'user'=>$postUser,'message'=>$postMessage,'comments'=>''];
        fwrite($handle,json_encode($post));
        fwrite($handle,'<--->');
    }
    else{
        echo '<script>alert("titel niet uniek");</script>';
    }
//    $jCode = json_encode($post);

    //    json_encode($post);
}

$postList = '<div class="table">' ;
for($x=0;$x<count($fileContent);$x++){
    $fileContent[$x] = json_decode($fileContent[$x]);
    $postList .= '<a href="reader.php?item='.$fileContent[$x]->title.'" class="row">';
    $postList .= '<div class="cell">titel:</div>';
    $postList .= '<div class="cell">'.$fileContent[$x]->title.'</div>';
    $postList .= '<div class="cell">user:</div>';
    $postList .= '<div class="cell" style="font-size: 0.5em;">'.$fileContent[$x]->user.'</div>';
    $postList .= '</a>';

}
$postList .= '</div>' ;

//echo var_dump($files);
echo $dir.':';
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




    <?=$postList?>

    <?=$create_post?>





<?php
include 'parts/bottom.php';
?>