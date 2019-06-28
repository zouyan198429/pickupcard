<?php
$recordDir = __DIR__;
$webpath = "http://comp.kezhuisu.net/";
//名字获取
$wxid=$_POST["num"];//0
//时间获取
$diaryTime=date('YmdHis');//rand(1,100);//$_POST["datetime"];// 2018/07/20 20:13:13  20
//var_dump($diaryTime);die;
//获取日期
$date = $_POST["date"];// 2018/07/
$a = substr($_FILES['file']['type'],6);//获取图片后缀
//$a =  strstr( $_FILES['file']['type'], '/');
$file_name=$date . $diaryTime.'_'.$wxid.'.'.$a;//拼装存储地址path  2018/07/20_0.jpeg
$file_name1=$date. $diaryTime.'_'.$wxid.'.'.$a;//拼装图片浏览path  2018/07/20_0.jpeg
// $path = "D:\wamp64\www\File\\".$file_name;//存储path
$path = $recordDir . "/".$file_name;//存储path // /srv/www/lvdong/comp.kezhuisu.net/public/2018/07/20_0.jpeg

$dir = iconv("UTF-8", "GBK",$recordDir . "/".$date);//判断文件夹是否存在 /srv/www/lvdong/comp.kezhuisu.net/public/2018/07/

if (!file_exists($dir)){
    mkdir ($dir,0777,true);//不存在 创建新文件夹
    $panduan = move_uploaded_file($_FILES['file']['tmp_name'], $path);//存入图片
} else {
    $panduan = move_uploaded_file($_FILES['file']['tmp_name'], $path);//存入已有文件夹内
}
//保存到指定路径  指定名字
if ($panduan){//存储成功
    $res = ['errCode'=>0,'errMsg'=>'图片上传成功','file'=>$webpath. $file_name1,'Success'=>true];
    echo json_encode($res);
}else{//失败
    // $res = ['errCode'=>0,'errMsg'=>'图片上传失败','file'=>'http://127.0.0.1:8095/xx.png','Success'=>!true];
    $res = ['errCode'=>0,'errMsg'=>'图片上传失败','file'=>$webpath . 'fail.jpg','Success'=>!true];
    // return json($res);
    return json_encode($res);
}
?>
