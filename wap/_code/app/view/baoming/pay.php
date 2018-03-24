<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once Q::ini('app_config/PAY_DIR')."../lib/WxPay.Api.php";
require_once Q::ini('app_config/PAY_DIR')."WxPay.JsApiPay.php";
require_once Q::ini('app_config/PAY_DIR').'log.php';

//初始化日志
$logHandler= new CLogFileHandler(Q::ini('app_config/PAY_DIR')."../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}

//数据设置
$price = intval($sdata['fee'] * 100);
$now = time();
$mnow = microtime();
//$rcode = substr(date('Y',$now),2,2).date('md',$now).date('His',$now).substr($mnow, 2, 2);//年限

//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($sdata['name']);
$input->SetAttach("吉博");
$input->SetOut_trade_no($trade_no);
$input->SetTotal_fee($price);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("培训费");
$input->SetNotify_url("http://wap.vstp.com.cn/wap/index.php/baoming/callback");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
echo '<font color="#f00"><b>统一下单支付单信息'.$trade_no.'</b></font><br/>';
printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();
?>

  <script type="text/javascript">
  //调用微信JS api 支付
  function jsApiCall()
  {
    WeixinJSBridge.invoke(
      'getBrandWCPayRequest',
      <?php echo $jsApiParameters; ?>,
      function(res){
        if(res.err_msg == "get_brand_wcpay_request:ok" ) {
          alert('支付成功');
          var tourl = "/wap/index.php/default/myscore";
          window.location.href=tourl;
        }
        if(res.err_msg == "get_brand_wcpay_request:cancel" ) {
          alert('取消支付');
          //var tourl = "/wechatbaoming/wap/index.php/baoming/pay/id/"+plan_id;
          //window.location.href=tourl;
        }

        //WeixinJSBridge.log(res.err_msg);
        //alert(res.err_code+res.err_desc+res.err_msg);
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
  </script>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h3><?=$sdata['name']?></h3>
                <div id="pay">
                    <div id="success"><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>报名信息已经提交,您的用户名是身份证号,密码为身份证后6位. </strong></div></div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>费用合计:</label>
                            <span class="score2">¥<?=$sdata['fee']?></span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="callpay()">马上支付</button>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>

<?php $this->_endblock(); ?>