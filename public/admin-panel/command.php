<?php

require __DIR__ . '/bootstrap.php';

$commandKey = request('command');
$tabKey = request('tab');
$key = "tabs.{$tabKey}.commands.{$commandKey}";

if (! $command = config($key)) {
    return response_json(array(
        'error' => "Unknown command {$tabKey}.{$commandKey}",
    ), 404);
}

if (is_array($command)) {
    $command = $command['command'];
}

list($successful, $output) = process($command);

return response_json(array(
    'output' => $output,
    'successful' => $successful,
), 200);

