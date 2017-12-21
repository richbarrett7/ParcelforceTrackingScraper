<?PHP

include_once('src/ParcelforceTrackingScraper.php');

try {
  
  $trackingNumber = 'AB1234567';
  
  $scraper = new \richbarrett\ParcelforceTrackingScraper\ParcelforceTrackingScraper($trackingNumber);
  $events = $scraper->getEvents();

  print_r($events);

} catch(Exception $e) {
  
  die('Error: '.$e->getMessage());
  
}



?>