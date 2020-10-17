# GeoIP Library for PHP

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-scrutinizer] 
[![Total Downloads][ico-downloads]][link-downloads]
[![Hits][ico-hits]][link-hits]


## Description
PHP-GeoIP provides you with the ability to gather open source ip intelligence by using the open api provided by [GoGeoIP](https://github.com/Webklex/gogeoip).


## Table of Contents
- [Installation](#installation)
- [Usage](#usage)
    - [Basic usage example](#basic-usage-example)
- [Support](#support)
- [Security](#security)
- [Credits](#credits)
- [License](#license)


## Installation
1.) Just install the PHP-GeoIP package by running the following command:
```shell script
composer require webklex/php-geoip
```


## Usage
#### Basic usage example
This is a basic example, which will dump the geoip information for the current user ip as well as for the ip "205.13.135.36".

```php
use Webklex\GeoIP\GeoIP;

$gp = new GeoIP();

var_dump($gp->current());
var_dump($gp->get("205.13.135.36"));
```

If you want to use your own instance of [GoGeoIP](https://github.com/Webklex/gogeoip), just provide your endpoint instead:
```php
use Webklex\GeoIP\GeoIP;

$gp = new GeoIP("https://my_enpoint.tld");

var_dump($gp->current());
var_dump($gp->get("205.13.135.36"));
```

#### Response:
```json
{
  "network": {
    "ip": "208.13.138.36",
    "as": {
      "number": 209,
      "name": "CenturyLink Communications, LLC"
    },
    "isp": "",
    "domain": "",
    "tld": [".us"],
    "bot": false,
    "tor": false,
    "proxy": false,
    "proxy_type": "",
    "last_seen": 0,
    "usage_type": ""
  },
  "location": {
    "region_code": "NV",
    "region_name": "",
    "city": "Las Vegas",
    "zip_code": "89129",
    "time_zone": "America/Los_Angeles",
    "longitude": -115.2821,
    "latitude": 36.2473,
    "accuracy_radius": 20,
    "metro_code": 839,
    "country": {
      "code": "US",
      "cioc": "USA",
      "ccn3": "840",
      "call_code": ["1"],
      "international_prefix": "011",
      "capital": "Washington D.C.",
      "name": "United States",
      "full_name": "United States of America",
      "area": 9372610,
      "borders": ["CAN", "MEX"],
      "latitude": 39.443256,
      "longitude": -98.95734,
      "max_latitude": 71.441055,
      "max_longitude": -66.885414,
      "min_latitude": 17.831509,
      "min_longitude": -179.23108,
      "currency": [{
          "code": "USD",
          "name": ""
       }, {
          "code": "USN",
          "name": ""
       }, {
          "code": "USS",
          "name": ""
      }],
      "continent": {
        "code": "",
        "name": "North America",
        "sub_region": ""
      }
    }
  }
}
```

## Support
If you encounter any problems or if you find a bug, please don't hesitate to create a new [issue](https://github.com/Webklex/php-geoip/issues).
However please be aware that it might take some time to get an answer.
Off topic, rude or abusive issues will be deleted without any notice.

If you need **immediate** or **commercial** support, feel free to send me a mail at github@webklex.com. 


##### A little notice
If you write source code in your issue, please consider to format it correctly. This makes it so much nicer to read 
and people are more likely to comment and help :)

&#96;&#96;&#96; php

echo 'your php code...';

&#96;&#96;&#96;

will turn into:
```php
echo 'your php code...';
```


### Features & pull requests
Everyone can contribute to this project. Every pull request will be considered but it can also happen to be declined. 
To prevent unnecessary work, please consider to create a [feature issue](https://github.com/Webklex/php-geoip/issues/new?template=feature_request.md) 
first, if you're planning to do bigger changes. Of course you can also create a new [feature issue](https://github.com/Webklex/php-geoip/issues/new?template=feature_request.md)
if you're just wishing a feature ;)


## Change log
Please see [CHANGELOG][link-changelog] for more information what has changed recently.


## Security
If you discover any security related issues, please email github@webklex.com instead of using the issue tracker.


## Credits
- [Webklex][link-author]
- [All Contributors][link-contributors]


## License
The MIT License (MIT). Please see [License File][link-license] for more information.


[ico-version]: https://img.shields.io/packagist/v/Webklex/php-geoip.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Webklex/php-geoip/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Webklex/php-geoip.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Webklex/php-geoip.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/Webklex/php-geoip.svg?style=flat-square
[ico-build]: https://img.shields.io/scrutinizer/build/g/Webklex/php-geoip/master?style=flat-square
[ico-quality]: https://img.shields.io/scrutinizer/quality/g/Webklex/php-geoip/master?style=flat-square
[ico-hits]: https://hits.webklex.com/svg/webklex/php-geoip

[link-packagist]: https://packagist.org/packages/Webklex/php-geoip
[link-travis]: https://travis-ci.org/Webklex/php-geoip
[link-scrutinizer]: https://scrutinizer-ci.com/g/Webklex/php-geoip/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Webklex/php-geoip
[link-downloads]: https://packagist.org/packages/Webklex/php-geoip
[link-author]: https://github.com/webklex
[link-contributors]: https://github.com/Webklex/php-geoip/graphs/contributors
[link-license]: https://github.com/Webklex/php-geoip/blob/master/LICENSE
[link-changelog]: https://github.com/Webklex/php-geoip/blob/master/CHANGELOG.md
[link-jetbrains]: https://www.jetbrains.com
[link-hits]: https://hits.webklex.com
