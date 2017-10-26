<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!$args) {
        return "Usage: !join <channel>";
    } else {
        $client->join('#' . ltrim($args, '#'));
    }
};
