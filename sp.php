<?php
require 'vendor/autoload.php';
use QL\QueryList;


set_time_limit(0);
//获取采集对象
 $hj = QueryList::Query('https://www.amazon.com/gp/search/ref=sr_pg_1?rh=i%3Aaps%2Ck%3Aleggings+NYDJ&keywords=leggings+NYDJ&ie=UTF8&qid=1502365892', array(
    'title'   => array('div.a-spacing-none>div.a-spacing-mini>a>h2.a-size-base', 'text'),
    'price'   => array('div.a-spacing-none>a.a-link-normal>span', 'text'),
    'comment' => array('div.a-spacing-none>a.a-size-small', 'text'),
), 'li>div.s-item-container','')->getData(function($item){
        return $item;
    }); 
    

$con=mysqli_connect("localhost","root","123456","spider"); 
if (mysqli_connect_errno($con)) 
{ 
    echo "连接 MySQL 失败: " . mysqli_connect_error(); 
} 


$file = fopen('/home/halo/working/query/QueryList/text.xlsx', 'w');
fwrite($file, "商品名称\t价格\t评论数\t\n");

  foreach ($hj as $key => $value) {
	
	$title = str_replace('\r','',$value['title']);
	$price = str_replace('\r','',$value['price']);
	$comment = str_replace('\r','',$value['comment']);
	$title = str_replace('\n','',$title);
	$price = str_replace('\n','',$price);
	$price = str_replace('\t','',$price);
	$comment = str_replace('\n','',$comment);
	$price = str_replace(' ','',$price);
	
	$re = mysqli_query($con,"INSERT INTO `test` (`title`,`price`,`comment`) VALUES ('".$title."','".$price."','".$comment."')");
    
    
    fwrite($file, $title."\t".$price."\t".$comment."\t\n");
}  


mysqli_close($con);
fclose($file);  
//关闭连接