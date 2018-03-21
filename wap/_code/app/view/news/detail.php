<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
        <!-- Project Two -->
        <!-- Page Heading/Breadcrumbs -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small><?=$info['title']?></small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">
            <div class="col-md-4">
                <p>时间：<?=date('Y-m-d',$info['datetime'])?></p>
            </div>
            <?php
            if(!empty($info['pic'])){
            ?>
            <div class="col-md-8">
                    <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <?if(!empty($info['pic'])){?>
                            <img class="img-responsive" src="/weixin/library/backend/web/<?=$info['pic']?>" alt="">
                        <?}?>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
            <div class="col-md-4 newscontent">
                <?=$info['content']?>
                <!--h3>Project Description</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae. Sed dui lorem, adipiscing in adipiscing et, interdum nec metus. Mauris ultricies, justo eu convallis placerat, felis enim.</p>
                <h3>Project Details</h3>
                <ul>
                    <li>Lorem Ipsum</li>
                    <li>Dolor Sit Amet</li>
                    <li>Consectetur</li>
                    <li>Adipiscing Elit</li>
                </ul-->
            </div>

        </div>
    <!-- /.container -->
</div>
<style type="text/css">
a:visited { 
    text-decoration: none; 
} 
</style>
<?php $this->_endblock(); ?>