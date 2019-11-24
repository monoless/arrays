# PHP Array Implementation
--
## goal
implementation typed array with less usage of memory.
## example
```php
<?php
define('ARRAY_LENGTH', 10000);
$start_memory = memory_get_usage();

$entries = new \Monoless\Arrays\FixedUnsignedIntegerArray(ARRAY_LENGTH);
for ($i = 0; $i < ARRAY_LENGTH; $i++) {
    $entries[$i] = mt_rand(0, 255);
}

$memory = memory_get_usage() - $start_memory;
echo "fixed unsigned integer array memory usage : {$memory}\n";

$start = microtime(true);

$result = 0;
for ($i = 0; $i < ARRAY_LENGTH; $i++) {
    $result += $entries[$i];
}

$time_elapsed_secs = microtime(true) - $start;
echo "for iterator fixed unsigned integer array : {$time_elapsed_secs}\n";

// legacy array
$start_memory = memory_get_usage();

$rands = [];
for ($i = 0; $i < ARRAY_LENGTH; $i++) {
    $rands[] = mt_rand(0, 255);
}

$memory = memory_get_usage() - $start_memory;
echo "legacy array memory usage : {$memory}\n";

$start = microtime(true);

$result = 0;
for ($i = 0; $i < ARRAY_LENGTH; $i++) {
    $result += $rands[$i];
}

$time_elapsed_secs = microtime(true) - $start;
echo "for iterator legacy array : {$time_elapsed_secs}\n";
```

```text
# fixed unsigned integer array memory usage : 41072
# for iterator fixed unsigned integer array : 0.0032491683959961

# legacy array memory usage : 528440
# for iterator legacy array : 0.00018191337585449
```
## history
- 2019/11/24 - adding FixedUnsignedIntegerArray class