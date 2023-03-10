<?php
/**
 * AWS Image Handler Urls plugin for Craft CMS 4.x
 * Easily use AWS Serverless Image Handler in Twig templates
 * @link https://reidlangevin.com
 * @copyright Copyright (c) 2023 Ian Reid Langevin
 * 
 * @note Each config can be stored in your ENV file. Access them with App::env('YOUR_VARIABLE_NAME').
 * 
 */

use craft\helpers\App;

return [

   /**
    *  --------------------------------------------------------------------------
    *  Distribution and bucket settings
    *  --------------------------------------------------------------------------
    *
    *  Following values are only example.
    *
    */
   'bucketName' => App::env('S3_BUCKET_IMAGES') ?? "",
   'cloudfrontDistributionUrl' => App::env('S3_IMAGES_URL') ?? "",
   'bucketSubfolder' => App::env('S3_BUCKET_SUBFOLDER') ?? "",


   /**
    *  --------------------------------------------------------------------------
    *  Image default transforms
    *  --------------------------------------------------------------------------
    *
    *  If you do not add a third argument in the Twig function for transforms, 
    *  these values will be used. Leave empty if not useful for your project.
    *  Values must be valid SharpJS transforms in a PHP array.
    *
    */

   /** 
    * 'defaultTransforms' => [
    *   'flop' => true,
    *   'tint' => [
    *      "r" => 0,
    *      "g" => 0,
    *      "b" => 255
    *   ]
    * ],
    */

];
