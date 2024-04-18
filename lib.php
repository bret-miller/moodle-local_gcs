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
 * Internal functions for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Adds GCS menu items to page navigation menu
 *
 * @param global_navigation $nav
 */
function local_gcs_extend_navigation(global_navigation $nav) {
    $studmenu = [
        'program_req_completed',
        'sch_application',
        'enrollment_agreements_signing',
    ];
    $adminmenu = [
        'programs',
        'program_completion',
        'program_requirements',
        'permitted_courses',
        'courses',
        'classes',
        'classes_taken',
        'enrollment_agreements',
        'students',
        'term_dates',
        'sch_available',
        'sch_given',
        'codes',
        'acct_sch_cat',
        'regfox_webhook',
        'transcript',
        ];
    $reportsmenu = [
        'classes_roster',
        'program_req_completed',
        'student_list',
		'enrollment_agreements_unsigned',
        ];
    $systemcontext = context_system::instance();
    //$navtree = $nav->find('home', global_navigation::TYPE_SETTING);
    $navtree = $nav;
    if ($nav) {
		$navurl = new moodle_url("/local/gcs/menu.php");
		$navtype = global_navigation::TYPE_CUSTOM;
        if (has_capability('local/gcs:administrator', $systemcontext)) {
            $foldername = get_config('local_gcs', 'menuadmin');
            $navfld = $navtree->add($foldername, $navurl, $navtype, null, 'gcspm');
			$navfld->set_show_in_secondary_navigation(true);
			$navurl = null;
			$navtype = global_navigation::TYPE_CATEGORY;
            foreach ($adminmenu as $menuitem) {
                $name = get_string("menu$menuitem", 'local_gcs');
                $url = new moodle_url("/local/gcs/$menuitem.php");
                $navfld->add($name, $url, global_navigation::NODETYPE_LEAF);
            }
			$navtree = $navfld;
            $foldername = get_config('local_gcs', 'menureports');
            $navfld = $navtree->add($foldername, null, global_navigation::TYPE_CATEGORY, null, 'gcsrpt');
            foreach ($reportsmenu as $menuitem) {
                $name = get_string("menu$menuitem", 'local_gcs');
                $url = new moodle_url("/local/gcs/$menuitem.php");
                $navfld->add($name, $url, global_navigation::NODETYPE_LEAF);
            }
        }
        $foldername = get_config('local_gcs', 'menustudent');
        $navfld = $navtree->add($foldername, $navurl, $navtype, null, 'gcsstu');
        foreach ($studmenu as $menuitem) {
            $name = get_string("menu$menuitem", 'local_gcs');
            $url = new moodle_url("/local/gcs/$menuitem.php");
            $navfld->add($name, $url, global_navigation::NODETYPE_LEAF);
        }
    }
}


/**
 * Serve the embedded files.
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function local_gcs_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=[]) {
    // Removed check for logged in since some files may be used in email messages.
    $itemid = array_shift($args);
    $filename = array_pop($args);

    if (!$args) {
        $filepath = '/';
    } else {
        $filepath = '/'.implode('/', $args).'/';
    }

    $fs = get_file_storage();
    $files = $fs->get_area_files(
            context_system::instance()->id,
            'local_gcs',
            $filearea,
            0,
            "filename",
            false
        );
    $file = array_pop($files);
    if (!$file) {
        return false;
    }

    send_stored_file($file, null, 0, $forcedownload, $options);
}
