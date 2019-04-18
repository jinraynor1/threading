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


        $queue->add(1);
        $queue->add(3);


        $queue->wait();
        $results = $queue->results();
        $sum = array_sum($results);
        $this->assertEquals(10, $sum);


    }
}




