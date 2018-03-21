<ul id="base_content" class="p_list">
  <?php
  foreach ($news as $key => $value) {
    echo "<li><a href='".url('/show',array('id'=>$value['id']))."'>".$value['title']."</a></li>";
  }
  ?>
</ul>