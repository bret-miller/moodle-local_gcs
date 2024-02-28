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
 * Trigger RegFox processing task
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs\external;

defined('MOODLE_INTERNAL') || die();

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/externallib.php');
require_once(__dir__.'/../data.php');

require_login();
require_capability('local/gcs:administrator', \context_system::instance());

/**
 * Get unprocessed webhooks
 */
class regfox_get_unprocessed_webhooks extends \external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
        ]);
    }
    /**
     * Returns description of method returns
     * @return external_external_multiple_structure

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('receivedtime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('signature', XMLDB_TYPE_CHAR, '256', null, null, null, null);
        $table->add_field('event', XMLDB_TYPE_CHAR, '32', null, null, null, null);
        $table->add_field('postdata', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('processedtime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');


     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Identity Key|ro'),
                'receivedtime' => new external_value(PARAM_INT, 'Time received|date|ro'),
                'signature' => new external_value(PARAM_TEXT, 'Verification signature'),
                'event' => new external_value(PARAM_TEXT, 'Event'),
                'postdata' => new external_value(PARAM_TEXT, 'JSON post data'),
                'processedtime' => new external_value(PARAM_INT, 'Time processed|date'),
            ])
        );
    }
    /**
     * Get current list of unprocessed webhooks
     * @return hash of regfox webhook records
     */
    public static function execute() {
        $recs = \local_gcs\data::get_regfox_webhooks_unprocessed();
        return $recs;
    }
}
