<?php
// $Id$

/**
 * Controller_Mobilenews 控制器
 */

class Controller_Wxresponse extends Controller_Main
{
	

    function actionTest(){
        //$keywords = $this->test();
        /*$key = '订阅';
        if(array_key_exists($key, $keywords)) $contenStr = $keywords[$key];*/
        //var_dump($keywords);
        //$res = $this->addbuy('sdfasdfasdf','bbbbbb');
        //var_dump($res);
        exit();
    }

    public function test(){
        $ikeywords = Ikeywords::find("isdelete = 0")->getAll();
        $arrkeywords = array();
        foreach ($ikeywords as $key => $value) {
            $keyword = strtolower($value['keyword']);
            $arrkeywords[$keyword] = $value;
            # code...
        }
        //var_dump($ikeywords);
        /*foreach ($ikeywords as $key => $value) {
            
        }*/
        //$ikeywords = array('aaaa','dddddd');
        return $arrkeywords;
        //exit();
    }

    /**
     * 新增荐购记录
     * @return [type] [description]
     */
    public function addbuy($openid,$book_name){
        $Buy = new Buy();
        $Buy->openid = $openid;
        $Buy->book_name = $book_name;
        $Buy->create_time = time();
        $bres = $Buy->save();
        if(isset($bres) && $bres['id'])
            return 1;
        else
            return 0;
        //return 1;
    }

	
	function actionAccesstoken(){	
		$access_token = $this->jssdk->getAccessToken();
		var_dump($access_token);
        exit();
	}

	function actionIndex(){
		$echoStr = $this->_context->echostr;
		//exit($echoStr);
		if(!isset($echoStr)){
			$this->responseMsg();
		}else{
			$this->valid($echoStr);
		}
        exit();
	}


