<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<script src="<?=$_BASE_DIR?>/js/vendor/jquery.infinitescroll.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
  //翻页js  start
  var page = 1;
  window.url = "<?php echo url('/gethd');?>"+"/page/"+page;
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
      window.url = "<?php echo url('/gethd');?>"+"/page/"+page;
    });
    // end
})
</script>
<style type="text/css">
.wechat_tab{
    float: left;
    text-decoration: none;
    color: #fff;
    /*background-color:#fff;*/
    width: 20%;
    text-align: center;
    line-height: 34px;
    border-radius: 4px;
}

.wechat_empty{
    text-align: center;
}
.clear{
    height: 10px;
    clear: both;
}
.active{
    background-color: #6ca1c6;
    color: #fff;
}
.wechat_top{
    padding: 0px;
}
.wechat_top_tab{
    display: none;
}
.newblock{
    padding: 10px;
    margin: 17px;
    box-shadow: 8px 6px 12px #8eaad5;
    background-color: #e7f1fa;
    border-radius: 10px;

}
.newblock img{
    /* width: 80%; */
}
</style>
<div class="container wechat_top">
    <div class="clear"></div>
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <!-- Project Two -->
    <div id="base_content">
        <?foreach ($list as $key => $value) {?>
            <div class="row newblock">
                <?php
                if($value['pic']){
                ?>
                <div class="col-md-7">
                    <a href="<?=url('news/detail',array('id'=>$value['id']))?>">
                        <img class="img-responsive img-hover" src="/weixin/library/backend/web/<?=$value['pic']?>" alt="">
                    </a>
                </div>
                <?php
                }
                ?>
                <div class="col-md-5">
                    <!-- <h3>Project Two</h3> -->
                    <h4><?=$value['title']?></h4>
                    <!-- <p style="height: 100px;overflow: hidden;" class="newscontent"><?=$value['content']?></p> -->
                    <a class="btn btn-primary" style="float: right;" href="<?=url('news/detail',array('id'=>$value['id']))?>">查看内容</a>
                </div>
            </div>
            <hr>
        <?}?>
    </div>
        <?if(count($list)==0){?>
            <div class="wechat_empty">
                此分类暂时无数据，看看别的分类吧
            </div>
        <?}?>
        <!-- Project Three -->
        <!--div class="row">
            <div class="col-md-7">
                <a href="portfolio-item.html">
                    <img class="img-responsive img-hover" src="http://placehold.it/700x300" alt="">
                </a>
            </div>
            <div class="col-md-5">
                <h3>Project Three</h3>
                <h4>Subheading</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis, temporibus, dolores, at, praesentium ut unde repudiandae voluptatum sit ab debitis suscipit fugiat natus velit excepturi amet commodi deleniti alias possimus!</p>
                <a class="btn btn-primary" href="portfolio-item.html">View Project</i></a>
            </div>
        </div-->
        <!-- /.row -->
        <!-- /.row -->
    <!-- /.container -->
</div>
<div style="display:none"><a id="next" href="http://wechatbaoming/wap/index.php/news/index/id/4/page/1">next page?</a></div>
<style type="text/css">
a:visited { 
    text-decoration: none; 
} 
</style>
<?php $this->_endblock(); ?>