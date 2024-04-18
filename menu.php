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
 * GCS Program Management Menu Page
 *
 * @package    local_gcs
 * @copyright  2024 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../config.php');
require_login(null, false);

$settings = new \local_gcs\settings();
$html = '';
$pagetitle = get_string("menumenu", 'local_gcs');
$pagetitle = str_replace('{pluginname}', $settings->pluginname, $pagetitle);
global $OUTPUT, $PAGE;
$htmlpage = new \local_gcs\html_page('',[],$pagetitle);
$systemcontext = context_system::instance();
if (has_capability('local/gcs:administrator', $systemcontext)) {
	$foldername = get_config('local_gcs', 'menuadmin');
} else {
	$foldername = get_config('local_gcs', 'menustudent');
}
$PAGE->navigation->initialise();
$nav = clone($PAGE->navigation);
/*
$nodes = $nav->find_all_of_type(global_navigation::TYPE_CATEGORY);
$node = false;
foreach ($nodes as $n) {
	if ($n->text == $foldername) {
		$node = $n;
	}
}
*/
$node = $nav->find('gcspm', global_navigation::TYPE_CUSTOM);
if (!$node){
	$node = $nav->find('gcsstu', global_navigation::TYPE_CUSTOM);
}
/*
$html .= "\n<pre>\n";
$html .= "====================================================================================================\n";
$html .= "foldername: $foldername\n\n";
$html .= print_r($node,true);
$html .= "\n====================================================================================================\n";
$html .= print_r($PAGE->navigation,true);
$html .= "\n</pre>\n";
//*/
$html .= $OUTPUT->render_from_template('core/settings_link_page', ['node' => $node]);
$htmlpage->content .= $html;
echo $htmlpage->output->render($htmlpage);
