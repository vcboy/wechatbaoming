<?$this->_extends("_layouts/main_layout");?>
<?$this->_block("contents");?>
<script src="<?=$_BASE_DIR?>/js/vendor/jquery.infinitescroll.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
  //翻页js  start
  var page = 1;
  window.url = "<?php echo url('/getnewslist');?>"+"/page/"+page;
  $('#base_content').infinitescroll({
    navSelector   : "#next:last",
    nextSelector  : "a#next:last",
    itemSelector  : "#base_content li",
    debug     : true,
    dataType    : 'html',
    maxPage         : 20,
    path: function(page) {
      return window.url;
    },
    loading:{
      msgText: "加载中...",
      finishedMsg: '没有新数据了...'
      //selector: '.loading' // 显示loading信息的div
    }
    }, function(newElements, data, url){    
      page++;
      window.url = "<?php echo url('/getnewslist');?>"+"/page/"+page;
    });
    // end
})
</script>
<div id="clist">
  <ul id="base_content" class="p_list">
    <?php
    foreach ($news as $key => $value) {
      echo "<li><a href='".url('/show',array('id'=>$value['id']))."'>".$value['title']."</a></li>";
    }
    ?>
  </ul>
</div>
<div style="display:none"><a id="next" href="http://sxy/wap/default/getcourse/page/1">next page?</a></div>
<?php $this->_endblock(); ?>