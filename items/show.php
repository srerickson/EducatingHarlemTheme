<?php echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => 'items show')); ?>
<div id="primary">
  <!--
  <h1><?php// echo metadata('item', array('Dublin Core','Title')); ?></h1>

  <div id="item-metadata">
    <?php// echo all_element_texts('item'); ?>
  </div>
  -->

  <!-- Files -->
  <?php

    function img_object($file){
      $url = file_display_url($file);
      $path = FILES_DIR . '/' . $file->getStoragePath();
      $dims = getimagesize($path);
      $format = "{
        type: 'legacy-image-pyramid',
        levels: [{
          url: '%s',
          width: %d,
          height: %d
        }]
      }";
      return sprintf($format,$url,$dims[0],$dims[1]);
    }
    $files = join( ',', array_map('img_object', $item->getFiles()));
    $sequenceMode = count($item->getFiles()) > 1 ? 'true' : 'false';

  ?>

  <?php if (count($item->getFiles())>0): ?>
    <h3><?php// echo __('Files'); ?></h3>
    <div id="item-images-viewer" style="height: 800px; width:800px;">

    </div>

    <script type="text/javascript">
      var viewer = OpenSeadragon({
        id: "item-images-viewer",
        prefixUrl: "../../themes/EducatingHarlemTheme/javascripts/vendor/openseadragon-2.0.0/images/",
        tileSources: [<?php echo $files?>],
        sequenceMode: <?php echo $sequenceMode?>,
        showReferenceStrip: <?php echo $sequenceMode ?>
      });
    </script>
  <?php endif ?>



 <?php if(metadata('item','Collection Name')): ?>
    <div id="collection" class="element">
      <h3><?php echo __('Collection'); ?></h3>
      <div class="element-text"><?php echo link_to_collection_for_item(); ?></div>
    </div>
 <?php endif; ?>

   <!-- The following prints a list of all tags associated with the item -->
  <?php if (metadata('item','has tags')): ?>
  <div id="item-tags" class="element">
      <h3><?php echo __('Tags'); ?></h3>
      <div class="element-text"><?php echo tag_string('item'); ?></div>
  </div>
  <?php endif;?>

  <!-- The following prints a citation for this item. -->
  <div id="item-citation" class="element">
      <h3><?php echo __('Citation'); ?></h3>
      <div class="element-text"><?php echo metadata('item','citation',array('no_escape'=>true)); ?></div>
  </div>
     <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>


  <ul class="item-pagination navigation">
      <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
      <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
  </ul>

</div> <!-- End of Primary. -->

 <?php echo foot(); ?>
