<?php

//-------------------------------- 自訂獲取專案分析(所有時間) ------------------------------------

// Load the Google API PHP Client Library.
require __DIR__ . '/google-api-php-client-2.2.2/vendor/autoload.php';
require '../../core/inc/config.php';
require '../../core/inc/function.php';
require '../../core/inc/pdo_fun_calss.php';

$analytics = initializeAnalytics();

  // $pdo=pdo_conn();
  // $sql=$pdo->prepare("SELECT google_view_code, Tb_index, StartDate FROM build_case 
  //                     WHERE Tb_index IN ('case2021123009374970') AND google_view_code!='' AND is_auto_an=1 ORDER BY StartDate DESC");
  // $sql->execute();
  while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
    

    //-- 舊的 --
  	// $param=all_Analytics( $row['google_view_code'], $analytics);

  	// $row_an=pdo_select("SELECT COUNT(*) as total FROM google_analytics WHERE Tb_index=:Tb_index", ['Tb_index'=>$row['Tb_index']]);

  	// if ($row_an['total']>0) {
    //   $param['set_time']=date('Y-m-d H:i:s');
  	// 	pdo_update('google_analytics', $param, ['Tb_index'=>$row['Tb_index']]);
  	// }
  	// else{

  	//    $param['Tb_index']=$row['Tb_index'];
  	//    $param['set_time']=date('Y-m-d H:i:s');
    //    pdo_insert('google_analytics', $param);
  	// }

    // //-- 暫停X秒 --
    // sleep(25);
    
    



    //-- 新的 --
    //an_user($row['google_view_code'] , $analytics, $row['Tb_index'], date('Y-m-d',strtotime('-1 day')));

    $StartDate=strtotime('2022-01-10');
    $today=strtotime(date('Y-m-d'));
    $an=($today-$StartDate)/(60*60*24);

    
      for ($i=0; $i <$an ; $i++) { 
        $set_time=$StartDate+($i*60*60*24);
        
        an_user($row['google_view_code'] , $analytics, $row['Tb_index'], date('Y-m-d', $set_time));

        an_userType($row['google_view_code'] , $analytics, $row['Tb_index'], date('Y-m-d', $set_time));
        an_userCount($row['google_view_code'] , $analytics, $row['Tb_index'], date('Y-m-d', $set_time));
        an_dayLast($row['google_view_code'] , $analytics, $row['Tb_index'], date('Y-m-d', $set_time));
        an_src_time($row['google_view_code'] , $analytics, $row['Tb_index'], date('Y-m-d', $set_time));

        an_bounceRate($row['google_view_code'], $analytics, $row['Tb_index'], date('Y-m-d', $set_time));

        an_years($row['google_view_code'], $analytics, $row['Tb_index'], date('Y-m-d', $set_time) );
        an_sex($row['google_view_code'], $analytics, $row['Tb_index'], date('Y-m-d', $set_time) );
        an_city($row['google_view_code'], $analytics, $row['Tb_index'], date('Y-m-d', $set_time) );
        an_event($row['google_view_code'], $analytics, $row['Tb_index'], date('Y-m-d', $set_time) );
        an_media($row['google_view_code'], $analytics, $row['Tb_index'], date('Y-m-d', $set_time) );
        an_src($row['google_view_code'], $analytics, $row['Tb_index'], date('Y-m-d', $set_time) );
        an_timeOnSite($row['google_view_code'], $analytics, $row['Tb_index'], date('Y-m-d', $set_time) );

        //-- 暫停X秒 --
        sleep(1);
      }
    
    
    
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

function an_user($VIEW_ID, $analytics, $case_id, $set_time )
{
  //---- 每天使用人數 ----
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'sessions');
  $result=printResults($response);

       $param=[
         //'Tb_index'=>'an'.date('YmdHis').rand(0,99),
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'one_user'=>$result
       ];
       pdo_insert('an_user', $param);

}



//-- 新訪者/回訪者 --
function an_userType($VIEW_ID, $analytics, $case_id, $set_time)
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'sessions', 'userType');
  $result=printResults($response);
  $result=explode('|', $result);
  $type=explode(',', $result[0]);
  $one_data=explode(',', $result[1]);
  $type_num=count($type);

  for ($i=0; $i <$type_num ; $i++){
    $param=[
      //'Tb_index'=>'an'.date('YmdHis').$i,
      'case_id'=>$case_id,
      'set_time'=>date('Y-m-d H:i:s'),
      'date'=>$set_time,
      'userType'=>$type[$i],
      'one_user'=>$one_data[$i]
    ];
    pdo_insert('an_userType', $param);
  }
}



