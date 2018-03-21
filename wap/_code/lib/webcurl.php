<?php 

/**
 * 通过clientsoap调取数据
 */
class Webcurl
{

	public $client;
	public $rooturl;

	public function __construct()
	{
		$this->rooturl = "http://121.40.224.47:81/WS_Library.asmx/";
	}


	/**
	 * 图书定位
	 * 条形码
	 */
	public function BookPosition($barcode){
		$res = $this->client->BookPosition($barcode);
		return $res;
	}

	/**
	 * 借书
	 * @param [type] $openid     [description]
	 * @param [type] $barcode    [description]
	 */
	public function BorrowBook($openid,$barcode){
		$res = $this->client->BorrowBook($openid,$barcode);
		return $res;
	}

	/**
	 * 根据openid获取用户信息
	 * @param [type] $openid [description]
	 */
	public function CreatedQRCode($openid){
		$res = $this->client->CreatedQRCode($openid);
		return $res;
	}

	/**
	 * 根据openid判断是否绑定，绑定则返回用户信息
	 * @param [type] $openid [description]
	 */
	public function IsBinded($openid){
		$res = $this->client->IsBinded($openid);
		return $res;
	}

	/**
	 * 在借图书查询
	 * 参数：读者证号
	 * 返回结果：成功或失败、多本图书序列，图书序列内容包括：图书条码\题名\借阅日期\应还日期
	 * @param [type] $openid [description]
	 */
	public function QueryBorrowed($openid){
		$res = $this->client->QueryBorrowed($openid);
		return $res;
	}

	/**
	 * 读者绑定
	 * 参数：读者证号、密码、微信号
	 * 返回结果：成功或失败，失败原因
	 * @param [type] $openid [description]
	 */
	public function ReaderBinding($readercode,$pwd,$openid){
		$url = $this->rooturl . "ReaderBinding";
		$data = array('readercode'=>$readercode,'pwd'=>$pwd,'openid'=>$openid);
		$res = $this->httpPost($url,$data);
		return $res;
	}

	/**
	 * 续借
	 * 参数：读者证号、密码、图书条码号
	 * 返回结果：成功或失败，失败原因
	 * @param [type] $openid  [description]
	 * @param [type] $barcode [description]
	 */
	public function RenewBook($openid,$barcode){
		$res = $this->client->RenewBook($openid,$barcode);
		return $res;
	}

	/**
	 * 图书查询
	 * 参数：图书条码号 or 题名 or 责任者 or ISBN号
	 * 返回结果：成功或失败，多本图书序号，图书序列内容包括：图书条码\题名\责任者\借阅状态\层架编码
	 * @param [type] $barcode [description]
	 * @param [type] $title   [description]
	 * @param [type] $author  [description]
	 * @param [type] $isbn    [description]
	 */
	public function SearchBook($barcode,$title,$author,$isbn){
		$res = $this->client->SearchBook($barcode,$title,$author,$isbn);
		return $res;
	}

	/**
	 * 转借
	 * 参数：读者证号、密码、图书条码号、原读者证号
	 * 返回结果：成功或失败，失败原因
	 * @param [type] $openid     [description]
	 * @param [type] $readercode [description]
	 * @param [type] $barcode    [description]
	 */
	public function Subtenancy($openid,$readercode,$barcode){
		$res = $this->client->Subtenancy($openid,$readercode,$barcode);
		return $res;
	}


	function httpPost($url,$data){
		$curl = curl_init();
	    //设置抓取的url
	    curl_setopt($curl, CURLOPT_URL, $url);
	    //设置头文件的信息作为数据流输出
	    curl_setopt($curl, CURLOPT_HEADER, 1);
	    //设置获取的信息以文件流的形式返回，而不是直接输出。
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    //设置post方式提交
	    curl_setopt($curl, CURLOPT_POST, 1);
	    //设置post数据
	    //$data = array('readercode'=>'asdfasd','pwd'=>'asdf','openid'=>'rrrr');
	    $data_string = http_build_query($data);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
	    //执行命令
	    $result = curl_exec($curl);
	    echo  $result;
	    $xml = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$array = json_decode($json,TRUE);
	    //$xml = new SimpleXMLElement($result);
	    //var_dump($xml);
	    //关闭URL请求
	    curl_close($curl);
	    return $result;
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
}
