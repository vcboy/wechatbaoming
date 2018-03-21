<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<?php 
ini_set('date.timezone','Asia/Shanghai');
require_once "{$root_dir}/pay/lib/WxPay.Api.php";
require_once "{$root_dir}/pay/example/WxPay.JsApiPay.php";
require_once "{$root_dir}/pay/example/log.php";

//初始化日志
$logHandler= new CLogFileHandler("{$root_dir}/pay/logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("test");
$input->SetAttach("test");
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee("1");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);
?>
<div class="container">
    <h2></h2>
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <?
        $totalpay = 0;
        foreach ($list as $key => $value) {
            $totalpay += $value['pay'];
        ?>
            <a class="row" href="<?echo url('/info',array('id'=>$value['id']))?>">
                <div class="col-md-12">
                    <div class="thumbnail">
                        <div class="caption">
                            <p>书名：<?=$value['name']?></p>
                            <p>借阅截止日期：<?=$value['enddate']?></p>
                            <p>过期天数：<?=$value['gqdays']?></p>
                            <p>需支付金额：<?=$value['pay']?> 元</p>
                        </div>
                    </div>
                </div>
            <!-- /.row -->
            </a>
        <?}?>

        <div>总共需支付：<?=$totalpay?> 元</div>
        <button type="submit" class="btn btn-primary btn-block btn-lg"  onclick="callpay()" >立即支付</button>
    <!-- /.container -->
</div>
<style type="text/css">
a:visited { 
    text-decoration: none; 
} 
a:hover { 
    text-decoration: none; 
} 
</style>
<script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $jsApiParameters; ?>,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                alert(res.err_code+res.err_desc+res.err_msg);
            }
        );
    }

    function callpay()
    {
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
    
    window.onload = function(){
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
    };
    
</script>
<?php $this->_endblock(); ?>
