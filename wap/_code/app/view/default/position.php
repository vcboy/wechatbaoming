<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
	<h1></h1>
    <!-- /.container -->
        <!-- Table -->
                    <!--div class="wechat_lbg">
                        <div class="lbg_con">
                                <span>条码：00849020</span>
                                <span>书名：背叛：为什么我们身处背叛缺佯装不知？</span>
                                <span>作者：背叛：为什么我们身处背叛缺佯装不知？</span>
                                <span>状态：背叛：为什么我们身处背叛缺佯装不知？</span>
                                <span>位置：背叛：为什么我们身处背叛缺佯装不知？</span>
                        </div>
                        <div style="clear: both"></div>
                    </div-->
                <?if($info['return']){//查询成功?>
                    <div class="wechat_lbg">
                        <div class="lbg_con">
                                <span>条码：<?=$info['barcode']?></span>
                                <span>书名：<?=$info['title']?></span>
                                <span>作者：<?=$info['author']?></span>
                                <span>状态：<?=$info['state']?></span>
                                <span>位置：<?=$info['position']?></span>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                <?}else{?>
                    <div class="wechat_lbg">
                        <div class="lbg_con">
                                <span><?=$info['remark']?></span>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                <?}?>
                <?php
                if($fr == 'search'){
                ?>
    <button type="button" class="btn btn-wechat btn-block btn-lg" onclick="back()">返回</button>
    <?php
    }
    ?>
</div>
<?php $this->_endblock(); ?>