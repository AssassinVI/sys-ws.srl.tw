<?php 
 require __DIR__ . '/google-api-php-client-2.2.2/vendor/autoload.php';
 require '../../core/inc/config.php';
 require '../../core/inc/function.php';
 require '../../core/inc/pdo_fun_calss.php';

 $analytics = initializeAnalytics();



 
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


 
 function getReport($VIEW_ID, $analytics, $StartDate, $EndDate='today', $Metrics, $Dimensions='') {

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



if ($_POST) {

   $pdo=new PDO_fun;
	
  //------------- 日期區間 來源流量 ---------------
  if($_POST['type']=='date_src_num'){

     $row=$pdo->select("SELECT google_view_code FROM build_case WHERE Tb_index=:Tb_index", ['Tb_index'=>$_POST['Tb_index']], 'one');

  	 $param=[];
     $response = getReport($row['google_view_code'], $analytics, $_POST['StartDate'], $_POST['EndDate'], 'sessions', 'sourceMedium');
     $param['src']=printResults($response);

     $pdo->update('google_analytics', $param, ['Tb_index'=>$_POST['Tb_index']]);

  }
}


?>