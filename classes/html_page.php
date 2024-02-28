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
 * Defines an angular module for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;
use renderable;
use cache;

/**
 * Defines a standard html page
 */
class html_page implements renderable {
    /** @var string name of the file for this page */
    public $name;
    /** @var string relative URL of this page */
    public $url;
    /** @var string title this page */
    public $title;
    /** @var class instanciated angular_module class for the Angular module */
    public $content;
    /** @var class output renderer */
    public $output;

    /**
     * Initializes an Angular-based page
     *
     * @param string $content html content to output
     * @param array  $jsfiles javascript files to require on page
     */
    public function __construct($content, $jsfiles=[]) {
        global $PAGE;
        global $CFG;
        // Get basename, filename and URL.
        $name = basename(debug_backtrace()[0]['file'], '.php');
        $this->name = $name;
        $this->url = "/local/gcs/$name.php";
        $this->title = get_string("menu$name", 'local_gcs');
        $this->content = $content;

        // Set up the page environment.
        $PAGE->set_url($this->url);
        $PAGE->set_pagelayout('admin');
        $PAGE->set_context(\context_system::instance());
        $PAGE->set_title($this->title);
        $PAGE->set_heading($this->title);

        // Check if there is css to go with this page.
        $cssfile = "css/".$this->name.".css";
        if ($url = $this->timeurl($cssfile)) {
            $stylesheet = new \moodle_url($url);
            $PAGE->requires->css($stylesheet);
        }

        // Check if there is script to go with this page.
        $jsfile = "amd/".$this->name.".js";
        if ($url = $this->timeurl($jsfile, true)) {
            $murl = new \moodle_url($url);
			$jsmodule = ['name' => $this->name, 'fullpath' => $url, 'requires' => [], 'strings' => []];
            $PAGE->requires->js($murl);
        }

        // Load any scripts the page wanted too.
        foreach ($jsfiles as $jsfile) {
			if ($url = $this->timeurl($jsfile, true)) {
				$scriptfile = new \moodle_url($url);
				$PAGE->requires->js($scriptfile);
			}
        }

        // Output the header.
        $this->output = $PAGE->get_renderer('local_gcs');
    }
    /**
     * Creates a URL with a time parameter to autoreload when the file changes
     *
     * @param string $relurl relative url
     * @return string full url
     */
    private function timeurl($relurl, $relative=false) {
		global $CFG;
        $url = false;
        $mydir = $CFG->dirroot . "/local/gcs/";
        $myurl = "/local/gcs/";
        if (file_exists($mydir . $relurl)) {
            $tm = filemtime($mydir . $relurl);
			if ($relative) {
				$url = '';
			} else {
				$url = $CFG->wwwroot;
			}
            $url .= $myurl . $relurl . "?t=" . $tm;
        }
        return $url;
    }
}
