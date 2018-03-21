<?php $_hidden_elements = array(); ?>
<script type="text/javascript">
function load_img(){
        $('#img_file_uploadxxx').uploadify({
            'uploader'       : '<?=$_BASE_DIR?>uploadify/uploadify.swf',
            'script'         : '<?=url('user/importimg')?>',
            'cancelImg'      : 'uploadify/cancel.png',
            'folder'         : 'uploads',
            'multi'          : false,
            'auto'           : true,
            'fileExt'        : '*.jpg;*.gif;*.png',
            'fileDesc'       : 'Image Files (.JPG, .GIF, .PNG)',
            'queueID'        : 'custom-queue',
            'buttonImg'      : '<?=$_BASE_DIR?>uploadify/button.jpg',
            'height'         : '25',
            'width'          : '82',
            'queueSizeLimit' : 200,
            'simUploadLimit' : 200,
            'sizeLimit'   : 1024*1024*2,
            'removeCompleted': false,
            'scriptData'     :{
                'name':$('#name').val()
            },
            'onComplete'  : function(event, ID, fileObj, response, data) {//console.log(response);
                var info2 = eval('('+response+')');
                $("#img_show_info").attr("src","<?=substr($_BASE_DIR,0,-1)?>"+info2.imgPath);
                $("#head_pic").val(info2.imgPath);
            },
            'onError'   : function (event,ID,fileObj,errorObj) {
                alert("上传的文件有误，请上传小于2M的.JPG, .GIF, .PNG的图片文件.");
            }
            
        });
}
</script>
<div id="form_panel">
<form <?php foreach ($form->attrs() as $attr => $value): $value = h($value); echo "{$attr}=\"{$value}\" "; endforeach; ?>>
	<fieldset class="query_form" style="margin-top:10px;">
		<?php if ($form->_tips): ?>
	    <legend><?php echo h($form->_subject); ?></legend>
        <?php endif; ?>
        <table class="form_table" width="100%" cellpadding="0" cellspacing="0">
            <tr  height="30">
			<?php
			$count=0;$count_all=0;$all=count($form->elements());$count_set=0;
            foreach ($form->elements() as $element):
				$count_all++;
			    if ($element->_ui == 'hidden')
			    {
			        $_hidden_elements[] = $element;
			        continue;
			    }
			    $id = $element->id;
			    $count_set++;    
            ?>
            
            <?php if($count==0&&$count_set>1){?><tr height="30"><?php } 
				if($element->_ui=="memo" || $element->_nextline){
					?><tr height="30">
						<td<?php if($element->_ui=="memo"){?> colspan=6<?php }?>>
							<span style="width:92px;">
								<?php echo h($element->_label); ?>：
							</span>
							<span id="span_<?php echo $id;?>"><?php echo trim(Q::control($element->_ui, $id, $element->attrs()));?><?php if ($element->_req):?>&nbsp;
								<font class="req">*</font><?php endif; ?>
							</span>
					<?php if (!$element->isValid()): ?>
				    <br><span class="error"><?php echo nl2br(h(implode("，", $element->errorMsg()))); ?></span>
				    <?php endif; ?>
					</td><?php if($element->_ui=="memo"){?></tr><?php 
					$count==0;}else{$count=1;}
                }else{
                    $count++;?>
				<td>
					<span style="width:92px;"><?php echo h($element->_label); ?>：</span><span id="span_<?php echo $id;?>"><?php echo trim(Q::control($element->_ui, $id, $element->attrs()));?><?php if ($element->_req):?>&nbsp;<font class="req">*</font><?php endif; ?></span>
					<?php if (!$element->isValid()): ?>
				    <br><span class="error"><?php echo nl2br(h(implode("，", $element->errorMsg()))); ?></span>
				    <?php endif; ?>
				</td>
				<?php if($count_all==2){?>
				<td rowspan=4 >
					<div style="margin:10px 0 0 10px;border:solid #eeeeee 1px;height:130px;width:120px;">
						<img id="img_show_info" src="<?=$_BASE_DIR."img/noneman.gif"?>" style="border:none;height:130px;"/>
					</div>
                    <input type="file" id="img_file_uploadxxx" name="Filedata"/ >
				</td>
				<?php }?>
            <?php if(($count==3&&$count_set>8)||($count==2&&$count_set<9)){ $count=0; ?></tr><?php  }else if($count_all==$all){?></tr><?php }}?>
            <?php endforeach;?>
            

			<tr >
				<td colspan=3><div id="course_show_info" style="margin-top:5px;display:none">&nbsp;</div></td>
			</tr>
			<tr height="30">
				<td><input type="submit" class="btn4" value="保存" style="margin-left:15px; border:0px;" onclick="return act_set();">
				<?php if(@$option_type=="edit"){?>
				<input type="button" class="btn4" value="返回" style="margin-left:15px; border:0px;" onclick="javascript:location='<?php echo url('')?>';">
				<?php }else{ ?>
				<input type="button" class="btn4" value="重置" style="margin-left:15px; border:0px;" onclick="panel_reset();">
				<?php }?>
				</td>
			</tr>
		</table>
		<?php foreach ($_hidden_elements as $element): ?>
		<input type="hidden" name="<?php echo $element->id; ?>" id="<?php echo $element->id; ?>" value="<?php echo h($element->value); ?>" />
		<?php endforeach; ?>
    </fieldset>
    <script>
        if($("#head_pic").val()!=""){
            $("#img_show_info").attr("src","<?=substr($_BASE_DIR,0,-1)?>"+$("#head_pic").val());
        }
    </script>
</form>
</div>
