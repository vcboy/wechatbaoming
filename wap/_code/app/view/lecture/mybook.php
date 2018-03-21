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
  /*wx.config({
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
  });*/
  /*wx.ready(function () {
    // 在这里调用 API
        // 12 微信卡券接口
        // 12.1 添加卡券
        $(".btn_addcard").click(function() {
            var card_id = $(this).attr('cardid');
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
              },
              cancel: function (res) {
                //alert(JSON.stringify(res))
              }
            });
        });

        
    });*/
</script>
<div class="container">
    <h2></h2>
        <!-- Table -->
        <?foreach ($book as $key => $value) {
            //list-group-item-danger list-group-item-warning
            if($value['datetime']>time()){?>
                <div class="panel panel-default">
                    <table class="table list-group-item-success">
                        <tbody>
                            <tr>
                                <td>讲座：</td><td><?=$value['title']?></td>
                            </tr>
                            <tr>
                                <td>演讲者：</td><td><?=$value['speaker']?></td>
                            </tr>
                            <tr>
                                <td>讲座地址：</td><td><?=$value['address']?></td>
                            </tr>
                            <tr>
                                <td>讲座时间：</td><td><?=date('Y-m-d',$value['datetime'])?></td>
                            </tr>
                            <tr>
                                <td>状态：</td><td>未开始</td>
                            </tr>
                            <!-- <tr>
                                <td colspan="2"><button type="button" class="btn btn-primary  btn_addcard" cardid="<?=$value['card_id']?>">领取卡券</button></td>
                            </tr> -->
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                  <a  class="btn btn-primary  " href="<?echo url('/cancel',array('id'=>$value['id']))?>">取消预约</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?}else{?>
                <div class="panel panel-default">
                    <table class="table list-group-item-danger">
                        <tbody>
                            <tr>
                                <td>讲座：</td><td><?=$value['title']?></td>
                            </tr>
                            <tr>
                                <td>演讲者：</td><td><?=$value['speaker']?></td>
                            </tr>
                            <tr>
                                <td>讲座地址：</td><td><?=$value['address']?></td>
                            </tr>
                            <tr>
                                <td>讲座时间：</td><td><?=date('Y-m-d',$value['datetime'])?></td>
                            </tr>
                            <tr>
                                <td>状态：</td><td>已结束</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?}?>

        <?}?>

        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
    <!-- /.container -->
</div>
<?php $this->_endblock(); ?>
