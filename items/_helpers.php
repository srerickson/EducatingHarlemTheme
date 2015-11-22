<?php
  // return seadragon tile config for the image based derivatives
  function sd_image_tile($file){
    $derivatives = array("fullsize","original");
    $levels = array();
    foreach ($derivatives as $i => $derivative) {
      $url = $file->getWebPath($derivative);
      $path = FILES_DIR . '/' . $file->getStoragePath($derivative);
      $dims = getimagesize($path);
      $format = "{url:'%s',width:%d,height:%d}";
      array_push($levels, sprintf($format,$url,$dims[0],$dims[1]));
    }
    $format = "{
      type: 'legacy-image-pyramid',
      levels: [%s]
    }";
    return sprintf($format, join(',', $levels));
  }
?>