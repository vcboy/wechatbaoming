<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
    <h2></h2>
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <?foreach ($list as $key => $value) {?>
            <a class="row" href="<?echo url('/info',array('id'=>$value['id']))?>">
                <div class="col-md-12">
                    <div class="thumbnail">
                        <div class="caption">
                            <p>标题：<?=$value['title']?></p>
                            <p>姓名：<?=$value['speaker']?></p>
                            <p>简介：<?=$value['content']?></p>
                        </div>
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
</style>
<?php $this->_endblock(); ?>