<div class="col-md-4"  id="base_content">
    <? foreach ($list as $i=>$row) { ?>
    <div class="row newblock">
    <a href="<?=url('/signup',array('id'=>$row['id'],'tabletype'=>$row['tabletype']))?>" style=" padding: 0px 10px;">
        <div class="media">
            <div class="pull-left">
                <img src="<?=rtrim($_BASE_DIR, '/').$row['img']?>" width="140px" height="85px" />
            </div>
            <div class="media-body">
                <h4 class="media-heading"><?=$row['name']?></h4>
                <p><?=$row['enddate']?></p>
            </div>
        </div>
    </a>
    </div>
    <?php } ?> 
</div>