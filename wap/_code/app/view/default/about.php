<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
    <h2></h2>
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->

            <div class="row">
                <div class="col-md-12">

                    <?=$content?>
                
                </div>
            <!-- /.row -->
            </div>
    <!-- /.container -->
</div>
<?php $this->_endblock(); ?>