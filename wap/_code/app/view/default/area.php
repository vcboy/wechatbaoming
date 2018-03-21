<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<style type="text/css">
.wechat_tab{
    float: left;
    text-decoration: none;
    color: #337ab7;
    background-color:#fff;
    width: 25%;
    text-align: center;
    line-height: 34px;
    border-radius: 4px;
}
.active{
    background-color: #337ab7;
    color: #fff;
}
.wechat_top{
    padding-top: 10px;
}
td{ 
height:30px; 
} 
.table{ 
border:1px solid #cad9ea; 
color:#666; 
width: 90% !important;
margin: 0px auto;
} 
.table th { 
background-repeat:repeat-x; 
height:30px; 
} 
.table td,.table th{ 
border:1px solid #cad9ea; 
padding:0 1em 0; 
} 
.table tr.alter{ 
background-color:#f5fafe; 
} 
</style>
<div class="container wechat_top">
    <?
    //for ($i=2; $i <6 ; $i++) { 
    foreach ($floors as $key => $value) {

    ?>
        <a href="<?echo url('default/area',array('floot'=>$value['FloorNo']))?>" class="wechat_tab <?if($floot==$value['FloorNo'])echo 'active';?>"><?=$value['FloorName']?></a>
    <?}?>
</div>
<div>
    <img width="100%" src="<?=$_BASE_DIR?>images/<?=$floot?>.jpg">
</div>
<?if(empty($error)){?>
    <table width="90%" class="table"> 
        <tr> 
            <th>区块</th> 
            <th>编号</th> 
        </tr> 
        <?foreach ($list as $key => $value) {
            $class = '';
            if($key%2 == 1)   $class = 'alter';
        ?>
            <tr class="<?=$class?>"> 
                <td><a href="<?echo url('default/shelf',array('floot'=>$floot,'blockno'=>$value['BlockNo']))?>"><?=$value['BlockName']?></a></td> 
                <td><a href="<?echo url('default/shelf',array('floot'=>$floot,'blockno'=>$value['BlockNo']))?>"><?=$value['BlockNo']?></a></td> 
            </tr> 
        <?}?>
    </table> 
<?}else{?>
    <table width="90%" class="table"> 
        <tr> 
            <th><?=$error?></th> 
        </tr> 
    </table> 
<?}?>
<?php $this->_endblock(); ?>