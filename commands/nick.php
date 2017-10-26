<?php
return function (IrcClient $client, string $args, string $channel) {
    if (empty($params[0])) {
        return "Usage: !nick <nickname>";
    }

    $client->nick($args);
    return 'Nickname changed.';
};
