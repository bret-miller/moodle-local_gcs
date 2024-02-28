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
 * Display the list of classes with their roster for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../config.php');

// For some reason, require_login() doesn't redirect to the login page when we call it.
if (!isloggedin()) {
    $SESSION->wantsurl = qualified_me();
    redirect(get_login_url());
}

$settings = new \local_gcs\settings();
$html = '<div>
    <select id="statussel">
		<option value="1" selected>Active Students</option>
		<option value="0">All Students</option>
	</select> &nbsp;
	<select id="orderby">
        <option value="">-- Order by? --</option>
		<option value="p">By program</option>
		<option value="s">By state</option>
		<option value="n">By name</option>
	</select> &nbsp;
	<select id="showsel">
        <option value=" ">Show students only</option>
		<option value="p">Show completed programs</option>
		<option value="c">Show classes taken</option>
	</select> &nbsp;
	<select id="progsel" class="hidden">
	</select> &nbsp;
	<select id="ctrysel" class="hidden">
	</select> &nbsp;
</div>
<div class="reportcontent"></div>';
$htmlpage = new \local_gcs\html_page($html,['amd/webservice.js']);
echo $htmlpage->output->render($htmlpage);
