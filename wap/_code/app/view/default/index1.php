<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="news">
        <ul id="myTab" class="nav nav-tabs">
         <li class="active">
            <a href="<?=url('/newslist')?>" >
               动态新闻
            </a>
         </li></ul>
        <div>
            <ul class="p_newslist">
              <?php
              foreach ($news as $key => $value) {
                echo "<li><a href='".url('/show',array('id'=>$value['id']))."'>".$value['title']."</a></li>";
              }
              ?>
            </ul>
        </div>
      </div>

    </div> <!-- /container -->
<?php $this->_endblock(); ?>