# Quotable API

[![Latest Version](https://img.shields.io/github/release/defro/quotable-php.svg?style=flat-square)](https://github.com/defro/quotable-php/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/defro/quotable-php/master.svg?style=flat-square)](https://travis-ci.org/defro/quotable-php)
[![SymfonyInsight](https://insight.symfony.com/projects/4cf70d48-ef8c-49d1-842f-d0d093d5df63/mini.svg)](https://insight.symfony.com/projects/bb6b7848-7e7a-4e9f-a25b-397369caeef5)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/defro/quotable-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/defro/quotable-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/defro/quotable-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/defro/quotable-php/?branch=master)
[![StyleCI](https://styleci.io/repos/156726302/shield)](https://styleci.io/repos/156726302)
[![Total Downloads](https://img.shields.io/packagist/dt/defro/quotable-php.svg?style=flat-square)](https://packagist.org/packages/defro/quotable-php)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MSER6KJHQM9NS)

This package can get quotes and their authors using [Quotable](https://github.com/lukePeavey/quotable). Here's a quick example:

```php
$client = new \GuzzleHttp\Client();
$quotable = new \Defro\Quotable\Api($client);
$random = $quotable
    ->getRandomQuote();

var_dump($random);
```

## Documentation

Read how to install, use this package, customize it on [documentation page](https://defro.github.io/quotable-php/).

## License

The MIT License (MIT). Please see [license file](LICENSE) for more information.
