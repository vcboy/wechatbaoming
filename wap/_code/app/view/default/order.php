<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
    <h2></h2>
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <?if($info['status']==1){?>
            <div class="row">
                <div class="col-md-12">
                    <div class="thumbnail">
                        <div class="caption">
                            <p>读者证号：<?=$info['readercode']?></p>
                            <p>读者姓名：<?=$info['name']?></p>
                        </div>
                        <img class="img-responsive" src="<?=$_BASE_DIR?>qrcode/qrcode.php?size=8&data=<?echo $code?>" alt="">
                    </div>
                </div>
            <!-- /.row -->
            </div>
            <p class="text_center">可用于自助借还机的二维码扫描枪扫描</p>
        <?}?>
        <?if($info['status']==0){?>
            <div class="alert alert-danger"><?=$info['remark']?></div>
        <?}?>
    <!-- /.container -->
</div>
<?php $this->_endblock(); ?>