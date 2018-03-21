<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
	<h1></h1>
    <!-- /.container -->
        <!-- Table -->
                <?if(!empty($success)){//查询成功?>
                    <div class="wechat_lbg" style="height: 100%; padding-bottom: 15px;margin-bottom: 21px;">
                        <div class="lbg_con">续借成功
                            <?php
                            foreach ($success as $key => $value) {
                                echo "<span>条码：".$value."</span>";
                            }
                            ?>                       
                        </div>
                        <div style="clear: both"></div>
                    </div>
                <?}else{?>
                    <div class="wechat_lbg"  style="height: 100%; padding-bottom: 15px; margin-bottom: 21px;">
                        <div class="lbg_con">续借失败
                            <?php
                            foreach ($fail as $key => $value) {
                                echo "<span>条码：".$value."</span>  [{$note[$value]}]";
                            }
                            ?> 
                        </div>
                        <div style="clear: both"></div>
                    </div>
                <?}?>
    <a href="<?=url('default/borrowsearch')?>">
    <button type="button" class="btn btn-primary btn-block btn-lg">返回</button>
    </a>
</div>
<script type="text/javascript">
    function goback(url){
        
    }
</script>
<?php $this->_endblock(); ?>