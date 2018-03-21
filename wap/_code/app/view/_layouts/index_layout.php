<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:wb="http://open.weibo.com/wb">
<head>
<?php
$hit = Hits::find()->getById(1);
$hit->num++;
$hit->save();
?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>麦能网</title>
    <link rel="stylesheet" href="<?=$_BASE_DIR?>css/common.css" type="text/css" />
    <link rel="stylesheet" href="<?=$_BASE_DIR?>css/index.css" type="text/css" />
	<!-- QQ登录meta -->
	<meta property="qc:admins" content="22557261766516506375" />
	<!-- 微博登录meta -->
	<meta property="wb:webmaster" content="390d28746c2dfd7e" />
<script type="text/javascript" src="<?=$_BASE_DIR?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/PictureRotation.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/comx.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>js/cart.js"></script>
<script type="text/javascript" src="<?=$_BASE_DIR?>player/jwplayer.js"></script>
<script>
<?php $url_info = Q::ini('appini/url_info');?>
var url_info = "<?=$url_info?>";
$(function(){
    $(".contimg").hover(
      function () {
          $(this).css({ "background-color":"#B01421","color":"#FFE68C"});
          $(this).find('.money').css({"background":"url(<?=$_BASE_DIR?>images/moneybg1.png) no-repeat","color":"#B01421"});
          $(this).find('.c_name').css({"color":"#FFF"});
          $(this).find('.xingqe').css({"color":"#FFF"});
          $.each($(this).find('.pcimg img'),function(kk){
             $(this).attr('src',$(this).attr('src').replace(/0/g,"1").replace(/2/g,"3"));
          });
      },
      function () {
          $(this).css({ "background-color":"#fff","color":"#666"});
          $(this).find('.money').css({"background":"url(<?=$_BASE_DIR?>images/moneybg0.png) no-repeat","color":"#666"});
          $(this).find('.c_name').css({"color":"#000"});
          $(this).find('.xingqe').css({"color":"#666"});
          $.each($(this).find('.pcimg img'),function(kk){
             $(this).attr('src',$(this).attr('src').replace(/1/g,"0").replace(/3/g,"2"));
          });
      }
    );
});

$(function () {
    $(window).scroll(function(){
        if ($(window).scrollTop()>90){
            $("#back-to-top").css({'top':$(window).scrollTop(),'left':$(window).width()-100});
            $("#back-to-top").fadeIn(1500);
        }else{
            $("#back-to-top").fadeOut(1500);
        }
    });
    $("#back-to-top").click(function(){
        $('body,html').animate({scrollTop:0},1000);
        return false;
    });
});

function searchsub(){
	 if($("#key_info").val()==""){
		 $("#key_info").focus();
		 $("#key_info").attr("placeholder","请输入关键字");
		 }
	 else{
    var info = $("#key_info").val();
    location.href="<?=url('index/search')?>"+'/keyinfo/'+info;
	 }
	 }

function showlinklist(key){
    if(key){
        $("#linklist").show();
    }else{
        $("#linklist").hide();
    }
}

