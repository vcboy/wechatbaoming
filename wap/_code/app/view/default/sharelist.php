<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<script src="<?=$_BASE_DIR?>/js/vendor/jquery.infinitescroll.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
  //翻页js  start
  var page = 1;
  window.url = "<?php echo url('/getmoreshow');?>"+"/page/"+page;
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
      window.url = "<?php echo url('/getmoreshow');?>"+"/page/"+page;
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
                <h4 class="page-header1">我的活动 </h4>
            </div> -->
            <div class="col-md-4 baseline"  id="base_content">
            <? foreach ($list as $i=>$row) { ?>
            <div class=" newblock">
                <a href="<?=url('baoming/hddetail',array('id'=>$row['id'],'tabletype'=>$row['tabletype']))?>" style=" padding: 0px 10px;">
                <div class="media">
                    <div class="pull-left">
                        <img src="<?=$_BASE_DIR.'../backend/'.$row['img']?>" width="120px" height="65px" />
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading"><?=$row['name']?></h4>
                        <p><?=$row['enddate']?></p>
                    </div>
                </div>
                </a>                         
            </div>
            <div class="divbase">

                <!-- <div class="score">成绩：<span>33</span> 分</div>-->
                <!-- <div>
                    <a href="<?=url('default/setmark')?>"><div class="btn btn-primary ">课程评分</div></a>
                </div> --> 

                <div style="float: right;"><a href="<?=url('default/qrcode',array('id'=>$row['id'],'tabletype'=>$row['tabletype']))?>"><button class="btn btn-primary">分享活动</button></a></div> 
       
                <div class="score"><span style="font-size: 14px;font-weight: normal;">活动进行中</span></div>

            </div>
            <?php } ?>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <div style="display:none"><a id="next" href="http://weixin/wap/index.php/news/index/id/4/page/1">next page?</a></div>
    <!-- /.container -->


<?php $this->_endblock(); ?>
