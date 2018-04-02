<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<link href="<?=$_BASE_DIR?>css/styleclip.css" rel="stylesheet" type="text/css">
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

<div class="container container0">

<section class="logo-license">
<!-- <div class="half">
  <div class="logo cun1" id="js-zj1">

  </div>
  <p>1寸照</p>
</div>
<div class="half">
  <div class="logo cun2" id="js-zj2">

  </div>
  <p>2寸照</p>
</div> -->
<div class="half" style="display: none">
  <div class="uploader blue">
    <input type="text" class="filename" readonly/>
    <a class="license">
      <img id="img-1" src="images/logo_n.png">
    </a>
    <input id="file0" class="file-3" type="file" size="30" onchange="javascript:setImagePreview();" accept="image/*" multiple />
  </div>
  <div class="yulan">
    <img src="" id="img0" >
    <div class="enter">
      <button class="btn-2 fl">取消</button>
      <button class="btn-3 fr">确定</button>
    </div>
  </div>
   <!-- <p>营业执照</p>-->
</div>
<div class="clear"></div>
</section>

<article class="htmleaf-container">
<div id="clipArea"></div>
<div class="foot-use">
  <div class="uploader1 blue">
    <input type="button" name="file" class="button" value="打开">
    <input id="file" type="file" onchange="javascript:setImagePreview();" accept="image/*" multiple  capture="camera"  />
  </div>
  <button id="clipBtn">截取</button>
</div>
<div id="view"></div>
</article>

<section>
  <div id="js-previewz" class="sfzcard">身份证正面</div>
  <div id="js-previewf" class="sfzcard">身份证反面</div>
  <input type="hidden" value="" name="jsid" id="jsid">
</section>
</div>
<script>window.jQuery || document.write('<script src="<?=$_BASE_DIR?>js/vendor/jquery-2.1.1.min.js"><\/script>')</script>
<script src="<?=$_BASE_DIR?>js/iscroll-zoom.js"></script>
<script src="<?=$_BASE_DIR?>js/hammer.js"></script>
<script src="<?=$_BASE_DIR?>js/jquery.photoClip.js"></script>
<script>
var obUrl = '';
var width = 300;
var height = 188;
//clipArea(width,height);
//document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
//function clipArea(width,height){
  $("#clipArea").photoClip({
    width: width,
    height: height,
    file: "#file",
    view: "#view",
    ok: "#clipBtn",
    loadStart: function() {
      console.log("照片读取中");
    },
    loadComplete: function() {
      console.log("照片读取完成");
    },
    clipFinish: function(dataURL) {
      var jsid = $("#jsid").val();
      console.log(jsid);
      //console.log(dataURL);
      $.ajax({
        type: "POST",
        url: "<?=url('baoming/uploadpicture')?>",
        data: {
          dataURL: dataURL,
          jsid: jsid
        },
        dataType: "json",
        success: function(result) {
          console.log(result);
          if (result.code == 200) {
            //alert('上传成功');
          }else{
            alert("上传失败！");
          }
        },
        error: function() {
          alert("系统异常，请稍后重试");
        }
      });
    }
  });
