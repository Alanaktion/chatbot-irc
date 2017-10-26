<?php
// command find test

$command = $argv[1];

$root = dirname(__DIR__) . '/commands/';
if (is_file($root.$command.'.php')) {
    echo $root.$command.'.php', "\n";
    return;
}
foreach (scandir($root) as $dir) {
    if ($dir{0} != '.' && is_dir($root.$dir)) {
        if (is_file($root.$dir.'/'.$command.'.php')) {
            echo $root.$dir.'/'.$command.'.php', "\n";
            return;
        }
    }
}
