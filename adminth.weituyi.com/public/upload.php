<?php

//名字获取
$wxid=$_POST["wxid"];
//时间获取
$diaryTime=$_POST["diaryTime"];
//拼接存储名字
$diaryTime=substr($diaryTime,0,10).substr($diaryTime,11,2).substr($diaryTime,14,2).substr($diaryTime,17);
//得到存储名字
$file_name=$wxid.'_'.$diaryTime.'.png';
//存储路径
$path='./picture/'.$file_name;
//保存
move_uploaded_file($_FILES['picture']['tmp_name'], $path);
?>