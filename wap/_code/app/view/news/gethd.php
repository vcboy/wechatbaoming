<div id="base_content">
        <?foreach ($list as $key => $value) {?>
            <div class="row newblock">
                <?php
                if($value['pic']){
                ?>
                <div class="col-md-7">
                    <a href="<?=url('news/detail',array('id'=>$value['id']))?>">
                        <img class="img-responsive img-hover" src="/weixin/library/backend/web/<?=$value['pic']?>" alt="">
                    </a>
                </div>
                <?php
                }
                ?>
                <div class="col-md-5">
                    <!-- <h3>Project Two</h3> -->
                    <h4><?=$value['title']?></h4>
                    <!-- <p style="height: 100px;overflow: hidden;" class="newscontent"><?=$value['content']?></p> -->
                    <a class="btn btn-primary" style="float: right;" href="<?=url('news/detail',array('id'=>$value['id']))?>">查看内容</a>
                </div>
            </div>
            <hr>
        <?}?>

    </div>