<?php

class SimpleGoogleAnalytics {

  private $client;
  private $service;
  private $client_id;
  private $key_file;

  function __construct($client_id, $client_mail, $product_name, $key_file) {

    $this->client_id = $client_id;
    $this->key_file = $key_file;

    $this->client = new Google_Client();
    $this->client->setApplicationName($product_name); // name of your app
    
    $this->client->setAssertionCredentials(
            new Google_AssertionCredentials(
                    $client_mail, // email you added to GA
                    array('https://www.googleapis.com/auth/analytics.readonly'),
                    file_get_contents($key_file)  // keyfile you downloaded
    ));
    

    $this->client->setClientId($this->client_id);           // from API console
    $this->client->setAccessType('offline_access');  // this may be unnecessary?

    $this->service = new Google_AnalyticsService($this->client);
  }

  function getAccount() {

    $results = $this->service->management_profiles
                 ->listManagementProfiles('~all', '~all');
    
    
  }
  
  
  function getGeneralData($profile_id, $start_date = NULL) {
    
    $metrics_array = array('ga:visitors','ga:pageviews','ga:visits','ga:bounces','ga:entranceBounceRate','ga:visitBounceRate','ga:avgTimeOnSite');
    $metrics = implode(',', $metrics_array);
    
    
    $dimensions_array = array('ga:year','ga:month','ga:day');
    $dimensions = implode(',', $dimensions_array);

    $columns = array_merge($dimensions_array, $metrics_array);
    
    
    if ($start_date == NULL) $start_date = date('Y-m-d');
    
    $data = $this->service->data_ga->get('ga:'.$profile_id, $start_date, $start_date, $metrics, array('dimensions' => $dimensions));
    
    if ($data != NULL ) {
   
      $ret_array = array(
          'visits'      => $data['totalsForAllResults']['ga:visits'],
          'visitors'    => $data['totalsForAllResults']['ga:visitors'],
          'pageviews'   => $data['totalsForAllResults']['ga:pageviews']
      );
      
      return $ret_array;
      
    } else {
      
      return false;
      
    }
    
  }

}

class SimpleGoogleAnalyticsResult{
  
  private $raw_data;
  
  private $data;
  
  function __construct($columns, $raw_data) {
    
    $this->raw_data = $raw_data;
    
    foreach($this->raw_data as $row) {
      
      
      
      
    }
    
  }
  
  function get($day, $metric) {
    
    return $this->raw_data;
    
  }
  
  
}

?>