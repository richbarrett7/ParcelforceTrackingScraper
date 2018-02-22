# Introduction
This scrapes Parcelforce tracking events from their website because they do not provide an official tracking API.

# Installation
`composer require richbarrett/parcelforce-tracking-scraper`

# Usage
```php
include_once('vendor/autoload.php');

try {
  
  $trackingNumber = 'AB1234567';
  
  $scraper = new \richbarrett\ParcelforceTrackingScraper\ParcelforceTrackingScraper($trackingNumber);
  $events = $scraper->getEvents();

  print_r($events);

} catch(Exception $e) {
  
  die('Error: '.$e->getMessage());
  
}
```

# Health Warning
Obviously Parcelforce could change their tracking webpage at any time resulting in this breaking, so bear that in mind if you use it in your application.
