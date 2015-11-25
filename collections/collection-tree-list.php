<div class="element-set">
  <div  class="element" id="collection">
    <h3><?php echo __('from'); ?></h3>
    <div class="element-text">
      <?php
        $link_to_collection = isset($link_to_collection) ? $link_to_collection : true;
        echo $this->collectionTreeList($collection_tree, $link_to_collection);
      ?>
    </div>
  </div>
</div>
