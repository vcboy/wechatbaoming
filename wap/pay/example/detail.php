<?php 

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//获取后台数据
$web_imgurl = 'http://www.mynep.com/sims';
$con = mysql_connect("127.0.0.1","mynep_com","mntaEYUS6qxEG6M4db");
//$con = mysql_connect("10.82.97.42","panzhiwei","6d8S2JundzcPcjjCdb");
if (!$con)
{
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("mynep_com_2016_db", $con);
$id = $_REQUEST['id'];//产品id
$fid = $_REQUEST['fid'];//分销商id
$uid = intval($_REQUEST['uid']);
$again = intval($_REQUEST['again']);
$data = array();

if(!empty($id)){
    $result = mysql_query("SELECT * FROM lc_product WHERE id=".$id);
    $data = mysql_fetch_row($result);  
    $price = intval($data[4] * 100);
    $pimg = !empty($data['13'])?($web_imgurl.$data['13']):($web_imgurl.$data['6']);
    if($data[9]){
        $pcontent = $data[9];
        $pos = strpos($pcontent, '/mynep/sims/ckeditor/ckfinder/');
        if($pos !== false){
            $pcontent = str_replace('/mynep/sims/ckeditor/ckfinder/', 'http://www.mynep.com/mynep/sims/ckeditor/ckfinder/', $pcontent);
        }
    }

    $now = time();
    $mnow = microtime();
    $rcode = substr(date('Y',$now),2,2).date('md',$now).sprintf('%04d',$uid).date('His',$now).substr($mnow, 2, 2);//年限
}else{
    header("Location: http://wx.mynep.com/");
    return;
}
    
if(!empty($id)&&!empty($uid)){
    
    //var_dump($data);
    
    //$pcontent = $data[9]
    //$price = 1;
    //生成订单号
    if($openId){
        
        //生成订单
        $order_sql = "INSERT INTO sms_order (order_no,price,order_time,state,user_id,paymethod,order_type,fid) 
                    VALUES (".$rcode.",".$data[4].",".$now.",'0',".$uid.",'wx','1',".$fid.")";
        mysql_query($order_sql);
        $order_id = mysql_insert_id();
        //订单id
        /*$id_sql = "SELECT @@IDENTITY";
        $id_result = mysql_query($id_sql);
        while($row = mysql_fetch_array($id_result)){
            //获取生成订单的id
            $order_id = $row['@@IDENTITY'];
        }*/
        //生成订单详细
        $detail_insert = "INSERT INTO sms_order_detail (id,order_id,order_no,courseid,product_id,price) 
        VALUES ('',".$order_id.",".$rcode.",'',".$id.",".$data[4].")";
        mysql_query($detail_insert);
        mysql_close($con);
    }
}

//初始化日志
/*$logHandler= new CLogFileHandler("pay/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}*/

//①、获取用户openid


//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($data[1]);
$input->SetAttach("test");
//$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetOut_trade_no($rcode);
$input->SetTotal_fee($price);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://wx.mynep.com/mynepwap/pay/example/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <!-- Bootstrap Core CSS -->
        <link href="http://wx.mynep.com/dealer/css/bootstrap.min.css" rel="stylesheet">
        <!-- build:css -->
        <link rel="stylesheet" href="http://wx.mynep.com/dealer/css/style.min.css">   
        <!-- endbuild -->  
        <!-- build:cssapp -->
        <link rel="stylesheet" href="http://wx.mynep.com/dealer/css/app.min.css">
        <!-- endbuild --> 
        <!-- svg font -->
        <link rel="stylesheet" href="http://wx.mynep.com/dealer/css/job.css">
        
        <!-- jQuery -->
        <script src="http://wx.mynep.com/dealer/js/vendor/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="http://wx.mynep.com/dealer/js/vendor/bootstrap.min.js"></script>


        <script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $jsApiParameters; ?>,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                //alert(res.err_msg);
                if(res.err_msg == 'get_brand_wcpay_request:ok'){
                    alert('支付成功!请去设置你的收货地址');
                    window.location.href = "http://wx.mynep.com/mynepwap/#/address2";
                }else{
                    alert('支付失败!');
                }
            }
        );
    }

    function callpay()
    {
        //alert('aaa');
        var idst = localStorage.idst?localStorage.idst:0;
        if(idst == 0){
            var url = "http://wx.mynep.com/mynepwap/#/userlogin/<?=$id?>/<?=$fid?>";
            window.location.href = url;
            return;
        }
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }
    </script>
    <script type="text/javascript">
    //获取共享地址
    function editAddress()
    {
        WeixinJSBridge.invoke(
            'editAddress',
            <?php echo $editAddress; ?>,
            function(res){
                var value1 = res.proviceFirstStageName;
                var value2 = res.addressCitySecondStageName;
                var value3 = res.addressCountiesThirdStageName;
                var value4 = res.addressDetailInfo;
                var tel = res.telNumber;
                
                alert(value1 + value2 + value3 + value4 + ":" + tel);
            }
        );
    }
    
    /*window.onload = function(){
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', editAddress, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', editAddress); 
                document.attachEvent('onWeixinJSBridgeReady', editAddress);
            }
        }else{
            editAddress();
        }
    };*/
    
    </script>
  
    </head>
    <body ng-app="mynepwap" class="ng-scope" youdao="bind">      
        <!-- uiView: nav --><div ui-view="nav" class="ng-scope"><nav class="navbar navbar-inverse navbar-fixed-top ng-scope" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header1">      
            <a class="navbar-brand job-back" onclick="goback();"><span class="icon-uniE612"></span></a>
            <div class="jobtitle ng-binding">产品信息</div>
        </div>

    </div>
    <!-- /.container -->
