<?php
return function (IrcClient $client, string $args, string $channel) {
    if ($args) {
        date_default_timezone_set(TZ);
        $str = date($args);
		date_default_timezone_set("UTC");
		return $str;
    } else {
        return "Usage: !date <format>";
    }
};
