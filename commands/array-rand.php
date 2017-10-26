<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!empty($params) && count($params) > 1) {
        return $params[array_rand($params)];
    } else {
        return "Usage: !array-rand [item1] [item2] ... [itemN]";
    }
};
