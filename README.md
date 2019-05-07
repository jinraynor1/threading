# Threads (fork)
simulate threads using pcntl posix 


* Features:
  * Can set queue size
  * Can pass messages from childs to parent 
  
# Example
```php
$queue = new ThreadQueue(function ($number) {
            return pow($number, 2);

});

$queue->enableMessaging(true);


$queue->add(1);
$queue->add(3);


$queue->wait();
$results = $queue->results();
$sum = array_sum($results);

// $sums now holds 9

```