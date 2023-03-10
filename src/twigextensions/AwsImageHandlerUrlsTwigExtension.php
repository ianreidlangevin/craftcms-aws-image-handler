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

   /**
    * imgSrcset() Twig Function
    *
    * @param Asset $image
    * @param array $widths
    * @param array $transformParams
    *
    * @return string|null
    */
   public function buildSrcSet(Asset $image, array $widths = [960], array $transformParams = []) : ?string
   {
      return AwsImageHandlerUrls::getInstance()->awsImageHandlerUrlsServices->buildSrcSet($image, $widths, $transformParams);
   }

   /**
    * imgSrimgUrlcset() Twig Function
    *
    * @param Asset $image
    * @param int $width
    * @param array $transformParams
    *
    * @return string|null
    */
   public function buildUrl(Asset $image, int $width = 960, array $transformParams = []) : ?string
   {
      return AwsImageHandlerUrls::getInstance()->awsImageHandlerUrlsServices->buildUrl($image, $width, $transformParams);
   }
}
