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
 * Scheduled task to handle daily automation tasks.
 *
 * @package    local_gcs
 * @copyright  2024 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs\task;

defined('MOODLE_INTERNAL') || die();

class daily_automation extends \core\task\scheduled_task {
    /** @var string log records to write to debug log */
    public $logrecs;

    /**
     * Task name.
     */
    public function get_name() {
        return get_string('taskdailyautomation', 'local_gcs');
    }

    /**
     * Task Processing
     */
    public function execute() {
        $this->logrecs = '';
        mtrace('local_gcs: daily automation started');
        
        // Handle automatic course enrollments for students added to or removed from a class.
        $enrollment = new \local_gcs\enrollment();
        $this->logrecs .= $enrollment->verify_enrollments();
        
        return $this->logrecs;
    }
}