//-- 回訪次數-人數 --
function an_userCount($VIEW_ID, $analytics, $case_id, $set_time)
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'sessions', 'sessionCount');
  $result=printResults($response);
  $result=explode('|', $result);
  $type=explode(',', $result[0]);
  $one_data=explode(',', $result[1]);
  $type_num=count($type);

  for ($i=0; $i <$type_num ; $i++){
    $param=[
      //'Tb_index'=>'an'.date('YmdHis').$i,
      'case_id'=>$case_id,
      'set_time'=>date('Y-m-d H:i:s'),
      'date'=>$set_time,
      'count_num'=>$type[$i],
      'one_user'=>$one_data[$i]
    ];
    pdo_insert('an_userCount', $param);
  }
}


//-- 上次訪問網站以來經過的天數 --
function an_dayLast($VIEW_ID, $analytics, $case_id, $set_time)
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'sessions', 'daysSinceLastSession');
  $result=printResults($response);
  $result=explode('|', $result);
  $type=explode(',', $result[0]);
  $one_data=explode(',', $result[1]);
  $type_num=count($type);

  for ($i=0; $i <$type_num ; $i++){
    $param=[
      //'Tb_index'=>'an'.date('YmdHis').$i,
      'case_id'=>$case_id,
      'set_time'=>date('Y-m-d H:i:s'),
      'date'=>$set_time,
      'lastDay'=>$type[$i],
      'one_user'=>$one_data[$i]
    ];
    pdo_insert('an_dayLast', $param);
  }
}


function an_years($VIEW_ID, $analytics, $case_id, $set_time )
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'sessions', 'userAgeBracket');
  $result=printResults($response);
  $result=explode('|', $result);
  $years_type=explode(',', $result[0]);
  $one_years=explode(',', $result[1]);
  $years_type_num=count($years_type);

  for ($i=0; $i <$years_type_num ; $i++) { 
    $param=[
         //'Tb_index'=>'an'.date('YmdHis').$i,
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'years_type'=>$years_type[$i],
         'one_years'=>$one_years[$i]
       ];
       pdo_insert('an_years', $param);
  }

}



function an_sex($VIEW_ID, $analytics, $case_id, $set_time )
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'sessions', 'userGender');
  $result=printResults($response);
  $result=explode('|', $result);
  $sex_type=explode(',', $result[0]);
  $one_sex=explode(',', $result[1]);
  $sex_type_num=count($sex_type);

  for ($i=0; $i <$sex_type_num ; $i++) { 
    $param=[
         //'Tb_index'=>'an'.date('YmdHis').$i,
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'sex_type'=>$sex_type[$i],
         'one_sex'=>$one_sex[$i]
       ];
       pdo_insert('an_sex', $param);
  }

}


function an_city($VIEW_ID, $analytics, $case_id, $set_time )
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'sessions', 'region');
  $result=printResults($response);
  $result=explode('|', $result);
  $city_type=explode(',', $result[0]);
  $one_city=explode(',', $result[1]);
  $city_type_num=count($city_type);

  for ($i=0; $i <$city_type_num ; $i++) { 
    $param=[
         //'Tb_index'=>'an'.date('YmdHis').$i,
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'city_type'=>$city_type[$i],
         'one_city'=>$one_city[$i]
       ];
       pdo_insert('an_city', $param);
  }

}


function an_event($VIEW_ID, $analytics, $case_id, $set_time )
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'uniqueEvents', 'eventCategory');
  $result=printResults($response);
  $result=explode('|', $result);
  $event_type=explode(',', $result[0]);
  $one_event=explode(',', $result[1]);
  $event_type_num=count($event_type);

  for ($i=0; $i <$event_type_num ; $i++) { 
    $param=[
         //'Tb_index'=>'an'.date('YmdHis').$i,
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'event_type'=>$event_type[$i],
         'one_event'=>$one_event[$i]
       ];
       pdo_insert('an_event', $param);
  }

}


//-- 跳出率 --
function an_bounceRate($VIEW_ID, $analytics, $case_id, $set_time )
{
  //---- 每天使用人數 ----
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'bounceRate');
  $result=printResults($response);

       $param=[
         //'Tb_index'=>'an'.date('YmdHis').rand(0,99),
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'one_bounceRate'=>$result
       ];
       pdo_insert('an_BounceRate', $param);

}


