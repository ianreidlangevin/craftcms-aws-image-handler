<p><img src="./src/icon.svg" width="100" height="100" alt="AWS Image Handler URLs icon"></p>

<h1>AWS Image Handler URLs for Craft CMS</h1>


## Requirements

This plugin requires Craft CMS 3.x | 4.x


## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require ianreid/aws-image-handler-urls -w && php craft plugin/install aws-image-handler-urls


## Overview

This plugin adds a Twig function to create base64 encoded URL to use with AWS Serverless Image Handler. It also provides a Twig function to easily create SRCSET values based on an array of widths.

At this time, this plugin only support one filesystem for images (use subfolders if you want to split the assets in the Control Panel).

__Please, do not open issues for AWS configuration problems.__



## Usage in Twig

#### Function imgUrl()

Outputs one image URL

| Parameters    | Type | Default |
| -------- | ------- | ------- |
| image  | Asset    | null |
| width | Integer     | 960 |
| transforms    | Array    | [ ] |

##### Basic usage to create a encoded URL for a width.

```

// Query your asset (this is an example)
{% set photo = craft.assets().id(88).one() %}

// This function will generate a 600px width
{{ imgUrl(photo, 600) }}
```

##### Usage with some transforms

```
{% set transforms = {
      flop: true,
      tint: {
         "r" : 0,
         "g" : 0,
         "b" : 255
      }
   } 
   %}

{{ imgUrl(photo, 600, transforms) }}
```

##### Usage with inline transforms

```
{{ imgUrl(photo, 600, { flop : true, grayscale : true }) }}
```

The result HTML will be something like that :

```
https://yourdist.cloudfront.net/eyJidWNrZXQiOiJjp7InIiOjAsImciOjAsImIiOjI1NX19fQ
```

##### Resizing based on height

In some Case, it could be helpful to resize an image based on its height, but keeping the original ratio. To achieve that, you can pass the resize transform with height and set the width to 0. 

```
{{ imgUrl(photo, 0, { resize: { height:300}} ) }}
```

---

#### Function imgSrcset() 

Outputs value for the SRCSET attribute

| Parameters    | Type | Default |
| -------- | ------- | ------- |
| image  | Asset    | null |
| widths | Array     | [960] |
| transforms    | Array    | [ ] |

##### Basic usage to create a encoded URL for a width.

```

// Query your asset (this is an example)
{% set photo = craft.assets().id(88).one() %}

// This function will generate a SRCSET string for the provided widths
<img 
   height="{{ photo.height }}"
   width="{{ photo.width }}"
   loading="lazy"
   decoding="async"
   srcset="{{ imgSrcset(photo, [320, 480, 960, 1440, 1920]) }}"
   src="{{ imgUrl(photo, 600)}}"
   sizes="90vw"
>
```

The result HTML for the srcset attribute will be something like that :

```
"https://yourdist.cloudfront.net/eyJidWNrZXInIiOjAsImciOjAsImIiOjI1NX19fQ 320w,
https://yourdist.cloudfront.net/eyJidWNrZXQiOilLCJ0aW50Ijp7InIiOjAsImciOjAsImIiOjI1NX19fQ 480w,
https://yourdist.cloudfront.net/eyJidWNrZXQiOilLCJ0aW50Ijp7InIiOjAsImciOjAsImIiOjI1NX19fQ 960w,
https://yourdist.cloudfront.net/eyJidWNrZXQiOilLCJ0aW50Ijp7InIiOjAsImciOjAsImIiOjI1NX19fQ 1440w,
https://yourdist.cloudfront.net/eyJidWNrZXQiOilLCJ0aW50Ijp7InIiOjAsImciOjAsImIiOjI1NX19fQ 1920w"
```

##### Usage with transforms

```
{{ imgSrcset(photo, [320, 480, 960], { flop : true, grayscale : true }) }}
```


## Configuration file
The plugin comes with a `config.php` file that defines some sensible defaults.

If you want to set your own values, you should create a `aws-image-handler-urls.php` file in your Craft config directory. Note that these settings are required. 

#### cloudfrontDistributionUrl
`cloudfrontDistributionUrl` is where you define the base URL for your image URLs. If you want to manage the base URL on a per-file basis, do not add this setting to your config file. If you are using a subfolder, you can append it to this URL. _Trailing slash are not required_. 

#### bucketName
`bucketName` is the name of your S3 images bucket. Do not append subfolder.

#### bucketSubfolder
`bucketSubfolder` is the name of your S3 bucket subfolder if you are using one.

#### defaultTransforms
`defaultTransforms` is where you define defaultTransforms for images. Let empty if you do not want any default transforms. If you do not provide a third argument (transformParams) with the Twig functions, these transforms will be used. Usefull for global things like quality, format, etc.

__Note:__ You can use values from your ENV file for all the configuration settings. Example: `App::env('S3_BUCKET_SUBFOLDER')`



## Example cloudfront-signed-urls.php Config File
```
<?php

use craft\helpers\App;

return [
   'bucketName' => App::env('S3_BUCKET_IMAGES'),
   'cloudfrontDistributionUrl' => App::env('S3_IMAGES_URL'),
   'bucketSubfolder' => App::env('S3_BUCKET_SUBFOLDER'),
    'defaultTransforms' => [
      'flop' => true,
      'grayscale' => true
    ],
];
```



## Sharp JS Transforms

You can use any Sharp JS Transforms that is supported by AWS Serverless Image Handler.
[View list of transforms](https://sharp.pixelplumbing.com/api-operation). 

Note : you have to pass the transforms as TWIG array.





---


Brought to you by [Ian Reid Langevin](https://www.reidlangevin.com)
