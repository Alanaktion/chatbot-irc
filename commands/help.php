<?php
return function (IrcClient $client, string $args, string $channel) {
    if (!$args) {

        // Show command list
        $list = scandir(__DIR__);
        $commands = array();
        foreach ($list as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (substr($file, -4) == '.php') {
                $commands[] = substr($file, 0, strlen($file) - 4);
            } elseif (is_dir(__DIR__ . '/' . $file)) {
                foreach (scandir(__DIR__ . '/' . $file) as $file2) {
                    if (substr($file2, -4) == '.php') {
                        $commands[] = substr($file2, 0, strlen($file2) - 4);
                    }
                }
            }
        }

        $count = count($commands);
        return "Available commands ($count): " . implode(", ", $commands);
    } else {

        // Show single command help
        $file = dirname(__DIR__) . "/command-help/{$args}.txt";
        if (is_file($file)) {
            return rtrim(file_get_contents($file));
        } else {
            return "Help is not available for {$args}. Try running the command without any parameters.";
        }
    }
};
