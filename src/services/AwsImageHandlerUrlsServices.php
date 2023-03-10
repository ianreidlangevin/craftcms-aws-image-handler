<?php

namespace ianreid\awsimagehandlerurls\services;

use ianreid\awsimagehandlerurls\AwsImageHandlerUrls;

use Craft;
use craft\base\Component;
use yii\base\InvalidConfigException;


class AwsImageHandlerUrlsServices extends Component
{

   // Vars
   // --------------------------------------------------------------------------

   private string $_distributionUrl;
   private string $_bucketName;
   private string $_bucketSubfolder;
   private array $_defaultTransforms;


   // Constructor
   // --------------------------------------------------------------------------

   public function __construct()
   {
      $settings = AwsImageHandlerUrls::getInstance()->getSettings();

      $this->_distributionUrl = $settings->cloudfrontDistributionUrl;
      $this->_bucketName = $settings->bucketName;
      $this->_bucketSubfolder = $settings->bucketSubfolder;
      $this->_defaultTransforms = $settings->defaultTransforms;
   }

   // Pulic methods
   // --------------------------------------------------------------------------

   /**
    * Build SRCSET choice of images
    * Return a big string with all the values separated by a comma
   */
   public function buildSrcSet(string $imagePath, array $widths, array $transformParams) : string
   {

      $srcSet = [];

      if (!empty($widths)) {
         foreach ($widths as $widthSize) {
            $srcSetValue = $this->buildUrl($imagePath, $widthSize, $transformParams);
            $srcSet[] = $srcSetValue . " " . "$widthSize" . "w";
         }
      }

      return implode(",", $srcSet);
   }


   /**
    * Build a URL for an asset with the base64 encoded transforms params
    * Return one URL for the asset.
    * Available directly from Twig (with function) and used by method buildSrcSet
   */
   public function buildUrl(string $imagePath, int $widthSize = 0, array $transformParams) : string
   {

      $requestParams = [];
      $edits = [];
      
      // Check if bucket name is empty and return error for dev environments only
      if ($this->_bucketName === '') {
         if (Craft::$app->getConfig()->general->devMode) {
            throw new InvalidConfigException('Bucket name for AWS Image Handler is missing in your config file.');
         }
      }

      // request bucket
      $requestParams['bucket'] = $this->_bucketName;
      // request key
      $requestParams['key'] = $this->_bucketSubfolder === '' ? "$imagePath" : "$this->_bucketSubfolder/$imagePath";
      // Edits key - ADD width and custom transforms params
      $edits['resize']['width'] = $widthSize;
      // for each transform params, push it into edits, fallback to config values if empty
      $transformParameters = !empty($transformParams) ? $transformParams : $this->_defaultTransforms;
      // Add each transform in the Edits key (see AWS Image documentation if needed)
      if (!empty($transformParameters)) {
         foreach ($transformParameters as $key => $value) {
            $edits[$key] = $value;
         }
      }

      // add edits to $requestParams
      $requestParams['edits'] = $edits;
      // encode as string the params and check for errors
      $encodedRequestParams = json_encode($requestParams, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
      // Append to distribution URL the base64 encoded params for AWS
      $url = rtrim($this->_distributionUrl, '/') . '/' . base64_encode($encodedRequestParams);

      return $url;
   }
}
