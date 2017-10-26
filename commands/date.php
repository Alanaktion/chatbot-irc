<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!empty($params)) {
        date_default_timezone_set(TZ);
        $str = date(implode(" ", $params));
		date_default_timezone_set("UTC");
		return $str;
    } else {
        return "Usage: !date <format>";
    }
};
