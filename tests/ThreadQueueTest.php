<?php

use PHPUnit\Framework\TestCase;
use Jinraynor1\Threading\Pcntl\ThreadQueue;

class ThreadQueueTest extends TestCase
{

    public function testSumResults()
    {


        $queue = new ThreadQueue(function ($number) {
            return pow($number, 2);

        });

        $queue->enableMessaging(true);


        $queue->add(1);
        $queue->add(3);


        $queue->wait();
        $results = $queue->results();
        $sum = array_sum($results);
        $this->assertEquals(10, $sum);


    }

    public function testManyThreads()
    {
        $queue = new ThreadQueue(function ($number) {
            return $number;

        }, 20);

        $queue->enableMessaging(true);


        for ($i = 0; $i <= 100; $i++ ) {
            $queue->add($i);
        }


        $queue->wait();
        $results = $queue->results();
        $sum = array_sum($results);


        $this->assertEquals(5050, $sum);

    }
}




