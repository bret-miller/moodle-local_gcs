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
 * Process course registration data from RegFox webhooks
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs\task;

class process_regfox_registrations extends \core\task\adhoc_task {
    /** @var string log records to write to debug log */
    public $logrecs;

    /**
     * Task name.
     */
    public function get_name() {
        return get_string('taskprocessregfox', 'local_gcs');
    }

    /**
     * Task Processing
     */
    public function execute() {
        $this->logrecs = '';
        $this->log('local_gcs: process_regfox_registrations started');

        // Debugging is easier if the processing code isn't in the task itself.
        $processor = new \local_gcs\regfox_processor($this);

        // Process raw webhooks into registrants and class registrations.
        $logrecs = $processor->process_webhooks();
        $this->log($logrecs);

        // Process the unprocessed registrants and class registrations.
        $logrecs = $processor->process_registrants();
        $this->log($logrecs);

        return $this->logrecs;
    }
    public function log($logrec) {
        if ($this->logrecs == '') {
            $l = '--------------------------------------------------------------------------------';
            mtrace($l);
            $this->logrecs = $l.PHP_EOL;
        }
        $this->logrecs .= $logrec.PHP_EOL;
        mtrace($logrec);
    }
    public function writelog() {
        $this->log('--------------------------------------------------------------------------------');
        $f = fopen("/home/gcswww/dev.gcs.edu/local/gcs/regfox.log", "a");
        fwrite($f, $this->logrecs);
        fclose($f);
    }
}