$(document).ready(function(){
  var topMain = $("#menu").height()+150;//是头部的高度加头部与nav导航之间的距离
  var nav = $("#menu_float");
  $(window).scroll(function(){
    if ($(window).scrollTop()>topMain){//如果滚动条顶部的距离大于topMain则就nav导航就添加类.nav_scroll，否则就移除
      nav.show();
    }else{
      nav.hide();
    }
  });
});
</script>
</head>
<body style="position:relative;">
    <div id="back-to-top"><img src="<?=$_BASE_DIR?>images/ajtop.jpg" width="55" height="54" /></div>

    <div class="bodyhead" id="menu_float" style="position:fixed;width:100%;display:none;height:50px;z-index:1100;">
        <div class="htmldiv">
            <div class="fl logo" style="padding: 10px 150px 10px 80px;float:left;">
                <img src="<?=$_BASE_DIR?>images/index1_20_s.png"  alt="">
            </div>
            <div class="head_main_s">
                <a href="<?=url("index")?>" <?php if(empty($tid)&&empty($tab_id)){echo 'class="on"';}?>>首页</a>
                <a href='<?=url("index/typelist",array('tab_id'=>1))?>' <?php if(!empty($tab_id)&&$tab_id==1){echo 'class="on"';}?>>本科类</a>
                <a href='<?=url("index/typelist",array('tab_id'=>2))?>' <?php if(!empty($tab_id)&&$tab_id==2){echo 'class="on"';}?>>专科类</a>
                <a href='<?=url("index/typelist",array('tab_id'=>3))?>' <?php if(!empty($tab_id)&&$tab_id==3){echo 'class="on"';}?>>职业技能</a>
                <a href='<?=url("index/opencourse")?>' <?php if(!empty($tid)&&$tid=="opencourse"){echo 'class="on"';}?>>公开课</a>
            </div>
            <div class="fl logo" style="padding: 10px 20px;float:right;with:200px;position:relative;cursor:pointer;" onclick="javascript:location.href='<?=url('index/cartinfo')?>'">
                <img src="<?=$_BASE_DIR?>images/cart.png"  alt="">
                <?php 
                    $cart = new Cart(); 
                    $cart_num = $cart->getnownum();
                ?>
                    <span style="display:inline-block;color:#000;width:21px;position:absolute;top:0;left:34px;background:url(<?=$_BASE_DIR?>images/cart_num.png)" class="cart_num">&nbsp;</span>
                    <span style="position:absolute;top:0;left:40px;line-height:15px;" id="cart_num_float"><?=empty($cart_num)?0:$cart_num?></span>
            </div>
        </div>
    </div>

    <div class="nav-box-wuping"  style="display:none;">
        <h3>您购物车中的商品:
            <span style="float:right;font-size:14px;background: url(<?=$_BASE_DIR?>images/gotopay.png);width: 62px;height: 25px;">
                <a href="<?=url('index/cartinfo')?>" class="blue" style='color:#B10000;padding-left: 15px;line-height: 25px;'>去结算</a>
            </span>
        </h3>
        <div class="nav-box-color01">
            抱歉没有任何商品!
        </div>
        <div class="nav-heji">
            共<span class="color-red" id="mini_cart_num"> 0 </span>件  
            商品小计<span class="color-red" id="mini_cart_total">0</span>元
        </div>
        <div class="nav-btnbuy"><a href="javascript:void $('.nav-box-wuping').fadeOut();">
            <img src="<?=$_BASE_DIR?>images/nav_btnbuy.png"></a>
        </div>
    </div>


    <div class="bodyhead" >
        <div class="htmldiv" id="menu">
            <div class="fl logo" style="height:40px;"><img src="<?=$_BASE_DIR?>images/index1_20.png" width="202" height="69" alt=""></div>
            <div class="fl">
                <div class="top1 clear">
                    <div><img src="<?=$_BASE_DIR?>images/index1_04.png"></div>
                    <div class="t_con">
                <?php $user = Q::registry('app')->currentUser();
                    //print_r($user);exit;
                    if(empty($user['username'])){   ?>
                        <a href="<?php echo url('user/login')?>">请登录</a>
                        <a href="<?php echo url('user/reg')?>">免费注册</a>
                <? }else{ ?>
                       <span style="display: inline;height: 20px;overflow: hidden;max-width: 130px;float: left;padding-right: 10px;padding-left: 30px;white-space: nowrap;text-overflow: ellipsis;">欢迎，<?php if(!empty($user['name']))echo $user['name'];else echo $user['username'];?></span>
                        <a href="<?php echo url("user/logout")?>">[退出]</a>
                <? } ?>
                        <dl><dd><font color="#000">｜</font></dd></dl>
                        <dl><dd><img src="<?=$_BASE_DIR?>images/index1_12.png"></dd></dl>
                        <dl><dd>免费客服热线：400-800-0370</dd></dl>
                
                        <div class="menu" style="float:right;position:relative;">
                <?php $user = Q::registry('app')->currentUser();
                    if(empty($user['username'])){ ?>
                            <div class="flwpx">&nbsp;</div>
                <? }else{ ?>
                            <!--<a href="http://www.mynep.com/lms" class="decn" style="padding-left: 15px">我的学习中心</a>-->
							<?php if ($user['sims_lev'] == 0) {?>
                            <a href="<?=$url_info?>/lms" class="decn" style="padding-left: 15px">我的学习中心</a>
							<?php } else {?>
                            <a href="<?=$url_info?>/lms" class="decn" style="padding-left: 15px">我的管理中心</a>
							<?php }?>
                            <dl><dd><font color="#000">｜</font></dd></dl> 
                <? } ?>   
                            <dl><dd><img src="<?=$_BASE_DIR?>images/index1_14.png"></dd></dl>
                            <a href="<?=url('index/cartinfo')?>" class="decn">购物车&nbsp;<span style="color:#FFF;" id="cart_num"><?php $cart = new Cart(); echo $cart->getnownum();?></span></a>
                            <dl><dd><font color="#000">｜</font></dd></dl>
                            
                            <a href="#" class="decn" onmouseover="showlinklist(1);" >网站导航<img src="<?=$_BASE_DIR?>images/index1_17.png"></a>
                        
                            <div style="z-index:99;position:absolute;right:0;display:none;width:82px;" id="linklist"  >
                                <span  onmouseout="showlinklist(0);" onmouseover="showlinklist(1);" style="color:#C84B4B ;display:block;float:left; background:url('<?=$_BASE_DIR?>images/link_bg.png');width: 82px;border: 1px solid #BFBFBF;border-bottom:none;">
                                    <span style="margin-left:15px;">网站导航</span>
                                </span>
                                
                                <ul>
                                    <?php
                                        $link = new Links();
                                        $linklist = $link->get_links();
                                        $count=0;
                                        $style_info = "";
                                        foreach ($linklist as $key => $val) {
                                            if(count($linklist)==($count+1)){
                                                $style_info = "border-bottom: 1px solid #BFBFBF;";
                                            }
                                    ?>
                                        <li style="text-align:left;" onmouseout="showlinklist(0);" onmouseover="showlinklist(1);">
                                            <a href="<?=$key?>" class="link_hove" style="<?=$style_info?>border-right: 1px solid #BFBFBF;border-left: 1px solid #BFBFBF;color:#00A3D2;text-decoration:none;background:url('<?=$_BASE_DIR?>images/link_<?=$count?>.png');padding:0;width:82px;height:22px;" target="blank">
                                                <span style="padding-left:24px;"><?=$val?></span>
                                            </a>
                                        </li>
                                    <?php $count++;} ?>
                                </ul>
                            </div> 
                        </div>
                    </div>
                    <div><img src="<?=$_BASE_DIR?>images/index1_10.png"></div>
                </div>
                <div class="top2 clear">
                    <div class="fr search">
                        <ul>
                            <li><img src="<?=$_BASE_DIR?>images/index1_32.png" width="30" height="32" alt=""></li>
                            <li><input id="key_info" name="key_info" type="text" value="<?=(empty($key_info)?"":$key_info)?>"/></li>
                            <li><img src="<?=$_BASE_DIR?>images/index1_36.png" width="52" height="32" alt="" style='cursor:pointer;' onclick='searchsub();'></li>
                        </ul>
                    </div>
                    <div class="head_main">
                        <a href="<?=url("index")?>" <?php if(empty($tid)&&empty($tab_id)){echo 'class="on"';}?>>首页</a>
                        <a href='<?=url("index/typelist",array('tab_id'=>1))?>' <?php if(!empty($tab_id)&&$tab_id==1){echo 'class="on"';}?>>本科类</a>
                        <a href='<?=url("index/typelist",array('tab_id'=>2))?>' <?php if(!empty($tab_id)&&$tab_id==2){echo 'class="on"';}?>>专科类</a>
                        <a href='<?=url("index/typelist",array('tab_id'=>3))?>' <?php if(!empty($tab_id)&&$tab_id==3){echo 'class="on"';}?>>职业技能</a>
                        <a href='<?=url("index/opencourse")?>' <?php if(!empty($tid)&&$tid=="opencourse"){echo 'class="on"';}?>>公开课</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->_block('contents');?>

    <?$this->_endblock();?>

