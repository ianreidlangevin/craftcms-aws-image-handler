<?php

namespace ianreid\awsimagehandlerurls\models;

use craft\base\Model;

class Settings extends Model
{

   public string $cloudfrontDistributionUrl = '';
   public string $bucketName = '';
   public string $bucketSubfolder = '';
   public array $defaultTransforms = [];

   public function rules(): array
   {
      return [
         ['cloudfrontDistributionUrl', 'required'],
         ['bucketName', 'required'],
         ['bucketSubfolder', 'required'],
         ['defaultTransforms', 'required']
      ];
   }
}