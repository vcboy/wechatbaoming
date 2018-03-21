<?php

$code = $_REQUEST['code'];
//var_dump($code);

function getopenid($code){
	$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxd8b0c84c1425ea57&secret=a4264fb3f6eca52c34c6717e0d341e6c&code=$code&grant_type=authorization_code";
	$res = httpGet($url);
	var_dump($res);
}

function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }

getopenid($code);
?>