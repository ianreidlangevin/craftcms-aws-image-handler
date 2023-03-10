<p><img src="./src/icon.svg" width="100" height="100" alt="AWS Image Handler URLs icon"></p>

<h1>AWS Image Handler URLs for Craft CMS</h1>


## Requirements

This plugin requires Craft CMS 3.x | 4.x and first-party [AWS S3 plugin](https://plugins.craftcms.com/aws-s3)


## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require ianreid/aws-image-handler-urls -w && php craft plugin/install aws-image-handler-urls


## Overview

This plugin adds a Twig function to create base64 encoded URL to use with AWS Serverless Image Handler. It also provides a Twig function to easily create SRCSET values based on an array of widths.

__Please, do not open issues for AWS configuration problems.__



## Usage in Twig

Your image must have a valid AWS S3 [Filesystem](https://craftcms.com/docs/4.x/assets.html#filesystems)

### Function imgUrl()

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

### Function imgSrcset() 

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



## Sharp JS Transforms

You can use any Sharp JS Transforms that is supported by AWS Serverless Image Handler.
[View list of transforms](https://sharp.pixelplumbing.com/api-operation). 

Note : you have to pass the transforms as TWIG array.





---


Brought to you by [Ian Reid Langevin](https://www.reidlangevin.com)
