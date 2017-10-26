# chatbot-irc
*A minimal IRC chat bot*

## Requirements
PHP 7.1 or higher.

## Installation
Clone the repo or download the zip.

## Configuration
Copy `config-sample.php` to `config.php` and replace the configuration options with the ones for your server.

## Usage
Start the bot by running `php bot.php` (or `./bot.php` on unix-like environments) from the command line.

Use the bot by running commands from a channel the bot is a member of. All commands start with a bang (`!`).

Run `!help` to list all available commands.

## Adding Commands
New commands can be added to the `commands/` directory, and should contain a single returned function. See `commands/say.php` for a basic example.

Command help messages can be added to the `command-help/` directory. They should match the command name, and have a `.txt` file extension.