<div class="clearp"></div>
<!--底部-->
<div class="foot">
    <div class="footxx"></div>
    <div class="htmldiv">
        <div class="footcont">
            <div class="fo_1">
                <ul>
                <li>免费客服热线</li>
                <li><span>400 800 0370</span></li>
                <li style="cursor: pointer;"><a>立即关注</a><div><img src="<?=$_BASE_DIR?>images/index1_103.png" width="80" height="24" /></div></li>
                <li style="cursor: pointer;"><a>立即关注</a><div><img src="<?=$_BASE_DIR?>images/index1_106.png" width="80" height="21" /></div></li>
                </ul>
            </div>
            
            <div class="fo_2">
                <ul>
                <li class="fzb">新手指南</li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6021))?>">如何注册</a></li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6028))?>">如何登录</a></li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6029))?>">订购方式</a></li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6033))?>">购课流程</a></li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6035))?>">如何重置密码</a></li>
                </ul>
            </div>
            <div class="fo_2">
                <ul>
                <li class="fzb">售后服务</li>
                <li><a href="#">退货政策</a></li>
                <li><a href="#">退款时限</a></li>
                <li><a href="#">先行赔付</a></li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6032))?>">退换货说明</a></li>
                <li><a href="#">服务时效承诺</a></li>
                </ul>
            </div>
            <div class="fo_2">
                <ul>
                <li class="fzb">支付方式</li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6030))?>">在线支付</a></li>
                <li><a href="#">会员卡支付</a></li>
                </ul>
            </div>
            <div class="fo_2">
                <ul>
                <li class="fzb">产品&amp;服务</li>
                <li><a href="#">面向人个</a></li>
                <li><a href="#">面向企业</a></li>
                <li><a href="#">客户端下载</a></li>
                </ul>
            </div>
            <div class="fo_2">
                <ul>
                <li class="fzb">帮助说明</li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6037))?>">常见问题</a></li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6034))?>">联系客服</a></li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6031))?>">用户协议</a></li>
                <li><a href="<?=url('index/newsinfo',array('id'=>6036))?>">版权说明</a></li>
                </ul>
            </div>
            <div class="fo_3">
                <ul>
                <li>麦能网微信公众号</li>
                <li><img src="<?=$_BASE_DIR?>images/index1_100.png" width="84" height="85" /></li>
                <li class="clfz">随手扫一扫,课程全知晓</li>
                </ul>
            </div>
        </div>
        <!---->
        <div class="clear foothtmlxx"></div>
        <div class="Copyright">
            <div><a href="<?=url('index/index')?>">麦能首页</a> | 
                <a href="#">关于我们</a> | 
                <a href="#">合作专区</a> | 
                <a href="#">问题反馈</a> | 
                <a href="<?=url('index/newsinfo',array('id'=>6034))?>">联系我们</a> | 
                <a href="<?=url('index/newsinfo',array('id'=>6037))?>">帮助中心</a><div> 
            <div>客服电话:400-800-0370&nbsp;&nbsp;(免长途话费)&nbsp;&nbsp;Copyright © 2012-2014 www.mynep.com. All rights reserved. </div>
            <div>浙江吉博教育科技有限公司 浙ICP备11031232号-15 &nbsp; 访问量：<?php echo $hit->num;?></div>
        </div>
    </div>
</div>
<div class="clear"></div>


</body>
</html>
