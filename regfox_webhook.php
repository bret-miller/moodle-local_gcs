<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Collect the webhook data from RegFox for later processing
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(__DIR__.'/../../config.php');
require_once(__DIR__.'/classes/data.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postdata = file_get_contents('php://input');
    $req = json_decode($postdata);
    if (!$req) {
        $req = "** Invalid JSON data **";
    }
    $html = "\n\n<!--\n_REQUEST:\n".print_r($_REQUEST,true)."\n\n\n_SERVER:\n".print_r($_SERVER,true)."\n\n\n";

    $f = fopen("/home/gcswww/dev.gcs.edu/local/gcs/debug.log", "w");
    fwrite($f, print_r($html, true));
    fwrite($f, "POST data:\n$postdata\n\n\n\n");
    fwrite($f, "decoded data:\n".print_r($req,true)."\n\n\n\n");
    fclose($f);

    $now = time();
    $sig = $_SERVER['HTTP_X_WEBCONNEX_SIGNATURE'];
    $evt = $_SERVER['HTTP_X_WEBCONNEX_EVENT'];
    $rec = (object) [
        'receivedtime' => $now,
        'signature' => $sig,
        'event' => $evt,
        'postdata' => $postdata,
        'processedtime' => 0,
    ];
    \local_gcs\data::insert_regfox_webhook($rec);
    $secret = '3213b1dee75d47c4acfb32e295ae2dcd';
    $hash = hash_hmac('sha256',$postdata,$secret);
    $task = new \local_gcs\task\process_regfox_registrations();
    \core\task\manager::reschedule_or_queue_adhoc_task($task);

    if ($sig != $hash) {
        echo '*** IGNORED: invalid signature ***'.PHP_EOL;
        echo 'Signature: '.$sig.PHP_EOL;
        echo '     Hash: '.$hash.PHP_EOL;
    }
} else {
    require_login();
    $html  = '<div class="gcswebhooks"><span>&nbsp;</span><br />'.PHP_EOL;
    $html .= '    <button id="gcstriggerbutton" onclick="local_gcs_trigger_processing();">Trigger Webhook Processing</button><br />'.PHP_EOL;
    $html .= '    <button id="gcsprocessbutton" onclick="local_gcs_process_webhooks();">Process Webhooks</button><br />'.PHP_EOL;
    $html .= '    <button id="gcsprocessbutton" onclick="local_gcs_process_registrants();">Process Registrants</button><br />'.PHP_EOL;
    $html .= '    <button id="gcstriggermatching" onclick="local_gcs_trigger_task(\'matching\');">Trigger Student Matching</button><br />'.PHP_EOL;
    $html .= '    <div class="response"></div>'.PHP_EOL;
    $html .= '</div>'.PHP_EOL;
    $html .= '<div class="gcscontent">Loading...</div>'.PHP_EOL;
    $htmlpage = new \local_gcs\html_page($html,['amd/webservice.js']);
    //$x = new \local_gcs\regfox_class();
    //$x = new \local_gcs\regfox_processor();
    echo $htmlpage->output->render($htmlpage);
}
