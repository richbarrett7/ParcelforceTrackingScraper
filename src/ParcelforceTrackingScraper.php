<?PHP

namespace richbarrett\ParcelforceTrackingScraper;

class ParcelforceTrackingScraper {
  
  var $url;
  
  function __construct($trackingNumber) {
    
    $this->url = 'https://www.parcelforce.com/track-trace?trackNumber='.$trackingNumber;
    
  }
  
  function getEvents() {
    
    $response = $this->curl_get_data($this->url);
        
    if($response['responseCode'] != 200) {
      throw new \Exception('Response code is '.$response['responseCode']);
    }
    
    if(strpos($response['data'],'Invalid tracking number') !== false) {
      throw new \Exception('Invalid tracking number');
    }
    
    if(strpos($response['data'],'currently unable to confirm the status of your parcel') !== false) {
      throw new \Exception('No events available yet');
    }
    
    preg_match_all('/td class="tracking-history-date">([0-9\/]+)</', $response['data'], $dates);
    $dates = $dates[1];
    
    preg_match_all('/td class="tracking-history-time">([0-9\:]+)</', $response['data'], $times);
    $times = $times[1];
    
    preg_match_all('/td class="tracking-history-location">([^<]+)</', $response['data'], $locations);
    $locations = $locations[1];
    
    preg_match_all('/td class="tracking-history-desc">(.+)<\/td/', $response['data'], $descriptions);
    $descriptions = $descriptions[1];
    
    // Combine it all
    foreach($dates as $key => $v) {
      
      $ret[$key]['date'] = explode('/',$dates[$key])[2].'-'.explode('/',$dates[$key])[1].'-'.explode('/',$dates[$key])[0];
      $ret[$key]['time'] = $times[$key].':00';
      $ret[$key]['datetime'] = date('Y-m-d H:i:s', strtotime($ret[$key]['date'].' '.$ret[$key]['time']));
      $ret[$key]['location'] = $locations[$key];
      $ret[$key]['description'] = strip_tags($descriptions[$key]);
      
    }
    
    return $ret;
    
  }
  
  function findElementByClass($class,$html) {
    
     // images
      preg_match_all('/class="'.$class.'">(.+)<\/td>/', $html, $matches);
      
      return $matches[1];
  
  }
  
  function curl_get_data($url) {

  	$ch = curl_init();
  	$timeout = 10;
  	curl_setopt($ch, CURLOPT_URL, $url);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

  	$data = curl_exec($ch);
  	$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
  	$responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

  	return [ 'data'=>$data,'contentType'=>$contentType,'responseCode'=>$responseCode ];

  }
  
}

?>