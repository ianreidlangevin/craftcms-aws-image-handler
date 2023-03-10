<?php

namespace ianreid\awsimagehandlerurls;

use ianreid\awsimagehandlerurls\models\Settings;
use ianreid\awsimagehandlerurls\services\AwsImageHandlerUrlsServices as Service;
use ianreid\awsimagehandlerurls\twigextensions\AwsImageHandlerUrlsTwigExtension;

use Craft;
use craft\base\Plugin;

class AwsImageHandlerUrls extends Plugin
{

   // Public Methods
   // --------------------------------------------------------------------------

   public function init()
   {
      parent::init();

      // Services
      $this->setComponents([
         'awsImageHandlerUrlsServices' => Service::class,
      ]);
      
      // Twig Extension
      Craft::$app->view->registerTwigExtension(new AwsImageHandlerUrlsTwigExtension());
   }

   // Protected Methods
   // --------------------------------------------------------------------------

   // Settings
   protected function createSettingsModel(): ?\craft\base\Model
   {
      return new Settings();
   }
}
