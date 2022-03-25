<?php

// Load the Google API PHP Client Library.
require __DIR__ . '/google-api-php-client-2.2.2/vendor/autoload.php';
require '../../core/inc/config.php';
require '../../core/inc/function.php';

$analytics = initializeAnalytics();

$pdo=pdo_conn();
  $sql=$pdo->prepare("SELECT google_view_code, Tb_index FROM build_case WHERE google_view_code!=''");
  $sql->execute();
  while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {

  	$param=all_Analytics( $row['google_view_code'], $analytics);

  	$row_an=pdo_select("SELECT COUNT(*) as total FROM test_analytics WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['Tb_index']]);

  	if ($row_an['total']>0) {
      $param['set_time']=date('Y-m-d H:i:s');
  		pdo_update('test_analytics', $param, ['Tb_index'=>$row['Tb_index']]);
  	}
  	else{

  	   $param['Tb_index']=$row['Tb_index'];
  	   $param['set_time']=date('Y-m-d H:i:s');
       pdo_insert('test_analytics', $param);
  	}

    //-- 暫停X秒 --
    sleep(25);
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
  $KEY_FILE_LOCATION = 'rwd_sys_key-8284266ea9b1.json';

  // Create and configure a new client object.
  $client = new Google_Client();
  $client->setApplicationName("Hello Analytics Reporting");
  $client->setAuthConfig($KEY_FILE_LOCATION);
  //-- 設定API功能範圍 --
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

function getReport($VIEW_ID, $analytics, $StartDate, $Metrics, $Dimensions='') {

  // Replace with your view ID, for example XXXX.
  // Create the DateRange object.
  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
  $dateRange->setStartDate($StartDate);
  $dateRange->setEndDate("today");

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

  $type='';
  $data='';
  for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
    $report = $reports[ $reportIndex ];
    $header = $report->getColumnHeader();
    $dimensionHeaders = $header->getDimensions();
    $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
    $rows = $report->getData()->getRows();
    
    //-- 分類 --
    for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
      $row = $rows[ $rowIndex ];
      $dimensions = $row->getDimensions();
      $metrics = $row->getMetrics();
      for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
       // print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
        $type.=$dimensions[$i].',';
      }
      
       //-- 分類的值 --
      for ($j = 0; $j < count($metrics); $j++) {
        $values = $metrics[$j]->getValues();
        for ($k = 0; $k < count($values); $k++) {
          $entry = $metricHeaders[$k];
          //print($entry->getName() . ": " . $values[$k] . "\n");
          $data.=$values[$k].',';
        }
      }
    }
  }

  if (!empty($type)) {
   return substr($type, 0,-1).'|'.substr($data, 0,-1);
  }
  else{
    return substr($data, 0,-1);
  }
}


function all_Analytics($VIEW_ID, $analytics)
{
  $param=[];

  $response = getReport($VIEW_ID, $analytics, '7daysAgo', 'sessions');
  $param['week_user']=printResults($response);

  $response = getReport($VIEW_ID, $analytics, '30daysAgo', 'sessions');
  $param['month_user']=printResults($response);

  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'sessions');
  $param['total_user']=printResults($response);

  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'sessions', 'userGender');
  $param['sex']=printResults($response);
 
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'sessions', 'userAgeBracket');
  $param['years']=printResults($response);
  
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'sessions', 'deviceCategory');
  $param['media']=printResults($response);

  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'uniqueEvents', 'eventCategory');
  $param['event']=printResults($response);

  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'sessions', 'sourceMedium');
  $param['src']=printResults($response);

  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'sessions', 'region');
  $param['city']=printResults($response);

  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'avgTimeOnPage', 'userAgeBracket');
  $param['timeOnSite_years']=printResults($response);

  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'sessions', 'date');
  $param['user_date']=printResults($response);

  return $param;
}
