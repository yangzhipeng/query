<?php
require 'vendor/autoload.php';
use QL\QueryList;
//获取采集对象
$hj = QueryList::Query('https://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=leggings+American+Apparel+Womens%2FLadies+Cotton+Spandex+Jersey+Leggings+%2423.70&rh=i%3Aaps%2Ck%3Aleggings+American+Apparel+Womens%2FLadies+Cotton+Spandex+Jersey+Leggings+%2423.70', array(
    'title'   => array('div.a-spacing-none>div.a-spacing-mini>a>h2.a-size-base', 'text'),
    'price'   => array('div.a-spacing-none>a.a-link-normal>span', 'text'),
    'comment' => array('div.a-spacing-none>a.a-size-small', 'text'),
), 'li>div.s-item-container')->data;
//输出结果：二维关联数组
// print_r($hj);
$con=mysqli_connect("localhost","root","123456","spider"); 
if (mysqli_connect_errno($con)) 
{ 
    echo "连接 MySQL 失败: " . mysqli_connect_error(); 
} 

$file1 = fopen('/home/halo/working/query/QueryList/text.xls', 'w');
fwrite($file1, "title\tprice\tcomment\t\n");
foreach ($hj as $key => $value) {
	$title = (string)$value['title'];
	$price = str_replace(' ', '',  trim($value['price']));
	$price = str_replace("\n", '', $price);
	$comment = trim($value['comment']);
	fwrite($file1, $title."\t".$price."\t".$comment."\t\n");

$content = "商品名称：".$title."   价格：".$price.'   评论数:'.$comment."\r\n";
fclose($file1);

$file  = '/home/halo/working/query/QueryList/file.txt';
if($f  = file_put_contents($file, $content,FILE_APPEND)){// 这个函数支持版本(PHP 5)
	$i++;
	echo "写入成功。\n".$i;
}
$title = str_replace("\n", '', $title);
$comment = str_replace("\n", '', $comment);
	$re = mysqli_query($con,"INSERT INTO ularmo1 (`title`,`price`,`comment`) VALUES ('".$title."','".$price."','".$comment."')");

	echo $re."\n";


}
mysqli_close($con);

