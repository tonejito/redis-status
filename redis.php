<?php

# redis-status.php - Redis status page using PECL PhpRedis
# Andres Hernandez - tonejito

#	http://pecl.php.net/package/redis
#	https://github.com/phpredis/phpredis#info
#	https://gist.github.com/zircote/969477
#	http://stackoverflow.com/a/4530129

$host = "/var/run/redis/redis.sock";

try
{
  $redis = new Redis();
  #$redis->connect('localhost', 6379);
  $redis->connect($host); // unix domain socket.
  $redis->ping();
$info = array
(
  "server"      => "",
  "clients"     => "",
  "memory"      => "",
  "persistence" => "",
  "stats"       => "",
  "replication" => "",
  "cpu"         => "",
  "keyspace"    => ""
);
  foreach(array_keys($info) as $type)
  {
    $info[$type]=$redis->info($type);
    $table[$type] = "<table id='".$type."' name='".$type."'>"."\n";
    foreach ($info[$type] as $k=>$v)
      $table[$type] .= "<tr><th>".$k."</th><td>".$v."</td></tr>"."\n";
    $table[$type] .= "</table>"."\n";
  }
}
catch (Exception $e)
{
  die("Caught exception: ".$e->getMessage()."\n");
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>redis</title>
    <style>
        body {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            margin: 0;
            padding: 0;
        }

        #container {
            width: 1024px;
            margin: auto;
            position: relative;
        }

        h1 {
            padding: 10px 0;
        }

        table {
            border-collapse: collapse;
        }

        tbody tr:nth-child(even) {
            background-color: #eee;
        }

        p.capitalize {
            text-transform: capitalize;
        }

        .tabs {
            position: relative;
            float: left;
            width: 70%;
        }

        .tab {
            float: left;
        }

        .tab label {
            background: #eee;
            padding: 10px 12px;
            border: 1px solid #ccc;
            margin-left: -1px;
            position: relative;
            left: 1px;
        }

        .tab [type=radio] {
            display: none;
        }

        .tab th, .tab td {
            padding: 8px 12px;
        }

        .content {
            position: absolute;
            top: 28px;
            left: 0;
            background: white;
            border: 1px solid #ccc;
            height: 450px;
            width: 100%;
            overflow: auto;
        }

        .content table {
            width: 100%;
        }

        .content th, .tab:nth-child(3) td {
            text-align: left;
        }

        .content td {
            text-align: right;
        }

        .clickable {
            cursor: pointer;
        }

        [type=radio]:checked ~ label {
            background: white;
            border-bottom: 1px solid white;
            z-index: 2;
        }

        [type=radio]:checked ~ label ~ .content {
            z-index: 1;
        }

        label {
            cursor: pointer;
        }

        .actions {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #cacaca;
        }
    </style>
  </head>
<?php flush(); ?>
  <body>
    <div id="container">
      <span style="float:right;font-size:small;">Redis Status</span>
      <h1><?php echo $host; ?></h1>
      <div class="tabs">
<?php foreach ($table as $id => $content) { ?>
        <div class="tab">
          <input type="radio" id="tab-<?php echo $id; ?>" name="tab-group-1">
          <label for="tab-<?php echo $id; ?>"><?php echo $id; ?></label>
          <div class="content">
<?php echo $content ?>
          </div>
        </div>
<?php } ?>
      </div>
    </div>
<?php flush(); ?>
  <body>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
          $('#tab-memory').attr('checked', 'checked');
          $('#tab-memory').click();
        });
    </script>
  </body>
</html>

