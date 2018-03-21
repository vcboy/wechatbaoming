<?php
/**
  * wechat php test
  */
//define your token
define("TOKEN", "8vEcHl8xQ4MHkYg5Fc799o1GfpMFwcOC");
$wechatObj = new wechatCallbackapiTest();
/*$wechatObj->valid();
$wechatObj->responseMsg();*/
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}
class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		//$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postStr = file_get_contents("php://input");

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $event = $postObj->Event;
                $eventKey = $postObj->EventKey;
                $rMsgType = $postObj->MsgType;
                //$ScanResult = $postObj->ScanResult;
                $scanResult = $postObj->ScanCodeInfo->ScanResult;

                $time = time();

                //以文本消息方式回复
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>"; 

                //接收用户的文本消息
                if($rMsgType == 'text'){
                    $msgType = "text"; //以文本消息方式回复给用户
                    $keywords = explode(' ',$keyword);
                    $contentStr = $keywords[0];
                    switch ($keywords[0]) {
                        case 'value':
                            # code...
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                    exit();
                }

				if($eventKey)
                {
              		$msgType = "text";
                    switch ($eventKey) {
                        case 'rselfmenu_0_0':
                            # code...
                            $redirect_uri = "http://live.mynep.com/weixin/index.php?action=position&barcode=".$scanResult;
                            $contentStr = "图书请<a href='".$redirect_uri."'>点击这里定位</a>";
                            break;
                        case 'rselfmenu_0_2':
                            $arr = explode('|', $scanResult);
                            if($arr[1]){
                                $act = 'renewresult&barcode='.$arr[0].'&readercode='.$arr[1];
                            }else{
                                $act = 'borrow&barcode='.$arr[0];
                            }
                            $redirect_uri = "http://live.mynep.com/weixin/index.php?action=".$act;
                            $testurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd8b0c84c1425ea57&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
                            $contentStr = "请<a href='".$redirect_uri."'>点击这里借书</a>"; 
                            break;
                        default:
                            $contentStr = $keyword.$_SERVER['SERVER_NAME'].$fromUsername;
                            # code...
                            break;
                    }
                	//$contentStr = "Welcome to wechat world! ".$eventKey.$scanResult;
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>