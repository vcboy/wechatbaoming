<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    /*debug: true,*/
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
        'addCard',
        'chooseCard',
        'openCard'
    ]
  });
  wx.ready(function () {
    // 在这里调用 API
        // 12 微信卡券接口
        // 12.1 添加卡券
        $("#bookcard").click(function() {
            //alert('aaa');
            $(".book").attr("disabled", true);
            $(".book").html("预约中....");
            //return;
            var card_id = "<?=$info['card_id']?>";
            //alert(card_id);
            wx.addCard({
              cardList: [
                {
                  cardId: card_id,
                  cardExt: '{"code": "", "openid": "", "nonce_str": "<?php echo $cardSignPackage["nonceStr"];?>", "timestamp": "<?php echo $cardSignPackage["timestamp"];?>", "signature":"<?php echo $cardSignPackage["signature"];?>"}'
                }
              ],
              success: function (res) {
                //alert('已添加卡券：' + JSON.stringify(res.cardList));
                $.ajax({
                    type : "post",
                    method : "post",
                    dataType : "json",
                    data:{"id":<?=$info['id']?>},
                    url : "<?php echo url('lecture/book')?>",
                    success : function(data) {
                        if(data=='0'){
                            $(".book").attr("disabled", false);
                            $(".book").html("预约失败");
                        }else{
                            $(".book").html("预约成功");
                        }
                    },
                    error:function(){
                        //alert(1);
                        $(".book").attr("disabled", false);
                        $(".book").html("我要预约");
                    }               
                });
                alert("卡券领取成功，请去微信卡包我的票卷查看");
              },
              cancel: function (res) {
                //alert(JSON.stringify(res))
                $(".book").attr("disabled", false);
                $(".book").html("我要预约");
              }
            });

            //console.log(id);
        });       
    });
</script>
<div class="container">
	<h1></h1>

    <?if(!empty($info['id'])){//查询成功?>
        <div class="row">
            <div class="col-md-12">
                <div class="thumbnail">
                    <div class="caption">
                        <p>讲座：<?=$info['title']?></p>
                        <p>演讲者：<?=$info['speaker']?></p>
                        <p>讲座时间：<?=date('Y-m-d',$info['datetime'])?></p>
                        <p>讲座地址：<?=$info['address']?></p>
                        <p>详情简介：</p>
                        <p><?=$info['content']?></p>
                        <p><button id="bookcard" type="button" class="btn btn-primary book btn_addcard" data-toggle="button" <?if($status) echo "disabled='disabled'";?>  style="margin:0px auto;display:block;"><?=$text?></button></p>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
    <?}?>

</div>
<script type="text/javascript">
function book(id){
    $(".book").attr("disabled", true);
    $(".book").html("预约中....");
    console.log(id);
    $.ajax({
        type : "post",
        method : "post",
        dataType : "json",
        data:{"id":id},
        url : "<?php echo url('lecture/book')?>",
        success : function(data) {
            if(data=='0'){
                $(".book").attr("disabled", false);
                $(".book").html("预约失败");
            }else{
                $(".book").html("预约成功");
            }
        },
        error:function(){
            //alert(1);
        }               
    });
}
</script>
<?php $this->_endblock(); ?>