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
  $info=$redis->info();
  $table = "<table>"."\n";
  foreach ($info as $k=>$v)
    $table .= "<tr><th>".$k."</th><td>".$v."</td></tr>"."\n";
  $table .= "</table>"."\n";
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
            width: auto;
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
            height: auto;
            width: auto;
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

        #graph {
            float: right;
            width: 40%;
            position: relative;
        }

        #graph > form {
            position: absolute;
            right: 60px;
            top: -20px;
        }

        #graph > svg {
            position: absolute;
            top: 0;
            right: 0;
        }

        #stats {
            position: absolute;
            right: 125px;
            top: 145px;
        }

        #stats th, #stats td {
            padding: 6px 10px;
            font-size: 0.8em;
        }

        #partition {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 10;
            top: 0;
            left: 0;
            background: #ddd;
            display: none;
        }

        #close-partition {
            display: none;
            position: absolute;
            z-index: 20;
            right: 15px;
            top: 15px;
            background: #f9373d;
            color: #fff;
            padding: 12px 15px;
        }

        #close-partition:hover {
            background: #D32F33;
            cursor: pointer;
        }

        #partition rect {
            stroke: #fff;
            fill: #aaa;
            fill-opacity: 1;
        }

        #partition rect.parent {
            cursor: pointer;
            fill: steelblue;
        }

        #partition text {
            pointer-events: none;
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
        <div class="tab">
          <input type="radio" id="tab-status" name="tab-group-1" checked>
          <label for="tab-status">Status</label>
          <div class="content">
            <?php echo $table ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

