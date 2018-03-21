<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
   
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
            <a class="row" href="<?echo url('/info',array('id'=>'11'))?>">
                <div class="panel panel-default" style="margin-bottom:0px;">
                    <table class="table list-group-item-success">
                        <tbody>
                            <tr>
                                <td>预约书名：111</td>
                            </tr>
                            <tr>
                                <td>预约时间：2017-02-09</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">
                                    <a  class="btn btn-primary  " href="<?echo url('/cancel',array('barcode'=>'11'))?>">取消预约</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </a>
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
