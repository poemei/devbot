<h1>DevBot</h1>
DevBot is the soul of the forge - a PHP-powered development companion designed to track, log, summarize and report daily insights across your project. It powers the ChaosCMS project and reports to the Dev Team, only those things that matter.

<h2> Features</h2>
<ul>
 <li>Tracks dev activity via the <code>devclock</code> plugin</li>
 <li>Logs task and ideas via the <code>devthink</code> plugin</li>
 <li>Can send daily and weekly reports by email</li>
 <li>Summarize plugin output across its system</li>
- <li>Modular plugin design</li>
- <li>Designed for the ChAoS CMS but adaptable to any PHP project</li>
</ul>

- <h2> Directory Structure</h2>
- <strong>devbot</strong>
  <ul>
    <li>devbot_proc.php (the devbot itself)</li>
    <li>devbot_config.php</li>
     <li><strong>plugins</strong>
       <ul>
        <li>filewatch.php (watches your directories and files for changes)</li>
        <li>devclock.php (track development time)</li>
        <li>devthink.php (track tasks and ideas)</li>
        <li>YOUR plugin.php, should yu wish to expand the devbot.</li>
       </ul>
     </li>
  </ul>
 
  <h2>Plugin Design</h2>
  <strong>Each Plugin</strong>
  <ul>
    <li>lives in <code>devbot/plugins</code></li>
    <li>Gets autoloaded by devbot</li>
    <li>Pushes strings into <code>$todays_logs[]</code></li>
    <li>Uses standard log or JSON I/O</li>
 </ul>
    
  ## Running the DevBot
  - manually or by cron
   - - <code>php devbot/devbot_proc.php</code>
  
  ## Example Output
  ```php
  $todays_logs[] = "3 new errors logged today.";
  $todays_logs[] = "All milestone completed for phase one.";
 ```
  ## Example email output
  ```php
  Greetings from your DevBot.
  - Finish Mileston 2
  -- Wrap up admin/themes.
  DevBot
```
