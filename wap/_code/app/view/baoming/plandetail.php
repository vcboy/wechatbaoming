<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container" style="padding: 0px;">

        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">
            <div class="col-md-8padding0">
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="<?=$_BASE_DIR.'../backend/'.$plandata['img']?>" width="100%"  />
                    </div>
                </div>
            </div>
            <div class="col-md-8 dt_title">
                <?=$plandata['name']?>
            </div>
            <div>
                <div class="detial_field top_line">
                    <b></b>
                    <div class="detail_Time_n"><span class="glyphicon glyphicon-book iconcolor"></span><span class="padding10">课程: <?=$plandata['lession']['name']?></span></div>
                </div>
                <div class="detial_field">
                    <b></b>
                    <div class="detail_Time_n"><span class="glyphicon glyphicon-user iconcolor"></span><span class="padding10">老师: <?=$plandata['teacher']['name']?></span></div>
                </div>
                <div class="detial_field" style="border-bottom: 0px;">
                    <b></b>
                    <div class="detail_Time_n"><span class="glyphicon glyphicon-credit-card iconcolor"></span><span class="padding10">费用:<span class="score2"> ¥ <?=$plandata['fee']?></span></span></div>
                </div>
            </div>
            
            <div class="dt_content" >
                <?=$plandata['description']?>
            </div>
        </div>
    <!-- /.container -->
</div>
<div class="dt_join_bar"  data-toggle="modal" data-target="#myModal">我要报名</div>
<style type="text/css">
a:visited { 
    text-decoration: none; 
} 
</style>
<?php $this->_endblock(); ?>