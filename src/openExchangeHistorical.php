<?php

require 'vendor/autoload.php'; // Include Composer's autoloader
require 'AbstractPipeline.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use schemas\Exchange;
use wrappers\OpenExchangeWrapper;

class openExchangeHistorical extends AbstractPipeline
{

    public function extract($opts): array
    {
        $start_date = $opts["start_date"];
        $end_date = $opts["end_date"];
        $app_id = $opts["app_id"];
        $openExchange = new OpenExchangeWrapper();

        $records = array();

        if ($end_date == null) {
            $strDate = date_format($start_date, "Y-m-d");
            $records = $openExchange->get_historical_data($app_id, $strDate);
        } else {
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start_date, $interval, $end_date);

            foreach ($period as $dt) {
                $strDate = date_format($dt, "Y-m-d");

                $records[] = $openExchange->get_historical_data($app_id, $strDate);
            }
        }
        return $records;
    }

    public function transform($data, $opts) : array
    {
        // Check if the data is a single record or an array of records
        $rates = array();
        if ($opts["end_date"] == null) {
            $rates = $this->process($data);
        } else {
            foreach ($data as $record) {
                $rates[] = $this->process($record);
            }
            $rates = array_merge(...$rates);
        }

        return $rates;
    }

    private function process($record) {
        $euro_rate = $record["rates"]["EUR"];
        $date = $this->timestamp_to_datetime($record["timestamp"]);
        $rates = array();

        foreach ($record["rates"] as $symbol => $rate) {
            $symbolRate = $rate / $euro_rate;
            $rates[] = new Exchange($symbol, $date, $symbolRate);
        }
        return $rates;
    }

    private function timestamp_to_datetime($strDate): \DateTime
    {
        $time = date("Y-m-d", $strDate);
        return new DateTime($time);
    }
}
