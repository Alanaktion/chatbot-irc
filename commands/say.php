<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!$args) {
        return "Usage: !say <message>";
    } else {
        return $args;
    }
};
