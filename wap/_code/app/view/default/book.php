
<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <?foreach ($list as $key => $value) {?>
            <a class="row" href="<?echo url('/info',array('id'=>$value['id']))?>">
                    <div class="wechat_lbg" style="margin-top: 5px;">
                        <div class="lbg_con">
                            <p>书名：<?=$value['name']?></p>
                            <p>作者：<?=$value['author']?></p>
                        </div>
                    </div>
            <!-- /.row -->
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
