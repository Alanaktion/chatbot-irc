<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!$args) {
        return "Usage: !sha1 <string>";
    } else {
        return sha1($args);
    }
};
