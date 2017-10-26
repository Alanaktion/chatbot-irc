<?php
return function (IrcClient $client, string $args, string $channel) {
    if (isset($params[1])) {
        shuffle($params);
        return implode(" ", $params);
    } elseif (isset($params[0])) {
        return str_shuffle($params[0]);
    } else {
        return "Usage: !shuffle <word> [word]";
    }
};