</nav>
<script type="text/javascript" class="ng-scope">
    var goback = function(){
        window.location.href="http://wx.mynep.com/dealer/#/mydealer/<?=$fid?>/1";
    }

   /* $(function(){
        //getBuyInfo();
    })
    function getBuyInfo () {
        // body...
        var Url = 'http://api.mynep.com/frontend/web/index.php/';
        //var Url: 'http://10.82.97.236/mynepappv2/api/frontend/web/index.php/',
        $.ajax({  
            url:Url+"dealerproduct/getbuyinfo",  
            dataType:'jsonp',  
            data:{'pid':<?=$_REQUEST['id']?>},  
            jsonp:'callback',  
            success:function(result) {  
                var data = result.responseData;
                $('.detail').append("<img src='"+data.img+"'>");
                $('.detail_title').html(data.name);
                $('.detail_price span').html('￥'+data.price);
                $('.detail_text').html(data.description);
                //$('.detail_bottom').html("<div class='bottom_right' style='width: 100%'' click='toJoinCourse("+data.id+")'>购买</div>");
            },  
            timeout:3000  
        });
    }*/
</script></div>
        <div class="container">       
            <!-- uiView:  --><div ui-view="" class="container ng-scope"><div class="detail ng-scope" style="padding-top: 50px;">
    <img src="<?=$pimg?>">
</div>
<div class="detail_title ng-binding ng-scope"><?=$data[1]?></div>
<div class="detail_price ng-scope">销售价格:<span class="ng-binding">￥<?=$data[4]?></span></div>

<div class="detail_bg ng-scope">
    <div class="detail_intro">课程详情</div>
    <div class="detail_text ng-binding" ><?=$pcontent?></div>
</div>
<div class="detail_bottom ng-scope">
    <div class="bottom_right" style="width: 100%" onclick="callpay()">购买</div>
</div></div>
        </div>       
    
<script type="text/javascript">
    $(document).ready(function(){
        var idst = localStorage.idst?localStorage.idst:0;
        var again = <?=$again?>;
        if(idst != 0 && again == 1){
            callpay();
        }       
    })
</script>
</body>
</html>
<script type="text/javascript">
    var $body = $('body');
document.title = '麦能网';
var $iframe = $('<iframe src=""></iframe>');
$iframe.on('load',function() {
  setTimeout(function() {
      $iframe.off('load').remove();
  }, 0);
}).appendTo($body);
</script>