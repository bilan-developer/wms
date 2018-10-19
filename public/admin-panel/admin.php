<?php

require __DIR__ . '/bootstrap.php';

$services = array();

foreach (config('tabs') as $tab => $tabContent) {

    $commands = array_keys($tabContent['commands']);
    $commands = array_map(function ($command) use ($tabContent) {

        $description = $command;
        $name = $command;

        if (is_array($tabContent['commands'][$command])) {
            $description = $tabContent['commands'][$command]['description'];
        }

        return compact('name', 'description');
    }, $commands);

    $index = 0;

    foreach ($tabContent['grid']['rows'] as $rowIndex => $row) {
        foreach ($row as $columnClass) {
            $commands[$index]['row'] = $rowIndex + 1;
            $commands[$index]['col'] = $columnClass;

            $index ++;
        }
    }

    $services[] = array(
        'id' => $tab,
        'name' => $tab,
        'commands' => $commands,
    );
}

return response(view('admin', array(
    'services' => json_encode($services),
    'endpoint' => 'command.php',
    'defaultTab' => config('defaultTab'),
)));
