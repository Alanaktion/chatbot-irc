<?php
return function (IrcClient $client, string $args, string $channel) {
    if ($args) {
        if (rand(1, 10) == 1) {
            return $args . "'s fail level is over 9000!!!!!!!11";
        } else {
            return "On a fail of 1-100, " . $args . " failed at " . rand(1, 100) . ".";
        }
    } else {
        return "Usage: !fail <who failed>";
    }
};
