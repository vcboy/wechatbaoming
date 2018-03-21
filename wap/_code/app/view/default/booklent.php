<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<div class="container">
    <h2></h2>
        <!-- Contact Form -->
        <!-- In order to set the email address and subject line for the contact form go to the bin/contact_me.php file. -->
    <form action="<?echo url('default/booklent')?>" method="post" name="sentMessage" >
                <div class="panel panel-default">
                    <!-- Table -->
                    <table class="table lent" id="lent-<?=$value['barcode']?>">
                        <tbody>
                                <tr>
                                    <td width="40%">条码号：</td>
                                    <td><?=$book['barcode']?></td>
                                </tr>
                                <tr>
                                    <td>题名：</td>
                                    <td><?=$book['name']?></td>
                                </tr>
                                <tr>
                                    <td>作者：</td>
                                    <td><?=$book['author']?></td>
                                </tr>
                                
                        </tbody>
                    </table>
                </div>
           
            <div class="control-group form-group" id="msg">
                <p class="help-block"></p>
            </div>               
            <input name="barcode" id="barcode" type="hidden">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block btn-lg" >立即借阅</button>
            </div>
    </form>
    <!-- /.container -->
</div>
<?php $this->_endblock(); ?>