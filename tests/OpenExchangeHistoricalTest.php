<?php

use PHPUnit\Framework\TestCase;
use \wrappers\OpenExchangeWrapper;

require __DIR__ . '/../src/Sqlite.php';
require __DIR__ . '/../src/openExchangeHistorical.php';
require __DIR__ . '/../bootstrap.php';

class DatabaseWorkerTest implements DatabaseWorker
{
    function save($data)
    {
        echo "saving data...\n";
        return $data;
    }
}

class OpenExchangeHistoricalTest extends TestCase
{
    public function testExtractSingleDate()
    {

        $openExchangeWrapperMock = $this->getMockBuilder(OpenExchangeWrapper::class)
            ->onlyMethods(['get_historical_data'])
            ->getMock();

        // Define the expected start date
        $startDate = new DateTime('2023-05-01');

        // Set up the expectation for the get_historical_data method
        $openExchangeWrapperMock->expects($this->once())
            ->method('get_historical_data')
            ->with('test_app_id', '2023-05-01')
            ->willReturn(['date' => '2023-05-01', 'value' => 123]);

        // Prepare options for the extract method
        $opts = [
            'start_date' => $startDate,
            'end_date' => null,
            'app_id' => 'b95b8a2a95c6459fac19324564dbc0a7',
        ];

        // Create an instance of the class you're testing, injecting the mock
        $pipeline = new OpenExchangeHistorical($opts, new DatabaseWorkerTest([]));

        // Call the extract method
        $result = $pipeline->extract($opts);

        // Assert the result
        $this->assertEquals([['date' => '2023-05-01', 'value' => 123]], $result);
    }

}
