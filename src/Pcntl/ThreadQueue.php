<?php

namespace Jinraynor1\Threading\Pcntl;
/*
  Adds parallel tasks to a queue.
  tick() must be called regularly, that arranges addition of new jobs and removal of closed threads.
  */


class ThreadQueue
{

    const DEFAULT_QUEUE_SIZE = 2;        // default number of parallel tasks
    const TICK_DELAY = 10000;        // delay after tick, in millisecs
    const MESSAGE_LENGTH = 1024;

    private $callable;    // the function name to call. can be a method of a static class as well
    private $threads = array();    // Thread instances
    private $jobs = array();    // parameters to pass to $callable
    public $queueSize;        // number of parallel tasks. public, to make it variable run-time.
    private $results;
    private $message_length;
    /**
     * @var bool
     */
    private $enable_messaging;

    /**
     *    Constructor
     *
     * @param string $callable function name
     * @param integer $queueSize number of parallel tasks
     */

    public function __construct($callable,
                                $queueSize = ThreadQueue::DEFAULT_QUEUE_SIZE)
    {
        if (!is_callable($callable)) throw new \Exception("$callable is not callable.");
        $this->callable = $callable;
        $this->queueSize = $queueSize;

    }

    public function enableMessaging($enable_messaging = false,
                                    $message_length = ThreadQueue::MESSAGE_LENGTH)
    {
        $this->message_length = $message_length;
        $this->enable_messaging = $enable_messaging;
    }


    /**
     *    Add a new job
     *
     * @param mixed $argument parameter to pass to $callable
     * @return int    queue size
     */

    public function add()
    {
        $this->jobs[] = func_get_args();
        return $this->tick();
    }


    /**
     *    Removes closed threads from queue
     *
     */

    private function cleanup()
    {
        foreach ($this->threads as $i => $szal)
            if (!$szal->isAlive()) {
                $this->results[] = $this->threads[$i]->result;
                unset($this->threads[$i]);
            }
        return count($this->threads);
    }


    /**
     *    Starts new threads if needed
     *
     * @return int    queue size
     */

    public function tick()
    {
        $this->cleanup();

        if ((count($this->threads) < $this->queueSize) && count($this->jobs)) {
            $this->threads[] = $szal = new Thread($this->callable, $this->enable_messaging, $this->message_length);
            $szal->start(array_shift($this->jobs));
        }

        usleep(ThreadQueue::TICK_DELAY);
        return $this->queueSize();
    }

    /**
     *    returns queue size with waiting jobs
     *
     * @return int
     */

    public function queueSize()
    {
        return count($this->jobs);
    }


    /**
     *    returns thread instances
     *
     * @return array of Thread
     */
    public function threads()
    {
        return $this->threads;
    }


    /**
     *    Removes all remaining jobs (empty queue)
     *
     * @return int    number of removed jobs
     */
    public function flush()
    {
        $size = $this->queueSize();
        $this->jobs = array();
        return $size;
    }

    /**
     *    waits for all threads to complete
     */
    public function wait()
    {

        while (count($this->threads())) {
            $this->tick();
        }
    }

    public function results()
    {
        return $this->results;
    }
}