function an_media($VIEW_ID, $analytics, $case_id, $set_time )
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'sessions', 'deviceCategory');
  $result=printResults($response);
  $result=explode('|', $result);
  $media_type=explode(',', $result[0]);
  $one_media=explode(',', $result[1]);
  $media_type_num=count($media_type);

  for ($i=0; $i <$media_type_num ; $i++) { 
    $param=[
         //'Tb_index'=>'an'.date('YmdHis').$i,
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'media_type'=>$media_type[$i],
         'one_media'=>$one_media[$i]
       ];
       pdo_insert('an_media', $param);
  }

}



function an_src($VIEW_ID, $analytics, $case_id, $set_time )
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'sessions', 'sourceMedium');
  $result=printResults($response);
  $result=explode('|', $result);
  $src_type=explode(',', $result[0]);
  $one_src=explode(',', $result[1]);
  $src_type_num=count($src_type);

  for ($i=0; $i <$src_type_num ; $i++) { 
    $param=[
         //'Tb_index'=>'an'.date('YmdHis').$i,
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'src_type'=>$src_type[$i],
         'one_src'=>$one_src[$i]
       ];
       pdo_insert('an_src', $param);
  }

}



//-- 流量來源(平均使用時間) --
function an_src_time($VIEW_ID, $analytics, $case_id, $set_time )
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'avgSessionDuration', 'sourceMedium');
  $result=printResults($response);
  $result=explode('|', $result);
  $src_type=explode(',', $result[0]);
  $one_src=explode(',', $result[1]);
  $src_type_num=count($src_type);

  for ($i=0; $i <$src_type_num ; $i++) { 
    $param=[
         //'Tb_index'=>'an'.date('YmdHis').$i,
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'src_type'=>$src_type[$i],
         'one_time'=>$one_src[$i]
       ];
       pdo_insert('an_src_time', $param);
  }
}



function an_timeOnSite($VIEW_ID, $analytics, $case_id, $set_time )
{
  $response = getReport($VIEW_ID, $analytics, date('Y-m-d',strtotime($set_time)), date('Y-m-d',strtotime($set_time)), 'avgTimeOnPage', 'userAgeBracket');
  $result=printResults($response);
  $result=explode('|', $result);
  $years_type=explode(',', $result[0]);
  $one_timeOnSite=explode(',', $result[1]);
  $years_type_num=count($years_type);

  for ($i=0; $i <$years_type_num ; $i++) { 
    $param=[
         //'Tb_index'=>'an'.date('YmdHis').$i,
         'case_id'=>$case_id,
         'date'=>$set_time,
         'set_time'=>date('Y-m-d H:i:s'),
         'years_type'=>$years_type[$i],
         'one_timeOnSite'=>$one_timeOnSite[$i]
       ];
       pdo_insert('an_timeOnSite', $param);
  }
}



function all_Analytics($VIEW_ID, $analytics)
{
  $param=[];
  //---- 每周使用人數 ----
  $response = getReport($VIEW_ID, $analytics, '7daysAgo', 'today','sessions');
  $param['week_user']=printResults($response);
  //---- 每月使用人數 ----
  $response = getReport($VIEW_ID, $analytics, '30daysAgo', 'today','sessions');
  $param['month_user']=printResults($response);
  //---- 總使用人數 ----
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'today','sessions');
  $param['total_user']=printResults($response);

  //---- 性別 ----
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'today','sessions', 'userGender');
  $param['sex']=printResults($response);
  //---- 年齡 ----
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'today','sessions', 'userAgeBracket');
  $param['years']=printResults($response);
  //---- 媒體 ----
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'today','sessions', 'deviceCategory');
  $param['media']=printResults($response);
  //---- 熱門事件點擊 ----
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'today','uniqueEvents', 'eventCategory');
  $param['event']=printResults($response);
  //---- 流量來源 ----
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'today','sessions', 'sourceMedium');
  $param['src']=printResults($response);
  //---- 月流量來源 ----
  $response = getReport($VIEW_ID, $analytics, '30daysAgo', 'today','sessions', 'sourceMedium');
  $param['month_src']=printResults($response);
  //---- 地區 ----
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'today','sessions', 'region');
  $param['city']=printResults($response);
  //---- 網站停留時間-年齡層 ----
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'today','avgTimeOnPage', 'userAgeBracket');
  $param['timeOnSite_years']=printResults($response);
  //---- 每日瀏覽人數 ----
  $response = getReport($VIEW_ID, $analytics, '2016-04-01', 'today','sessions', 'date');
  $param['user_date']=printResults($response);

  return $param;
}