
<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <?foreach ($list as $key => $value) {?>
            <a class="row" href="<?echo url('/book',array('id'=>$value['id']))?>">
                <div class="wechat_lbg" style="height: 100px;margin-top: 5px;">
                    <div class="lbg_con">
                
                            <p>通报名称：<?=$value['title']?></p>
                            <p>通报时间：<?=date('Y-m-d',$value['date'])?></p>
                    
                    </div>
                </div>
            </a>
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
