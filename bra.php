<?php
require 'vendor/autoload.php';
use QL\QueryList;
//获取采集对象
// $pn = $_GET['page'];

$hj = QueryList::Query("https://www.amazon.com/s/ref=sr_in_-2_p_89_7?fst=as%3Aoff&rh=n%3A7141123011%2Cn%3A7147440011%2Cn%3A1040660%2Cn%3A9522931011%2Cn%3A14333511%2Cn%3A1044960%2Ck%3Asports+bra%2Cp_89%3ABrooks&bbn=1044960&keywords=sports+bra&ie=UTF8&qid=1502416854&rnid=2528832011", array(
    'title' => array('div.a-spacing-none>div.a-spacing-micro>a>h2.a-size-small', 'text'),
    'price' => array('div.a-spacing-none>a.a-link-normal>span', 'text'),
    'link'  => array('div.a-spacing-none>div.a-spacing-micro>a', 'href'),
), 'li>div.s-item-container')->getData(function ($item) {
    $item['link'] = QueryList::Query($item['link'], array(
        'comment' => array('div#averageCustomerReviews_feature_div>div#averageCustomerReviews>span.a-declarative>a#acrCustomerReviewLink>span', 'text'),
        'price'   => array('div#unifiedPrice_feature_div>div#price>span#priceblock_ourprice', 'text'),

    ))->data;
    return $item;
});
//输出结果：二维关联数组
print_r($hj);


//写入txt文件
// foreach ($hj as $key => $value) {
//     $title = (string) $value['title'];
//     $price = str_replace(' ', '', trim($value['price']));
//     $price = str_replace("\n", '', $price);
//     foreach ($value['link'] as $v) {
//         $comment = trim($v['comment']);
//     }
//     $content = "商品名称：".$title."   价格：".$price.'   评论数:'.$comment."\r\n";
//     $file = '/home/halo/working/query/QueryList/file.txt';
//     if ($f = file_put_contents($file, $content, FILE_APPEND)) {
//         echo "写入成功。\n" ;
//     }
// }

//写入Excel文件
$file = fopen('/home/halo/working/query/QueryList/Brooks (44).xls', 'w');
fwrite($file, "商品名称\t价格\t评论数\t\n");

  foreach ($hj as $key => $value) {
	
	$title = str_replace('\r','',$value['title']);
	$price = str_replace('\r','',$value['price']);
	$title = str_replace('\n','',$title);
	$price = str_replace('\n','',$price);
	$price = str_replace(' ','',$price);
	
	foreach ($value['link'] as $v) {
        $comment = trim($v['comment']);
		$comment = str_replace('\r','',$comment);
		$comment = str_replace('\n','',$comment);
    }
    
    
    fwrite($file, $title."\t".$price."\t".$comment."\t\n");
}  
fclose($file);  
//关闭连接