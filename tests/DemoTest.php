<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DemoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->demo();
        $this->assertTrue(true);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function demo()
    {
        $param = [
            "open_app_id" => "200633114",
            "is_first_subscribe" => 0,
            "nickname" => "Greenny",
            "ticket" => "",
            "open_id" => "C7B496768F2D532CBF4DF069AAC1DD1D",
            "event_key" => "",
            "app_id" => 17
        ];
        echo http_build_query($param);
    }
}
