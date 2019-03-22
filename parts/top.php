<?php
if(!isset($title)){$title='';}
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
<h4>u bevind zich op $title</h4>
$images
"








?>