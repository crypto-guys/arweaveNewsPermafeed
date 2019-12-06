<?php
/**
 * permaNewsfeed archives news headlines from newsApi.org
 *
 * @category
 * @package  permaNewsFeed
 * @author   crypto-guys
 * @license  MIT
 * @link
 *
 */
$keyword = trim(file_get_contents('/arweaveNewsPermafeed/keyword.txt'));
$apiKey = trim(file_get_contents('/arweaveNewsPermafeed/apiKey.txt'));
$schedule = trim(file_get_contents('/arweaveNewsPermafeed/schedule.txt'));
$pagesize = trim(file_get_contents('/arweaveNewsPermafeed/pagesize.txt'));
$settings = [
    'keyword' => $keyword,
    'apiKey' => $apiKey,
    'pagesize' => $pagesize,
    'schedule' => $schedule, // 1 = hourly 2 = twice daily 3 = daily
];

require __DIR__ . '/vendor/autoload.php';

// Create the source URL.
$baseUrl = 'https://newsapi.org/v2/everything?';

// Date Time format is 2019-12-03T08:42:11
// Time an hour ago (for hourly run)
$addT = 'T';
$hourly_time = new DateTime(); // time right now
$hourly_time->sub(new DateInterval('P0Y0M0DT1H0M0S'));
$hourago_date = $hourly_time->format('Y-m-d');
$hourago_time = $hourly_time->format('H:i:s');
$hourly_formatted_time = $hourago_date . $addT . $hourago_time;

// Time 12 hours ago (for 2x daily run)
$halfday_time = new DateTime(); // time right now
$halfday_time->sub(new DateInterval('P0Y0M0DT12H0M0S'));
$halfday_date = $halfday_time->format('Y-m-d');
$halfday_time = $halfday_time->format('H:i:s');
$halfday_formatted_time = $halfday_date . $addT . $halfday_time;

// Time 1 day ago (for daily run)
$daily = new DateTime(); // time right now
$daily->sub(new DateInterval('P0Y0M1DT0H0M0S'));
$daily_date = $daily->format('Y-m-d');
$daily_time = $daily->format('H:i:s');
$daily_formatted_time = $daily_date . $addT . $daily_time;
$schedule = $settings['schedule'];

if ($schedule = '1') {
  $schedule_time = $hourly_formatted_time;
  } elseif ($schedule = '2') {
    $schedule_time = $halfday_formatted_time;
  } else {
    $schedule_time = $daily_formatted_time;
  }


/**
  * Make the source url
  *
  */
  $pagesize = $settings['pagesize'];
  $apiKey   = $settings['apiKey'];
  $keyword  = $settings['keyword'];
  $qs = [
       'apiKey'    => $apiKey,
       'q'         => $keyword,
       'pagesize'  => $pagesize,
       'from'      => '',
  ];
  $query    = http_build_query($qs);
  $makeurl  = ($baseUrl . $query . $schedule_time);
  //echo $makeurl;

/**
  * Use Curl to retrieve data from API source. 
  */
  $curl = curl_init();
  if (!$curl) {
      die("Couldn't initialize a cURL handle");
  }
curl_setopt($curl, CURLOPT_URL, $makeurl);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($curl, CURLOPT_FAILONERROR, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
curl_setopt($curl, CURLOPT_TIMEOUT, 50);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($curl);
if (curl_errno($curl))
{
    echo 'cURL error: ' . curl_error($curl);
}
curl_close($curl);
$decoded_response = json_decode($response, true);
$total_results = $decoded_response['totalResults'];

/**
  * Validate data
  */
  if (!is_array($decoded_response)) {
     die('Curl Response was invalid');
  }
  if ($total_results = "0") {
	die('Error: No articles returned by api');
  }

/**
  * Initialize the wallet - Create Transaction - Send Transaction - Log Transaction
  */
  $arweave = new \Arweave\SDK\Arweave('https', 'arweave.net', '443');
  $wallet_file = file_get_contents('/arweaveNewsPermafeed/jwk.json');
  $jwk = json_decode($wallet_file, true);
  $wallet = new \Arweave\SDK\Support\Wallet($jwk);
  $transaction = $arweave->createTransaction($wallet, [
        'data' => $response,
        'tags' => [
            'Content-Type' => 'application/json',
            'App-Name' => 'permaNewsfeed',
            'keyword' => $keyword,
            'Date' => $schedule_time,
        ]
    ]);

  $logFile = "/arweaveNewsPermafeed/transaction.log";
  $transactionId = $transaction->getAttribute('id');
  file_put_contents($logFile, PHP_EOL . $transactionId, FILE_APPEND);

 $arweave->api()->commit($transaction);



