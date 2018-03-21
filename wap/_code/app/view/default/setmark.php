<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<style type="text/css"> 
*{margin:0;padding:0;list-style-type:none;}
body{color:#666;font:12px/1.5 Arial;}
/* star */
.star{position:relative;margin:20px auto;height:24px;}
.star ul,.star span{float:left;display:inline;height:19px;line-height:19px;}
.star ul{margin:0 10px;}
.star li{float:left;width:24px;cursor:pointer;text-indent:-9999px;background:url(<?=$_BASE_DIR?>images/star.png) no-repeat;}
.star strong{color:#f60;padding-left:0px;}
.star li.on{background-position:0 -28px;}
.star p{position:absolute;top:20px;width:159px;height:60px;display:none;background:url(<?=$_BASE_DIR?>images/icon.gif) no-repeat;padding:7px 10px 0;}
.star p em{color:#f60;display:block;font-style:normal;}
</style>

<script type="text/javascript"> 
window.onload = function (){
  var oStar = document.getElementById("star");
  setmark(oStar,'course_score');

  var tStar = document.getElementById("tstar");
  setmark(tStar,'teacher_score');
  
  //评分处理
  function fnPoint(iArg,iStar,aLi){
    //分数赋值
    iScore = iArg || iStar;
    for (i = 0; i < aLi.length; i++) aLi[i].className = i < iScore ? "on" : ""; 
  }

  function setmark(oStar,inputid){
    //var oStar = document.getElementById("star");
    var aLi = oStar.getElementsByTagName("li");
    var oUl = oStar.getElementsByTagName("ul")[0];
    var oSpan = oStar.getElementsByTagName("span")[1];
    var oP = oStar.getElementsByTagName("p")[0];
    var i = iScore = iStar = 0;
    var aMsg = [
          "很不满意|差得太离谱，与宣传的严重不符，非常不满",
          "不满意|部分有破损，与宣传的不符，不满意",
          "一般|质量一般，没有宣传的那么好",
          "满意|质量不错，与宣传的基本一致，还是挺满意的",
          "非常满意|质量非常好，与宣传的完全一致，非常满意"
          ]
    
    for (i = 1; i <= aLi.length; i++){
      aLi[i - 1].index = i;
      
      //鼠标移过显示分数
      aLi[i - 1].onmouseover = function (){
        fnPoint(this.index,iStar,aLi);
        //浮动层显示
        oP.style.display = "none";
        //计算浮动层位置
        oP.style.left = oUl.offsetLeft + this.index * this.offsetWidth - 104 + "px";
        //匹配浮动层文字内容
        oP.innerHTML = "<em><b>" + this.index + "</b> 分 " + aMsg[this.index - 1].match(/(.+)\|/)[1] + "</em>" + aMsg[this.index - 1].match(/\|(.+)/)[1]
      };
      
      //鼠标离开后恢复上次评分
      aLi[i - 1].onmouseout = function (){
        fnPoint();
        //关闭浮动层
        oP.style.display = "none"
      };
      
      //点击后进行评分处理
      aLi[i - 1].onclick = function (){
        iStar = this.index;
        oP.style.display = "none";
        oSpan.innerHTML = "<strong>" + (this.index) + " 分</strong> (" + aMsg[this.index - 1].match(/\|(.+)/)[1] + ")";
        $("#"+inputid).val(this.index);
      }
    }
  }
  
};

function check(){
  var course_score = $("#course_score").val();
  var teacher_score = $("#teacher_score").val();
  if(course_score == 0 || teacher_score == 0){
    alert('请给课程和老师评分');
    return false;
  }else{
    $("#setmarkform").submit();
    alert('感谢您的评分');
  }
}





function setscore(){
  
  var teacher_score = <?=$markarr['teacher_score']?>;
  var course_score = <?=$markarr['course_score']?>;
  if(teacher_score != 0 && course_score != 0){
    //var oStar = document.getElementById("star");
    //var aLi = oStar.getElementsByTagName("li");
    //console.log(aLi);
      $('#star li').each(function(index){
        console.log(index);
        if(index < course_score){
          $(this).addClass('on');
        }
      })

      $('#tstar li').each(function(index){
        console.log(index);
        if(index < teacher_score){
          $(this).addClass('on');
        }
      })
  }
}

$(document).ready(function(){
  setscore();
})
</script>
  <div class="container">
    <form class="form-signin" method="post" enctype="application/x-www-form-urlencoded" action="" id="setmarkform">     
      <div id="star" class="star">
        <span>课程评分</span>
        <ul id="cul">
          <li><a href="javascript:;">1</a></li>
          <li><a href="javascript:;">2</a></li>
          <li><a href="javascript:;">3</a></li>
          <li><a href="javascript:;">4</a></li>
          <li><a href="javascript:;">5</a></li>
        </ul>
        <span></span>
        <p></p>
      </div><!--star end-->

      <div id="tstar" class="star">
        <span>教师评分</span>
        <ul>
          <li><a href="javascript:;">1</a></li>
          <li><a href="javascript:;">2</a></li>
          <li><a href="javascript:;">3</a></li>
          <li><a href="javascript:;">4</a></li>
          <li><a href="javascript:;">5</a></li>
        </ul>
        <span></span>
        <p></p>
      </div><!--star end-->

      <input type="hidden" name="course_score" value="0" id="course_score">
      <input type="hidden" name="teacher_score" value="0" id="teacher_score">
      <!--text文本框-->
      <div class="control-group form-group">
          <div class="controls">
              <label>评论:</label>
              <textarea rows="10" cols="100" class="form-control" id="message" name="message" maxlength="999" style="resize:none"><?=$markarr['message']?></textarea>
          </div>
      </div>
      <div id="success"></div>
      <?php
      if($markarr['teacher_score']==0){
      ?>
      <button class="btn btn-primary" onclick="return check();">提交</button>
      <?php
      }
      ?>
    </form>
  </div> <!-- /container -->
<?php $this->_endblock(); ?>