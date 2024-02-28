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
 * Renders an angular module for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Renders an Angular module
 */
class local_gcs_renderer extends \plugin_renderer_base {
    /**
     * Renders an Angular page
     *
     * @param angular_page $angularpage angular page class
     * @return string HTML
     */
    public function render_angular_page(\local_gcs\angular_page $angularpage) {
        $out  = $this->output->header();
        $out .= $this->output->heading($angularpage->title);
        $out .= self::render_angular_module($angularpage->angularmodule);
        $out .= $this->output->footer();
        return $out;
    }

    /**
     * Renders an Angular module
     *
     * @param angular_module $mdl angular module class
     * @return string HTML
     */
    public function render_angular_module(\local_gcs\angular_module $mdl) {
        $out = $this->output->container_start('angular-app');
        $out .= $mdl->appcode . PHP_EOL;
        $out .= $this->output->container_end(); // End of content.
        $out .= '<!-- End angular-app. -->' . PHP_EOL;
        $out .= '<!-- DEBUG angular module' . PHP_EOL;
        $out .= print_r($mdl, true) . PHP_EOL . '-->' . PHP_EOL;
        if ($mdl->fromcache) {
            $out .= '<!-- Module definition loaded from cache time='.$mdl->modtime.' -->' . PHP_EOL;
        }
        foreach ($mdl->scripts as $jsfile) {
            $this->page->requires->js(new moodle_url($jsfile));
        }
        return $out;
    }

    /**
     * Renders an HTML page
     *
     * @param html_page $htmlpage html page class
     * @return string HTML
     */
    public function render_html_page(\local_gcs\html_page $htmlpage) {
        $out  = $this->output->header();
        $out .= $this->output->heading($htmlpage->title);
        $out .= '<div class="local-gcs-htmlpage">' . $htmlpage->content . '</div>' . PHP_EOL;
        $out .= $this->output->footer();
        return $out;
    }
}
