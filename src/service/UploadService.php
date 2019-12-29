<?php

namespace Service;

use Models\Image;

class UploadService {

  protected $imageModel;

  public function __construct() {
    $this->imageModel = new Image;
  }

  public function upload($file_extension, $file_extension_error, $file_extension_size, $file_extension_tmp, $image) {
      if (isset($file_extension) AND $file_extension_error == 0) {
          if ($file_extension_size <= 1000000) {
              $infosfichier = pathinfo($image);
              $extension_upload = $infosfichier['extension'];
              $extensions_access = array('jpg', 'jpeg', 'gif', 'png');
              if (in_array($extension_upload, $extensions_access)) {
                  move_uploaded_file($file_extension_tmp, 'assets/img/' . basename($image));
                  $this->imageModel->setImage($image);
                  $imageId = $this->imageModel->getId($image);
                  $imageId = $imageId['id'];
              }
          }
      } else {
          $imageId = 14;
      }
      return $imageId;
  }
}
