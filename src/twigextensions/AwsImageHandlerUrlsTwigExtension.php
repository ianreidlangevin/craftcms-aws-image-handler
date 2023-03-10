<?php

namespace ianreid\awsimagehandlerurls\twigextensions;

use ianreid\awsimagehandlerurls\AwsImageHandlerUrls;

use craft\elements\Asset;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class AwsImageHandlerUrlsTwigExtension extends AbstractExtension
{

   public function getFunctions()
   {
      return [
         new TwigFunction('imgSrcset', [$this, 'buildSrcSet']),
         new TwigFunction('imgUrl', [$this, 'buildUrl']),
      ];
   }

   // imgSrcset() Twig Function
   // --------------------------------------------------------------------------
   public function buildSrcSet(Asset $image = null, array $widths = [960], array $transformParams = [])
   {
      if($image === null) return;
      $imgSrcset = AwsImageHandlerUrls::getInstance()->awsImageHandlerUrlsServices->buildSrcSet($image->path, $widths, $transformParams);
      return $imgSrcset;
   }

   // imgUrl() Twig Function
   // --------------------------------------------------------------------------

   public function buildUrl(Asset $image = null, int $width = 960, array $transformParams = [])
   {
      if($image === null) return;
      $imgUrl = AwsImageHandlerUrls::getInstance()->awsImageHandlerUrlsServices->buildUrl($image->path, $width, $transformParams);
      return $imgUrl;
   }
}
