<?php
define('PM_PATH', '/home/arik/pocketmine-mp');
define('PM_USER', 'arik');
define('PM_LOG', '/var/log/pocketmine.log');

if (!session_id())
{
    session_start();
}

if (is_numeric($_GET['n']))
{
    $_SESSION['pocketmine_n'] = intval($_GET['n']);
}
?>
<html>
<head>
<meta charset="utf-8">
<title>PocketMine</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<style type="text/css">
p {
    margin-top:12px;
}
textarea {
    padding:10px;
}
</style>
</head>
<body>
<?php
function pocketmine_log($s)
{
    if (!$f = @fopen(PM_LOG, 'a')) return false;
    fputs($f, date('r'). ' > '. $s. "\n");
    fclose($f);
    return true;
}
function pocketmine_get_pids()
{
    $pids = array();
    $cmd = 'sudo ps -u '. PM_USER. ' | grep php';
    pocketmine_log($cmd);
    exec($cmd, $output);
    if (is_array($output))
    {
        foreach ($output as $s)
        {
            $tmp = explode(' ', $s);
            $pid = intval($tmp[0]);
            $pids[] = $pid;
        }
    }
    return $pids;
}
//
function pocketmine_start()
{
    $pids = pocketmine_get_pids();
    if (count($pids) > 0) return -1;
    $cmd = 'sudo -S -u '. PM_USER. ' '. PM_PATH. '/start.sh > /dev/null 2>&1 &';
    pocketmine_log($cmd);
    exec($cmd, $output);
    $pids = pocketmine_get_pids();
    if (count($pids) > 0) return 1;
    return 0;
}
//
function pocketmine_stop()
{
    $pids = pocketmine_get_pids();
    if (count($pids) == 0) return 0;
    foreach ($pids as $pid)
    {
        $cmd = 'sudo kill -9 ' . $pid;
        pocketmine_log($cmd);
        exec($cmd);
    }
    $pids = pocketmine_get_pids();
    if (count($pids) == 0) return 1;
    return 0;
}
//
function pocketmine_get_log()
{
    $s = '';
    $log = pocketmine_tail();
    foreach ($log as $l)
    {
        $s .= $l. "\n";
    } 
    return $s;
}
//
function pocketmine_tail($n = 100)
{
    $cmd = 'sudo -S -u '. PM_USER. ' tail -n '. $n. ' '. PM_PATH. '/server.log';
    pocketmine_log($cmd);
    exec($cmd, $output);
    if (!is_array($output)) return array();
    return array_reverse($output);
}
//
function pocketmine_restart()
{
    pocketmine_stop();
    return pocketmine_start();
}
//
if ($_GET['action'] == 'start')
{
    $result = pocketmine_start();
}
//
if ($_GET['action'] == 'stop')
{
    $result = pocketmine_stop();
}
//
if ($_GET['action'] == 'restart')
{
    $result = pocketmine_restart();
}
//
$pids = pocketmine_get_pids();
if (count($pids)>0)
{
    $status = '<span class="label label-success">Online</span>';
    $start = ' disabled';
    $stop = '';
}
else
{
    $status = '<span class="label label-danger">Offline</span>';
    $start = '';
    $stop = ' disabled';
}
//
$log_n = isset($_SESSION['pocketmine_n']) ? intval($_SESSION['pocketmine_n']) : 100;
//
?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>Server status: <?php echo $status; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-primary<?php echo $start; ?>" href="?action=start">Start</a>
                &nbsp;
                <a class="btn btn-danger<?php echo $stop; ?>" href="?action=stop">Stop</a>
                &nbsp;
                <a class="btn btn-warning<?php echo $stop; ?>" href="?action=restart">Restart</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>Server Log:</p>
                <textarea name="log" id="log" rows="20" style="width: 100%;"><?php echo pocketmine_get_log(); ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-primary">Refresh</a></p>
            </div>
            <div class="col-md-6 text-right">
                <p>
                    <form class="form-inline" method="get">
                        <input type="text" name="n" class="form-control" size="3" id="n" value="<?php echo $log_n; ?>" />
                        <input type="submit" value="Show" class="btn btn-primary" />
                    </form>
                </p>
            </div>
        </div>
    </div>
</body>
</html>