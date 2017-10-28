<?php
return function (IrcClient $client, string $args, string $channel) {
    global $timer;

    if (!empty($timer)) {
        $init = time() - $timer;
        $hours = floor($init / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        $str = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    $params = explode(' ', $args);
    if (empty($params[0])) {
        if (!empty($timer)) {
            return "Timer running: $str";
        } else {
            return "Timer isn't running!";
        }
    } elseif ($params[0] == "start") {
        $timer = time();
        return "Starting timer";
    } elseif ($params[0] == "stop") {
        if (!empty($timer)) {
            return "Timer stopped. Time: $str";
            $timer = 0;
            unset($timer);
        } else {
            return "Timer isn't running!";
        }
    } else {
        return "Usage: !timer [start|stop]";
    }
};
