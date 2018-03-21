<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<script src="<?=$_BASE_DIR?>/js/vendor/jquery.infinitescroll.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
  //翻页js  start
  var page = 1;
  window.url = "<?php echo url('/getmore');?>"+"/page/"+page;
  $('#base_content').infinitescroll({
    navSelector   : "#next:last",
    nextSelector  : "a#next:last",
    itemSelector  : "#base_content div.row",
    debug     : true,
    dataType    : 'html',
    maxPage         : 5,
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
      window.url = "<?php echo url('/getmore');?>"+"/page/"+page;
    });
    // end
})
</script>

    <!-- Page Content -->
    <div class="container container0">
        <!-- Service List -->
        <!-- The circle icons use Font Awesome's stacked icon classes. For more information, visit http://fontawesome.io/examples/ -->
        <div class="row">
            <!-- <div class="col-lg-12">
                <h2 class="page-header">活动列表 </h2>
            </div> -->
            <div class="col-md-4 baseline"  id="base_content">
            <? foreach ($list as $i=>$row) { ?>
            <div class="line"></div>
            <div class="newblock1">
            <a href="<?=url('/signup',array('id'=>$row['id'],'tabletype'=>$row['tabletype']))?>" >
                <div class="media">
                    <div class="pull-left planlistl">
                        <img src="<?=$_BASE_DIR.'../backend/'.$row['img']?>" width="100%" height="85px" />
                    </div>
                    <div class="media-body planlistr">
                        <div class="media-heading"><?=$row['name']?></div>
                        <div><?=$row['enddate']?></div>
                        <div class="score1">¥<span><?=$row['fee']?></span></div>
                    </div>
                </div>
            </a>
            </div>
            
            <?php } ?>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <div style="display:none"><a id="next" href="http://weixin/wap/index.php/news/index/id/4/page/1">next page?</a></div>
    <!-- /.container -->


<?php $this->_endblock(); ?>
