<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!$args) {
        return "Usage: !md5 <string>";
    } else {
        return md5($args);
    }
};
