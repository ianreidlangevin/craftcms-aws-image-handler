<?php

namespace ianreid\awsimagehandlerurls\services;

use Craft;
use craft\base\Component;
use craft\elements\Asset;
use craft\helpers\App;
use craft\awss3\Fs;

class AwsImageHandlerUrlsServices extends Component
{


   // Pulic methods
   // --------------------------------------------------------------------------

   /**
    * Build a URL for an asset with the base64 encoded transforms params
    * Return one URL for the asset.
    * Available directly from Twig (with function) and used by method buildSrcSet
    */
   public function buildUrl(Asset $image, int $widthSize = 0, array $transformParams): string
   {

      $requestParams = [];

      // Get bucket from volume and set requestParams bucket and file key
      try {
         $fileSystem = $image->getVolume()->getFs();

         if ($fileSystem instanceof Fs) {
            // retrieve values from the FileSystem
            $distributionUrl = App::parseEnv($fileSystem->url);
            $bucket = App::parseEnv($fileSystem->bucket);
            $bucketSubfolder = App::parseEnv($fileSystem->subfolder);

            $requestParams['bucket'] = $bucket;
            $requestParams['key'] = $bucketSubfolder === '' ? "$image->path" : "$bucketSubfolder/$image->path";
         }
      } catch (\Throwable $e) {
         Craft::error('Could not get AWS S3 filesystem from image: ' . $e->getMessage(), __METHOD__);
      }

      // Edits key - ADD width and custom transforms params
      $edits = [];

      // Set width
      $edits['resize']['width'] = $widthSize;

      // Add each transform in the Edits key (see AWS Image documentation if needed)
      if (!empty($transformParams)) {
         foreach ($transformParams as $key => $value) {
            $edits[$key] = $value;
         }
      }

      // add edits to $requestParams
      $requestParams['edits'] = $edits;

      // encode as string the params and check for errors
      $encodedRequestParams = json_encode($requestParams, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
      // Append to distribution URL the base64 encoded params for AWS
      $url = rtrim($distributionUrl, '/') . '/' . base64_encode($encodedRequestParams);

      return $url;
   }

   /**
    * Build SRCSET choice of images
    * Return a big string with all the values separated by a comma
    */
   public function buildSrcSet(Asset $image, array $widths, array $transformParams): string
   {

      $srcSet = [];

      if (!empty($widths)) {
         foreach ($widths as $widthSize) {
            $srcSetValue = $this->buildUrl($image, $widthSize, $transformParams);
            $srcSet[] = $srcSetValue . " " . "$widthSize" . "w";
         }
      }

      return implode(",", $srcSet);
   }
   
}
