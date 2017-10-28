<?php
return function (IrcClient $client, string $args, string $channel) {
    if ($args) {
        return strrev($args);
    } else {
        return "Usage: !backwards <words, yo>";
    }
};
