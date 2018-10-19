<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

function array_get($array, $key, $default = null)
{
    if (is_null($key)) {
        return $array;
    }

    if (isset($array[$key])) {
        return $array[$key];
    }

    foreach (explode('.', $key) as $segment) {
        if (! is_array($array) || ! array_key_exists($segment, $array)) {
            return $default;
        }

        $array = $array[$segment];
    }

    return $array;
}

function config($key = null, $default = null)
{
    static $config;

    if (! $config) {
        $content = file_get_contents(CONFIG_FILE);
        $config = Yaml::parse($content);
    }

    return array_get($config, $key, $default);
}

function request($key = null, $default = null)
{
    static $request;

    if (! $request) {
        $request = Request::createFromGlobals();
    }

    return is_null($key)
        ? $request
        : $request->query->get($key, $default);
}

function view($view, array $params = array())
{
    ob_start();
    extract($params);
    include __DIR__ . "/views/{$view}.phtml";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

function response($content, $status = 200, array $headers = array('content-type' => 'text/html'))
{
    $response = new Response($content, $status, $headers);

    return $response->send();
}

function response_json(array $content, $status, array $headers = array())
{
    $headers = array('Content-Type' => 'application/json') + $headers;

    return response(json_encode($content), $status, $headers);
}

function prepare_command($command)
{
    preg_match_all('/\${[^}]+\}/', $command, $matches);

    foreach ($matches[0] as $match) {
        $key = substr($match, 2, strlen($match) - 3); // remove ${}
        $command = str_replace($match, config($key), $command);
    }

    return $command;
}

function process($command)
{
    $command = prepare_command($command);
    $workingDirectory = config('workingDirectory');

    log_command($command);

    $process = new Process($command, $workingDirectory);
    $process->run();

    $successful = $process->isSuccessful();
    $output = $process->isSuccessful()
        ? $process->getOutput()
        : $process->getErrorOutput();

    return array($successful, $output);
}

function log_command($command)
{
    $datetime = date(LOG_DATE_FORMAT);
    $ip = request()->getClientIp();
    $username = request()->server->get('PHP_AUTH_USER');

    $content = sprintf('[%s] [%s] [%s] %s', $datetime, $ip, $username, $command);

    $dir = dirname(LOG_FILE);

    if (! file_exists($dir)) {
        mkdir($dir);
    }

    file_put_contents(LOG_FILE, $content . PHP_EOL, FILE_APPEND);
}