	public function valid($echoStr)
    {
        //$echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        	//return $echoStr;
        }
    }


    public function responseMsg(){
        $postStr = file_get_contents("php://input");
        if (!empty($postStr)){
            //libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            
            $rMsgType = $postObj->MsgType;           
            switch ($rMsgType) {
                case 'event':
                    $result = $this->receiveEvent($postObj);
                    break;
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                default:
                    # code...
                    break;
            }

        }
    }

    /**
     * 接受事件消息
     * @param  [type] $object [description]
     * @return [type]         [description]
     */
    private function receiveEvent($object){
        $event = $object->Event;
        $eventKey = $object->EventKey;
        $msgType = "text";
        $resultStr = '';

        switch ($eventKey) {
            case 'rselfmenu_0_0':
                # code...
                $scanResult = $object->ScanCodeInfo->ScanResult;        
                $redirect_uri = $this->weburl."?action=position&barcode=".$scanResult."&fr=wx";
                //$act = url('default/position',array('barcode'=>$scanResult,'fr'=>'wx'));
                $act = "/default/position/barcode/".$scanResult."/fr/wx";
                //$redirect_uri = $this->weburl.$act;
                $testurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
                $contentStr = "图书请<a href='".$redirect_uri."'>点击这里定位</a>";
                $resultStr = $this->transmitText($object,$contentStr);
                echo $resultStr;
                break;
            case 'rselfmenu_0_2':
                $scanResult = $object->ScanCodeInfo->ScanResult;
                if(strpos($scanResult,',') === false){
                    
                }else{
                    $arrresult = explode(',',$scanResult);
                    $scanResult = $arrresult[1];
                }
                $etime = time()+300;
                if(strpos($scanResult,"|") === false){
                    //$act = 'borrow&barcode='.$scanResult.'&etime='.$etime;
                    //$act = url('default/borrow',array('barcode'=>$scanResult,'etime'=>$etime));
                    $act = "/default/borrow/barcode/".$scanResult."/etime/".$etime;
                }else{
                    $arr = explode('|', $scanResult);
                    //$act = 'renewresult&barcode='.$arr[0].'&readercode='.$arr[1].'&etime='.$etime;
                    //$act = url('default/renewresult',array('barcode'=>$arr[0],'readercode'=>$arr[1],'etime'=>$etime));
                    $act = "/default/renewresult/barcode/".$arr[0]."/readercode/".$arr[1]."/etime/".$etime;
                }
                /*$arr = explode('|', $scanResult);
                if($arr[1]){
                    $act = 'renewresult&barcode='.$arr[0].'&readercode='.$arr[1];
                }else{
                    $act = 'borrow&barcode='.$arr[0];
                }*/
                //$redirect_uri = $this->weburl."?action=".$act;
                $redirect_uri = $this->weburl.$act;
                $testurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
                $contentStr = "请<a href='".$testurl."'>点击这里借书</a>";
                $resultStr = $this->transmitText($object,$contentStr);
                echo $resultStr;
                break;
            default:
                //$contentStr = $keyword.$_SERVER['SERVER_NAME'].$fromUsername;
                //$contentStr = $eventKey.'事件处理';
                //$contentStr = "卡券领取成功";
                # code...
                break;
        }
        //$scanResult = $object->ScanCodeInfo->ScanResult;
        //$contentStr = "Welcome to wechat world! ".$eventKey.$scanResult;
        //$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        /*$resultStr = $this->transmitText($object,$contentStr);
        echo $resultStr;*/
    }

    /**
     * 接受文本消息
     * @param  [type] $object [description]
     * @return [type]         [description]
     */
    private function receiveText($object)
    {
        $keyword = strtolower(trim ( $object->Content ));
        $fromUsername = trim ( $object->FromUserName );
        $result = "";
        $contentStr = "";

        //多客服人工回复模式
        if (strstr($keyword, "您好") || strstr($keyword, "你好") || strstr($keyword, "在吗")){
            //$result = $this->transmitService($object);
        }elseif(strstr($keyword,'tj')){//荐购图书
            $val = trim(substr($keyword, 2));
            if($val){
                
                //调用图书荐购API接口
                /*$res = $this->webservice->Recommend($fromUsername,$val);
                if($res->RecommendResult->Result == True){
                    $contentStr = "荐购图书《".$val."》成功";
                }*/
                $bres = $this->addbuy($fromUsername,$val);
                if($bres){
                    $contentStr = "荐购图书《".$val."》成功";
                }else{
                    $contentStr = "抱歉，荐购图书《".$val."》不成功";
                }
            }else{
                $contentStr = "请输入想要荐购的图书书名";
            }
            $result = $this->transmitText($object,$contentStr);
        }else{ //自动回复模式
            $arrkeywords = $this->test();
            if(isset($arrkeywords[$keyword])){
                $indexkeyword = $arrkeywords[$keyword];
                $reply_type = $indexkeyword['reply_type'];
                $imageArray = array();
                $newsArray = array();
                switch ($reply_type) {
                    case '1': //文本消息
                        if($indexkeyword['content']){
                            $contentStr = $indexkeyword['content'];
                            $result = $this->transmitText($object,$contentStr);
                        }                       
                        break;
                    case '2':   //图片消息
                        if($indexkeyword['media_id']){
                            $imageArray['MediaId'] = $indexkeyword['media_id'];
                            $result = $this->transmitImage($object,$imageArray);
                        }                       
                        break;
                    case '3':   //图文消息
                        if($indexkeyword['pic_path']){
                            $newsArray[] = array(
                                'Title' => $indexkeyword['title'],
                                'Description' => $indexkeyword['content'],
                                'PicUrl' => $indexkeyword['pic_path'],
                                'Url' => $indexkeyword['url']
                            );
                            $result = $this->transmitNews($object,$newsArray);
                        }
                        break;
                    default:
                        # code...
                        break;
                }
            }else{
                $contentStr = "暂时没找到您想要的内容!参考下面关键字\n\n";
                foreach ($arrkeywords as $key => $value) {
                    $contentStr .= "[".$key."]:".$value['summary']."\n";
                }
                
                $result = $this->transmitText($object,$contentStr);
            }         
        }
        /*$contentStr = "暂时没找到您想要的内容!";
        $result = $this->transmitText($object,$contentStr);*/
        echo $result;
        //return $result;
    }

    /**
     * 回复文本消息
     * @param  [type] $object  [description]
     * @param  [type] $content [description]
     * @return [type]          [description]
     */
    private function transmitText($object, $content)
    {
        // 空内容直接回复空字符串
        if (! isset ( $content ) || empty ( $content )) {
            $content = "你的问题太奇妙了，容我思考一番[擦汗]";
        }

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    /**
     * 回复图片消息
     * @param  [type] $object     [description]
     * @param  [type] $imageArray [description]
     * @return [type]             [description]
     */
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    /**
     * 回复图文消息
     * @param  [type] $object    [description]
     * @param  [type] $newsArray [description]
     * @return [type]            [description]
     */
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }


		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!isset($this->TOKEN)) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = $this->TOKEN;
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

