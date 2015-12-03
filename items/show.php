<?php

   include '_helpers.php';

  // build lists of file types
  $files = $item->getFiles();
  $audios = array();
  $videos = array();
  $images = array();
  $pdfs   = array();
  foreach ($files as $i => $file) {
    if(preg_match('/^audio\/.*/', $file->mime_type)){
      array_push($audios, $file);
    } elseif (preg_match('/^video\/(?!quicktime)/', $file->mime_type)) {
      array_push($videos, $file);
    } elseif(preg_match('/^image\/.*/', $file->mime_type)){
      array_push($images, $file);
    } elseif(preg_match('/^(application|text)\/(pdf).*/', $file->mime_type)){
      array_push($pdfs, $file);
    }
  }
  $body_classes = "items show ";
  if($item_type = $item->getItemType()){
    $body_classes .= strtolower(str_replace(' ','-',$item_type->name));
  }
  if(count($audios)>0){$body_classes .= " audios";}
  if(count($videos)>0){$body_classes .= " videos";}
  if(count($images)>0){$body_classes .= " images";}
  echo head(array('title' => metadata('item', array('Dublin Core', 'Title')),'bodyclass' => $body_classes));
  $theme_path = Theme::getTheme(Theme::getCurrentThemeName())->getAssetPath();
?>


<div id="title-row">
  <div class="title">
    <h2><?php echo metadata('item', array('Dublin Core', 'Title')); ?></h2>

    <div class="collection">
      <!-- Collections: use CollectionTree or fallback to default -->
      <?php if(metadata('item','Collection Name')): ?>
        <?php if(plugin_is_active('CollectionTree')): ?>
          <?php
            $collection = get_collection_for_item($item);
            $collectionTree = get_db()->getTable('CollectionTree')->getCollectionTree($collection->id);
            echo get_view()->partial(
              'collections/collection-tree-list.php',
              array('collection_tree' => $collectionTree)
            );
          ?>
        <?php else: ?>
          <div class="element-set">
            <div id="collection" class="element">
              <h3><?php echo __('from'); ?></h3>
              <div class="element-text"><?php echo link_to_collection_for_item(); ?></div>
            </div>
          </div>
        <?php endif ?>
      <?php endif; ?>
    </div>
  </div>
</div>


<div id="main-row">

  <div id="item-media">

    <!-- Audio Player -->
    <?php if(count($audios)>0):?>
      <div class="player-wrapper">
        <audio src="<?php echo __($audios[0]->getWebPath()); ?>" width="100%">
        </audio>
      </div>
    <?php endif?>


    <!-- Video Player -->
    <?php if(count($videos)>0):?>
      <div class="player-wrapper">
        <video width="340" height="240" controls="controls" preload="none">
          <source type="<?php echo $videos[0]->mime_type; ?>"
                   src="<?php echo $videos[0]->getWebPath(); ?>" />
          <object width="340" height="240" type="application/x-shockwave-flash" data="<?php echo $theme_path; ?>/javascripts/vendor/mediaelement/flashmediaelement.swf">
              <param name="movie" value="<?php echo $theme_path; ?>/javascripts/vendor/mediaelement/flashmediaelement.swf" />
              <param name="flashvars" value="controls=true&file=<?php echo $videos[0]->getWebPath(); ?>" />
          </object>
        </video>
      </div>
    <?php endif?>



    <!-- Images Gallery -->
    <?php if (count($images)>0): ?>
      <?php // seadragon configs
        $sd_tiles = join( ',', array_map('sd_image_tile', $images));
        $sd_seqMode = count($images) > 1 ? 'true' : 'false';
      ?>
      <div id="seadragon-wrapper">
        <div id="seadragon-viewer"></div>
      </div>
      <script type="text/javascript">
        var viewer = OpenSeadragon({
          id: "seadragon-viewer",
          prefixUrl: "<?php echo $theme_path; ?>/javascripts/vendor/openseadragon-2.0.0/images/",
          tileSources: [<?php echo $sd_tiles ?>],
          sequenceMode: <?php echo $sd_seqMode ?>,
          showReferenceStrip: <?php echo $sd_seqMode ?>
        });
      </script>
    <?php endif ?>

    <!-- The following prints a list of all tags associated with the item -->
    <?php if (metadata('item','has tags')): ?>
    <div class="element-set">
      <div id="item-tags" class="element">
        <h3><?php echo __('Tags'); ?></h3>
        <div class="element-text"><?php echo tag_string('item'); ?></div>
      </div>
    </div>
    <?php endif;?>


    <!-- The following prints a citation for this item. -->
    <div class="element-set">
      <div id="item-citation" class="element">
        <h3><?php echo __('Citation'); ?></h3>
        <div class="element-text"><?php echo metadata('item','citation',array('no_escape'=>true)); ?></div>
      </div>
    </div>


    <!-- Interview Transcript if present-->
    <?php if(metadata('item', array('Item Type Metadata', 'Transcription')) || count($pdfs)>0): ?>
      <div class="element-set">
        <div id="oral-history-item-type-metadata-transcription" class="element">
          <h3>Transcript</h3>
          <?php if(count($pdfs)>0): ?>
            <a class="download-transcription" href="<?php echo $pdfs[0]->getWebPath(); ?>" target="_blank">
              transcript (.pdf)
            </a>
          <?php endif ?>
          <div class="element-text">
            <?php echo metadata('item', array('Item Type Metadata', 'Transcription')); ?>
          </div>
        </div>
      </div>
    <?php endif ?>


    <!-- All Files List -->
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




  </div> <!-- Metadata column -->

  <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>


  <ul class="item-pagination navigation">
      <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
      <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
  </ul>


</div> <!-- row -->


 <?php echo foot(); ?>
