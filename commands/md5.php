<?php
return function (IrcClient $client, string $args, string $channel) {
    if (empty($params)) {
        return "Usage: !md5 <string>";
    } else {
        return md5(implode(" ", $params));
    }
};
