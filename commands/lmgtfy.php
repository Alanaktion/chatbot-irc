<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!$args) {
        return "Usage: !lmgtfy <query>";
    } else {
        return "http://lmgtfy.com/?q=" . urlencode($args);
    }
};
