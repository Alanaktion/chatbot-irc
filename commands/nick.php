<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!$args) {
        return "Usage: !nick <nickname>";
    }
    $client->nick($args);
    return 'Nickname changed.';
};
