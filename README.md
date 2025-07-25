# DevBot
DevBot is the soul of the forge - a PHP-powered development companion designed to track, log, summarize and report daily insights across your project. It powers the ChaosCMS project and reports to the Dev Team, only those things that matter.

## Features
- Tracks dev activity via the 'devclock' plugin
- Logs task and ideas via the 'devthink' plugin
- Can send daily and weekly reports by email
- Summarize plugin output across its system
- Modular plugin design
- Designed for the ChAoS CMS but adaptable to any PHP project

- ## Directory Structure
- devbot
- - devbot_proc.php (the devbot itself)
- - devbot_config.php
- - plugins
  - -filewatch.php (the plugin that watches your directories and files)
  - -devclock.php (plugin that tracks development time)
  - -devthink.php (plugin that allows you to track tasks and ideas)
  - -YOUR plugin.php, should yu wish to expand the devbot.
 
  ## Plugin Design
  Each plugin
  - lives in 'devbot/plugins'
  - Gets autoloaded by devbot
  - Pushes strings into '$todays_logs[]
  - Uses standard log or JSON I/O
 
  ## Example output
  ```php
  - $todays_logs[] = "&#128293; 3 new errors logged today.";
  - $todays_logs[] = "&#xF272; All milestone completed for phase one.";

  ## Example email output
  ``` Greetings from your DevBot.
  - Finish Mileston 2
  -- Wrap up admin/temes.
  - DevBot

  ## Running the DevBot
  - manually or by cron
  - - manually
  ```php devbot/devbot_proc.php
