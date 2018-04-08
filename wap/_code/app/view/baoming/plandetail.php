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

                <?php
                if($plandata['tabletype'] == 2){
                ?>
                <div class="detial_field top_line">
                    <b></b>
                    <div class="detail_Time_n"><span class="glyphicon glyphicon-home iconcolor"></span><span class="padding10">申报单位: <?=$plandata['company']?></span></div>
                </div>
                <div class="detial_field">
                    <b></b>
                    <div class="detail_Time_n"><span class="glyphicon glyphicon-th iconcolor"></span><span class="padding10">报考方向: <?=$plandata['bkfx']?></span></div>
                </div>
                <div class="detial_field">
                    <b></b>
                    <div class="detail_Time_n"><span class="glyphicon glyphicon-bookmark iconcolor"></span><span class="padding10">报考证书: <?=$plandata['bkzs']?></span></div>
                </div>
                <?php
                }
                ?>
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
                    <div class="detail_Time_n"><span class="glyphicon glyphicon-yen iconcolor"></span><span class="padding10">费用:<span class="score2"> ¥ <?=$plandata['fee']?></span></span></div>
                </div>
            </div>
            
            <div class="dt_content" >
                <?=$plandata['description']?>
            </div>
        </div>
    <!-- /.container -->
</div>
<div class="clickable"  data-toggle="modal" data-target="#myModal">我要报名</div>
<style type="text/css">
a:visited { 
    text-decoration: none; 
} 
</style>
<!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <form action="<?=url('baoming/signup',array('id'=>$plandata['id'],'tabletype'=>$plandata['tabletype']))?>" method="post">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">报名</h4>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="recipient-name" class="control-label">姓名:</label>
                <input type="text" class="form-control" id="name" name="name" required autofocus>
              </div>
              <div class="form-group">
                <label for="message-text" class="control-label">手机:</label>
                <input type="number" class="form-control" id="mobile" name="mobile" required>
                <input type="hidden" name="planid" value="<?=$plandata['id']?>">
                <input type="hidden" name="tabletype" value="<?=$plandata['tabletype']?>">
                <input type="hidden" name="userid" value="<?=$userid?>">
              </div>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button type="submit" class="btn btn-primary" >下一步</button>
          </div>
        </div>
        </form>
      </div>
    </div>
    <script type="text/javascript">
        $('document').ready(function(){
            
        })
    </script>
<?php $this->_endblock(); ?>