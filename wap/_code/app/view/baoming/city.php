<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<style type="text/css">
#comfirm{
  display: none;
}
#qiandao{
  display: none;
}
.notice{
  color:#ff0000;
}
</style>

<div class="container">


  <div class="qiandao" id="qiandao">  
    <p>
      <button type="button" style="margin-top: 100px;" class="btn btn-primary btn-lg btn-block" id="scancard">获取城市</button>
    </p>
    <!-- <p>
    <button type="button" style="margin-top: 100px;" class="btn btn-primary btn-lg btn-block" id="getlocation">
      查看地址
    </button></p>  -->
  </div>

  <div id="comfirm">
    <div class="form-inline" style="padding: 20px 0px;">
      <label for="exampleInputName2">手机号码</label>
      <input type="text" class="form-control"  name="mobile" id="mobile" placeholder="手机号码">
    </div>
    <p class="notice">请确认是自己的信息，一旦绑定本次培训无法解绑</p>
      <!-- <input type="" name="mobile" id="mobile"> -->
    <p><button type="button" style="margin-top: 100px;" class="btn btn-primary btn-lg btn-block" id="bind">绑定</button></p>
  </div>
</div>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=P3lfvx7c65GD57N0NUwl3aY1"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  

  $(document).ready(function(){


    $("#scancard").click(function(){
        myCity = new BMap.LocalCity();

          myCity.get(function(e){

          //map.setCenter(e.name);

          alert(e.name);

          });
      
    })


    var getbdLocation = function(){
      var geolocation = new BMap.Geolocation();
      geolocation.getCurrentPosition(function(r){
        if(this.getStatus() == BMAP_STATUS_SUCCESS){
          //alert('您的位置：'+r.point.lng+','+r.point.lat);
          success(r.point.lat,r.point.lng);
        }
        else {
          alert('无法获取您的位置');
          $("#scancard").attr('disabled',false);
        }        
      },{enableHighAccuracy: true})
    };



    var error = function () {
      alert("无法获取您的位置");
    };

    var init = function(){
       
    }

    var is_weixin = function () {
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger") {
            return true;
        } else {
            return false;
        }
    }

    var isWeixin = is_weixin();
    if(!isWeixin){
        //alert('weixin');
        $("#mobile").attr('disabled',true);
        $(".notice").text('请使用微信扫一扫打开');
        //$(".weixin-tip").show();
        $("#comfirm").show();
        return;
    }

    init();
    
    //console.log(times);

    /*alert(times);
    var info_hmc = localStorage.getItem('info_hmc');
    var qiandao_date = localStorage.getItem('qiandao_date');
    alert(qiandao_date);
    alert(info_hmc);*/
  })
</script>
<?php $this->_endblock(); ?>