<?php
require __DIR__.'/config.php';
// Load the Google API PHP Client Library.
require __DIR__ . '/../../system/google_an/google-api-php-client-2.2.2/vendor/autoload.php';
//-- PDO --
require __DIR__ . '/pdo_fun_calss.php';

if($_POST){

  $pdo=new PDO_fun;

  $analytics = initializeAnalytics();
  $data=[];

  //---- 每周使用人數 ----
  $response = getReport($analytics, '7daysAgo', 'today', 'sessions');
  $Results=printResults($response);
  $Results['type']='week_user';
  array_push($data, $Results);
  //---- 每月使用人數 ----
  $response = getReport($analytics, '30daysAgo', 'today', 'sessions');
  $Results=printResults($response);
  $Results['type']='month_user';
  array_push($data, $Results);
  //---- 總使用人數 ----
  $response = getReport($analytics, '2016-04-01', 'today', 'sessions');
  $Results=printResults($response);
  $Results['type']='total_user';
  array_push($data, $Results);

  //-- 每日人數 --
  $response = getReport($analytics, $_POST['StartDate'], $_POST['EndDate'], 'sessions', 'date');
  $Results=printResults($response);
  for ($i=0; $i <count($Results['type']) ; $i++) { 
    $Results['type'][$i]=date('Y-m-d', strtotime($Results['type'][$i]));
  }
  array_push($data, $Results);

  //-- 使用者性別 --
  $response = getReport($analytics, $_POST['StartDate'], $_POST['EndDate'], 'sessions', 'userGender');
  $Results=printResults($response);
  for ($i=0; $i <count($Results['type']) ; $i++) { 
    $Results['type'][$i]= $Results['type'][$i]== 'female' ? '女性':'男性';
  }
  array_push($data, $Results);

  //-- 使用者年齡 --
  $response = getReport($analytics, $_POST['StartDate'], $_POST['EndDate'], 'sessions', 'userAgeBracket');
  $Results=printResults($response);
  array_push($data, $Results);

  //-- 地區使用人數 --
  $response = getReport($analytics, $_POST['StartDate'], $_POST['EndDate'], 'sessions', 'region');
  $Results=printResults($response);
  for ($i=0; $i <count($Results['type']) ; $i++) { 
    $Results['type'][$i]=str_replace(' City', '', $Results['type'][$i]);
    $row=$pdo->select("SELECT tw_name FROM taiwan_area WHERE en_name=:en_name", ['en_name'=>$Results['type'][$i]], 'one');
    
    if(!empty($row['tw_name'])){
      $Results['type'][$i]=$row['tw_name'];
    }
    else{
      //array_splice($Results['type'], $i, 1);
      //array_splice($Results['data'], $i, 1);
    }
  }
  array_push($data, $Results);

  //-- 使用的媒體 --
  $response = getReport($analytics, $_POST['StartDate'], $_POST['EndDate'], 'sessions', 'deviceCategory');
  $Results=printResults($response);
  for ($i=0; $i <count($Results['type']) ; $i++) { 
    switch ($Results['type'][$i]) {
      case 'desktop':
        $Results['type'][$i]='桌機';
      break;
      case 'mobile':
        $Results['type'][$i]='手機';
      break;
      case 'tablet':
        $Results['type'][$i]='平板';
      break;
    }
  }
  array_push($data, $Results);


  //-- 流量來源 --
  $response = getReport($analytics, $_POST['StartDate'], $_POST['EndDate'], 'sessions', 'sourceMedium');
  $Results=printResults($response);
  array_push($data, $Results);



  //-- 網頁來源 --
  $response = getReport($analytics, $_POST['StartDate'], $_POST['EndDate'], 'sessions', 'pagePath');
  $Results=printResults($response);
  array_push($data, $Results);


  echo json_encode($data);

  $pdo=NULL;
}


/**
 * Initializes an Analytics Reporting API V4 service object.
 *
 * @return An authorized Analytics Reporting API V4 service object.
 */
function initializeAnalytics()
{

  // Use the developers console and download your service account
  // credentials in JSON format. Place them in this directory or
  // change the key file location if necessary.
  $KEY_FILE_LOCATION = __DIR__ . '/../../system/google_an/rwd_sys_key-8284266ea9b1.json';

  // Create and configure a new client object.
  $client = new Google_Client();
  $client->setApplicationName("Hello Analytics Reporting");
  $client->setAuthConfig($KEY_FILE_LOCATION);
  $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
  $analytics = new Google_Service_AnalyticsReporting($client);

  return $analytics;
}


/**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 * @return The Analytics Reporting API V4 response.
 */
function getReport($analytics, $StartDate, $EndDate='today', $Metrics, $Dimensions='') {

   // Replace with your view ID, for example XXXX.
   $VIEW_ID = "247252132";

  // Replace with your view ID, for example XXXX.
  // Create the DateRange object.
  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
  $dateRange->setStartDate($StartDate);
  $dateRange->setEndDate($EndDate);

  // Create the Metrics object.
  $sessions = new Google_Service_AnalyticsReporting_Metric();
  $sessions->setExpression("ga:".$Metrics);
  $sessions->setAlias($Metrics);

  //Create the Dimensions object.
  if (!empty($Dimensions)) {
    $browser = new Google_Service_AnalyticsReporting_Dimension();
    $browser->setName("ga:".$Dimensions);
  }
  
  // Create the ReportRequest object.
  $request = new Google_Service_AnalyticsReporting_ReportRequest();
  $request->setViewId($VIEW_ID);
  $request->setDateRanges($dateRange);

  if (!empty($Dimensions)) {
    $request->setDimensions(array($browser));
  }
  
  $request->setMetrics(array($sessions));

  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
  $body->setReportRequests( array( $request) );
  return $analytics->reports->batchGet( $body );
}


/**
 * Parses and prints the Analytics Reporting API V4 response.
 *
 * @param An Analytics Reporting API V4 response.
 */
function printResults($reports) {

  $return=[
    'type'=>[],
    'data'=>[]
  ];

  for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
    $report = $reports[ $reportIndex ];
    $header = $report->getColumnHeader();
    $dimensionHeaders = $header->getDimensions();
    $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
    $rows = $report->getData()->getRows();

    for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
      $row = $rows[ $rowIndex ];
      $dimensions = $row->getDimensions();
      $metrics = $row->getMetrics();
      for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
        // print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
        // $type.=$dimensions[$i].',';
        array_push($return['type'], $dimensions[$i]);
      }

      for ($j = 0; $j < count($metrics); $j++) {
        $values = $metrics[$j]->getValues();
        for ($k = 0; $k < count($values); $k++) {
          $entry = $metricHeaders[$k];
          //print($entry->getName() . ": " . $values[$k] . "\n");
          //$data.=$values[$k].',';
          array_push($return['data'], $values[$k]);
        }
      }
    }
  }
  return $return;
}


?>