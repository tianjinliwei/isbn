<?php
//剪切
function cut($content,$start,$end) {
$r = explode($start, $content);
if (isset($r[1])) {
$r = explode($end, $r[1]);
return $r[0];
}
return '';
}
//模拟get请求
function get($url) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.7 (KHTML, like Gecko) Chrome/20.0.1099.0 Safari/536.7 QQBrowser/6.14.15493.201');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
curl_close($ch);
return $result;
}
$isbn = @$_GET['isbn'];
$surl = 'https://book.douban.com/isbn/'.$isbn.'/';
$headers = json_encode(get_headers($surl),true);
$headers = json_encode($headers,true);
$surl  = cut($headers,'Location: ','"');
$surl  = str_replace('\\','' ,$surl);//302地址
$data = get($surl);
$data_1=cut($data,'application/ld+json">','</script>');
 $data_1 = json_decode($data_1,true);
$res['title'] = $data_1['name'];//书名
$author = $data_1['author'];
if($author[0]==''){
  $author[0]['name'] = '未知';
}
$res['author'] =$author;//作者
$res['logo'] = cut($data,'data-pic="','"');//图标
$publisher = cut($data,'出版社:</span>','<br/>');
if($publisher==''){
  $publisher ='未知';
}
$res['publisher'] =$publisher;//出版社
$published = cut($data,'出版年:</span>','<br/>');
if($published==''){
  $published ='未知';
}
$res['published'] =$published;//出版年
$page = cut($data,'页数:</span>','<br/>');
if($page==''){
  $page ='未知';
}
$res['page'] =$page;//页数
$price = cut($data,'定价:</span>','<br/>');
if($price==''){
  $price ='未知';
}
$res['price'] =$price;//定价
$designed = cut($data,'装帧:</span>','<br/>');
if($designed==''){
  $designed ='未知';
}
$res['designed'] =$designed;//装帧
$description = cut($data,'class="intro">','</p>');
$description = explode('<p>',$description)[1];
if($description==''){
  $description ='未知';
}
$res['description'] =$description;//简介
$res = json_encode($res,true);
echo $res;

?>