<?php

  $body_classes = "items show ";
  $body_classes .= strtolower(str_replace(' ','-',$item->getItemType()->name));
  echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => $body_classes));

?>

<div id="main-row">



  <div id="item-media">



    <!-- Images Files -->
    <?php

      // build list of item images
      $files = $item->getFiles();
      $imgs = array();
      foreach ($files as $i => $file) {
        if($file->has_derivative_image){
          array_push($imgs, $file);
        }
      }

      // return's seadragon tile config for image/file
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
      $sd_tiles = join( ',', array_map('img_object', $imgs));
      $sd_seqMode = count($imgs) > 1 ? 'true' : 'false';
    ?>

    <?php if (count($imgs)>0): ?>
      <div id="seadragon-wrapper">
        <div id="seadragon-viewer"></div>
      </div>
      <script type="text/javascript">
        var viewer = OpenSeadragon({
          id: "seadragon-viewer",
          prefixUrl: "../../themes/EducatingHarlemTheme/javascripts/vendor/openseadragon-2.0.0/images/",
          tileSources: [<?php echo $sd_tiles ?>],
          sequenceMode: <?php echo $sd_seqMode ?>,
          showReferenceStrip: <?php echo $sd_seqMode ?>
        });
      </script>
    <?php endif ?>


    <?php if(count($files) > 0): ?>
      <div class="element-set" id="item-files">
        <div class="element">
          <h3><?php echo __('All Files'); ?></h3>
          <ul>
            <?php foreach ($item->getFiles() as $i => $f): ?>
              <li>
                <a href="<?php echo $f->getWebPath() ?>">
                  <?php echo $f->getDisplayTitle(); ?>
                </a>
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      </div>
    <?php endif?>


    <!-- The following prints a citation for this item. -->
    <div class="element-set">
      <div id="item-citation" class="element">
        <h3><?php echo __('Citation'); ?></h3>
        <div class="element-text"><?php echo metadata('item','citation',array('no_escape'=>true)); ?></div>
      </div>
    </div>




  </div> <!-- media column -->






  <!-- Metadata column -->
  <div id="item-metadata">

    <?php echo all_element_texts('item', array(
        'show_element_sets' => array('Dublin Core'),
        'show_element_set_headings' => false
      )
    ); ?>


    <!-- Item Type Metadata -->
    <?php
      echo $item_type_metadata = all_element_texts('item', array(
        'show_element_sets' => array('Item Type Metadata'),
        'show_element_set_headings' => false
        )
      );
    ?>

    <?php if(metadata('item','Collection Name')): ?>
      <div class="element-set">
        <div id="collection" class="element">
          <h3><?php echo __('Collection'); ?></h3>
          <div class="element-text"><?php echo link_to_collection_for_item(); ?></div>
        </div>
      </div>
    <?php endif; ?>


    <!-- The following prints a list of all tags associated with the item -->
    <?php if (metadata('item','has tags')): ?>
    <div class="element-set">
      <div id="item-tags" class="element">
        <h3><?php echo __('Tags'); ?></h3>
        <div class="element-text"><?php echo tag_string('item'); ?></div>
      </div>
    </div>
    <?php endif;?>


  </div> <!-- Metadata column -->

  <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>


  <ul class="item-pagination navigation">
      <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
      <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
  </ul>


</div> <!-- row -->


 <?php echo foot(); ?>
