<?$this->_extends("_layouts/main_layout");?>
<?$this->_block("contents");?>
    <div class="container">
      <div class="card">
        <div class="art_title">
          <h2 class="h2"><?=$newsdetail['title']?></h2>
          <p><span><?=date("Y-m-d H:i",$newsdetail['release_date'])?></span></p>
        </div>
        <div class="art_content">
          <div class="art_pic"></div>
          <div><?=$newsdetail['content']?></div>
        </div>
      </div>
    </div> <!-- /container -->
<?php $this->_endblock(); ?>