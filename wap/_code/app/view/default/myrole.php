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
                            <p>读者证号：<?=$myrole['gh']?></p>
                            <p>可借：<?=$myrole['kj']?></p>
                            <p>已借：<?=$myrole['yj']?></p>
                            <p>未借：<?=$myrole['wj']?></p>
                            <p>可借期限：<?=$myrole['duedate']?></p>
                        </div>
                    </div>
                </div>
            <!-- /.row -->
            </div>
        <?}?>
        <?if($info['status']==0){?>
            <div class="alert alert-danger"><?=$info['remark']?></div>
        <?}?>
    <!-- /.container -->
</div>
<?php $this->_endblock(); ?>