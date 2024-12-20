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
 * Scheduled task to process classes taken records to remind students to sign their
 * enrollment agreement and auto-sign the agreement when a student completes or leaves
 * the course if it is not already signed.
 *
 * @package    local_gcs
 * @copyright  2024 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs\task;

class process_agreements extends \core\task\scheduled_task {
    /** @var string log records to write to debug log */
    public $logrecs;

    /**
     * Task name.
     */
    public function get_name() {
        return get_string('taskprocessagreements', 'local_gcs');
    }

    /**
     * Task Processing
     */
    public function execute() {
        $this->logrecs = '';
        $this->logthis('local_gcs: agreement processing started');

        // Debugging is easier if the processing code isn't in the task itself.
        $processor = new \local_gcs\agreement_processor();

        // Autosign or remind students to sign agreements.
        $this->logrecs .= $processor->process_agreements();

        return $this->logrecs;
    }
    public function logthis($logrec) {
        if ($this->logrecs == '') {
            $l = '--------------------------------------------------------------------------------';
            mtrace($l);
            $this->logrecs = $l.PHP_EOL;
        }
        $this->logrecs .= $logrec.PHP_EOL;
        mtrace($logrec);
    }
}
