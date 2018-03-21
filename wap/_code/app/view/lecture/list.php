
<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <?foreach ($list as $key => $value) {?>
            <a class="wechat_lbg" href="<?echo url('/info',array('id'=>$value['id']))?>" >
                <div class="lbg_con" style="float: left;">
                        <span>讲座：<?=$value['title']?></span>
                        <span>演讲者：<?=$value['speaker']?></span>
                        <span>讲座时间：<?=date('Y-m-d',$value['datetime'])?></span>
                        <span>预定状态：<?if($value['datetime']>time()){echo "可预定";}else{echo "已结束";}?></span>
                </div>
                <div class="lbg_tip"></div>
                <div style="clear: both"></div>
            </a>
        <?}?>
        <?if(count($list)==0){?>
            <h2></h2>
                <div class="alert alert-danger">当前未有可预订讲座</div>
        <?}?>
    <!-- /.container -->
</div>
<style type="text/css">
a:visited { 
    text-decoration: none; 
} 
a:hover { 
    text-decoration: none; 
} 
</style>
<?php $this->_endblock(); ?>
