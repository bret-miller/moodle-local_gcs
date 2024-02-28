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
 * Defines an angular-based page for GCS Program Management
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
 * Defines a standard Angular-based page
 */
class angular_page implements renderable {
    /** @var string name of the file for this page */
    public $name;
    /** @var string relative URL of this page */
    public $url;
    /** @var string title this page */
    public $title;
    /** @var class instanciated angular_module class for the Angular module */
    public $angularmodule;
    /** @var class output renderer */
    public $output;

    /**
     * Initializes an Angular-based page
     *
     * @param string $modulename name of the folder where the Angular module exists
     */
    public function __construct($modulename) {
        global $PAGE;

        // Get basename, filename and URL.
        $name = basename(debug_backtrace()[0]['file'], '.php');
        $this->name = $name;
        $this->url = "/local/gcs/$name.php";
        $this->title = get_string("menu$name", 'local_gcs');

        // Set up Angular module, scripts and stylesheet.
        $this->angularmodule = new \local_gcs\angular_module($modulename);
        $this->angularmodule->load_styles($PAGE);

        // Set up the page environment.
        $PAGE->set_url($this->url);
        $PAGE->set_pagelayout('admin');
        $PAGE->set_context(\context_system::instance());
        $PAGE->set_title($this->title);
        $PAGE->set_heading($this->title);

        // Output the header.
        $this->output = $PAGE->get_renderer('local_gcs');
    }
}
