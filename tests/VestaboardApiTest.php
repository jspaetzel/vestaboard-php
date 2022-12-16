<?php

namespace Tests;

use Vestaboard\VestaboardApi;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

class VestaboardApiTest extends TestCase
{
    public function setUp(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../.env');
        $dotenv->load(__DIR__ . '/../.env.local');
        $this->api = new VestaboardApi($_ENV['VESTABOARD_KEY'], $_ENV['VESTABOARD_SECRET'], $_ENV['VESTABOARD_SUBSCRIPTION']);
    }

    public function testUpdateBoardWithText(): void
    {
        $this->markTestSkipped('dont actually query the api');

        $this->api->updateWithText('Hello from John');
    }

    public function testUpdateBoardWithCharacters(): void
    {
        $this->markTestSkipped('dont actually query the api');

        $stopover_example = [
            [63, 63, 64, 64, 65, 65, 66, 66, 67, 67, 68, 68, 63, 63, 64, 64, 65, 65, 66, 66, 67, 67],
            [64, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 68],
            [65, 0, 0, 0, 0, 0, 23, 5, 12, 3, 15, 13, 5, 0, 20, 15, 0, 0, 0, 0, 0, 63],
            [66, 0, 0, 0, 0, 20, 8, 5, 0, 19, 20, 15, 16, 15, 22, 5, 18, 0, 0, 0, 0, 64],
            [67, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 65],
            [68, 68, 63, 63, 64, 64, 65, 65, 66, 66, 67, 67, 68, 68, 63, 63, 64, 64, 65, 65, 66, 66]
        ];

        $this->api->updateWithCharacters($stopover_example);
    }

    public function testLinesWithCharacters(): void
    {
        $this->markTestSkipped('dont actually query the api');

        $test_message = ['A single line!'];
        $characters = VestaboardApi::linesToCharacters($test_message);
        $this->api->updateWithCharacters($characters);
    }
}
