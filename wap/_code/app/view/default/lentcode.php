<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <div class="row">
            <div class="col-lg-12">
                <h2></h2>
            </div>
            <?if($info['status']){?>
                <div class="col-md-12">
                    <div class="thumbnail">
                        <div class="caption">
                            <p>读者证号：<?=$info['readercode']?></p>
                            <p>读者姓名：<?=$info['name']?></p>
                        </div>
                        <img class="img-responsive" src="<?=$_BASE_DIR?>qrcode/qrcode.php?size=8&data=<?echo $code;?>" alt="">
                    </div>
                    <button type="button" class="btn btn-primary btn-block btn-lg" onclick="back()">返回</button>
                </div>
                <!-- For success/fail messages -->
            <?}else{?>
                <div class="col-md-12">
                    <div class="thumbnail">
                        <div class="caption">
                            <p>生成出错：<?=$remark?></p>
                        </div>
                    </div>
                </div>
            <?}?>
        <!-- /.row -->
        </div>
    <!-- /.container -->
</div>
<?php $this->_endblock(); ?>