//}
</script>
<script>
$(function(){
  /*var sfz_path = "<?=$member['sfz_path']?>";
  var filepath = "<?=$_BASE_DIR?>";
  console.log(sfz_path);
  if(sfz_path!=''){
    $("#js-previewz").html('');
    $("#js-previewz").append('<img src="' + filepath + sfz_path + '_z.jpg" align="absmiddle" style="width:95%">');
  }*/

$("#js-zj1").click(function(){
  width = 96;
  height = 134;
  //clipArea(width,height);
  console.log('js-zj1');
  $(".htmleaf-container").show();
  $("#jsid").val('js-zj1');
  
})

$("#js-zj2").click(function(){
  $(".htmleaf-container").show();
  $("#jsid").val('js-zj2');
})

$("#js-previewz").click(function(){
  //clipArea(width,height);
  $(".htmleaf-container").show();
  $("#jsid").val('js-previewz');
})

$("#js-previewf").click(function(){
  $(".htmleaf-container").show();
  $("#jsid").val('js-previewf');
})
var jsid;
$("#clipBtn").click(function(){
    //$("#logox").empty();
    //$('#logox').append('<img src="' + imgsource + '" align="absmiddle" style=" width: 5rem;height: 4.16rem; margin-left: 1.5rem;margin-top: 1.92rem">');
    jsid = $("#jsid").val();
    jsid = jsid ==''?'js-previewz':jsid;
    $("#"+jsid).empty();
    $("#"+jsid).html('');
    $("#"+jsid).append('<img src="' + imgsource + '" align="absmiddle" style="width:95%">');
    $(".htmleaf-container").hide();
  })
});
</script>
<script type="text/javascript">
$(function(){
  jQuery.divselect = function(divselectid,inputselectid) {
    var inputselect = $(inputselectid);
    $(divselectid+" small").click(function(){
      $("#divselect ul").toggle();
      $(".mask").show();
    });
    $(divselectid+" ul li a").click(function(){
      var txt = $(this).text();
      $(divselectid+" small").html(txt);
      var value = $(this).attr("selectid");
      inputselect.val(value);
      $(divselectid+" ul").hide();
      $(".mask").hide();
      $("#divselect small").css("color","#333")
    });
  };
  $.divselect("#divselect","#inputselect");
});
</script>
<script type="text/javascript">
$(function(){
  jQuery.divselectx = function(divselectxid,inputselectxid) {
    var inputselectx = $(inputselectxid);
    $(divselectxid+" small").click(function(){
      $("#divselectx ul").toggle();
      $(".mask").show();
    });
    $(divselectxid+" ul li a").click(function(){
      var txt = $(this).text();
      $(divselectxid+" small").html(txt);
      var value = $(this).attr("selectidx");
      inputselectx.val(value);
      $(divselectxid+" ul").hide();
      $(".mask").hide();
      $("#divselectx small").css("color","#333")
    });
  };
  $.divselectx("#divselectx","#inputselectx");
});
</script>
<script type="text/javascript">
$(function(){
  jQuery.divselecty = function(divselectyid,inputselectyid) {
    var inputselecty = $(inputselectyid);
    $(divselectyid+" small").click(function(){
      $("#divselecty ul").toggle();
      $(".mask").show();
    });
    $(divselectyid+" ul li a").click(function(){
      var txt = $(this).text();
      $(divselectyid+" small").html(txt);
      var value = $(this).attr("selectyid");
      inputselecty.val(value);
      $(divselectyid+" ul").hide();
      $(".mask").hide();
      $("#divselecty small").css("color","#333")
    });
  };
  $.divselecty("#divselecty","#inputselecty");
});
</script>
<script type="text/javascript">
$(function(){
   $(".mask").click(function(){
     $(".mask").hide();
     $(".all").hide();
   })
  $(".right input").blur(function () {
    if ($.trim($(this).val()) == '') {
      $(this).addClass("place").html();
    }
    else {
      $(this).removeClass("place");
    }
  })
});
</script>
<script>
$("#file0").change(function(){
  var objUrl = getObjectURL(this.files[0]) ;
   obUrl = objUrl;
  console.log("objUrl = "+objUrl) ;
  if (objUrl) {
    $("#img0").attr("src", objUrl).show();
  }
  else{
    $("#img0").hide();
  }
}) ;
function qd(){
   var objUrl = getObjectURL(this.files[0]) ;
   obUrl = objUrl;
   console.log("objUrl = "+objUrl) ;
   if (objUrl) {
     $("#img0").attr("src", objUrl).show();
   }
   else{
     $("#img0").hide();
   }
}
function getObjectURL(file) {
  var url = null ;
  if (window.createObjectURL!=undefined) { // basic
    url = window.createObjectURL(file) ;
  } else if (window.URL!=undefined) { // mozilla(firefox)
    url = window.URL.createObjectURL(file) ;
  } else if (window.webkitURL!=undefined) { // webkit or chrome
    url = window.webkitURL.createObjectURL(file) ;
  }
  return url ;
}
</script>
<script type="text/javascript">
var subUrl = "";
$(function (){
  $(".file-3").bind('change',function(){
    subUrl = $(this).val()
    $(".yulan").show();
    $(".file-3").val("");
  });

  $(".file-3").each(function(){
    if($(this).val()==""){
      $(this).parents(".uploader").find(".filename").val("营业执照");
    }
  });
$(".btn-3").click(function(){
$("#img-1").attr("src", obUrl);
$(".yulan").hide();
$(".file-3").parents(".uploader").find(".filename").val(subUrl);
})
  $(".btn-2").click(function(){
    $(".yulan").hide();
  })

});
</script>
<script type="text/javascript">
function setImagePreview() {
  var preview, img_txt, localImag, file_head = document.getElementById("file_head"),
      picture = file_head.value;
  if (!picture.match(/.jpg|.gif|.png|.bmp/i)) return alert("您上传的图片格式不正确，请重新选择！"),
      !1;
  if (preview = document.getElementById("preview"), file_head.files && file_head.files[0]) preview.style.display = "block",
      preview.style.width = "63px",
      preview.style.height = "63px",
      preview.src = window.navigator.userAgent.indexOf("Chrome") >= 1 || window.navigator.userAgent.indexOf("Safari") >= 1 ? window.webkitURL.createObjectURL(file_head.files[0]) : window.URL.createObjectURL(file_head.files[0]);
  else {
    file_head.select(),
        file_head.blur(),
        img_txt = document.selection.createRange().text,
        localImag = document.getElementById("localImag"),
        localImag.style.width = "63px",
        localImag.style.height = "63px";
    try {
      localImag.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)",
          localImag.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = img_txt
    } catch(f) {
      return alert("您上传的图片格式不正确，请重新选择！"),
          !1
    }
    preview.style.display = "none",
        document.selection.empty()
  }
  return document.getElementById("DivUp").style.display = "block",
      !0
}
</script>

<?php $this->_endblock(); ?>