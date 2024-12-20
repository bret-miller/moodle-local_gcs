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
 * Web services for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
require_once(__dir__.'/../classes/settings.php');
$functions = [
    // The name of your web service function.
    'local_gcs_codesets_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\codesets_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the names of all the codesets.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_codes_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\codes_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a set of codes and descriptions.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_code_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\code_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a code record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_code_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\code_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a code record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_code_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\code_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new code record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_code_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\code_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a code.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_programs_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\programs_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list of programs of study.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_program_get_by_programcode' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_get_by_programcode',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a program record by programcode.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_program_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a program of study.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_program_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a program of study.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_program_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new program of study.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_program_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a program of study.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_program_completion_get_all' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_completion_get_all',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the programs completed by a student or all students, within a program or all programs.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_program_completion_get_by_student' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_completion_get_by_student',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the programs completed by a student or all students, within a program or all programs.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_program_completion_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_completion_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a programs completed by a student.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_program_completion_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_completion_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a program completed by a student.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_program_completion_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_completion_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a program completed by a student.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_program_completion_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_completion_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a program completed by a student.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_programreqs_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\programreqs_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves program requirements.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_programreq_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\programreq_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a program requirement record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_programreq_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\programreq_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a program requirement.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_programreq_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\programreq_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new program requirement.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_programreq_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\programreq_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a program requirement.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
     // The name of your web service function.
    'local_gcs_permittedcourses_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\permittedcourses_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves permitted courses.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_permittedcourse_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\permittedcourse_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a program permitted course record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_permittedcourse_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\permittedcourse_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a permitted course.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_permittedcourse_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\permittedcourse_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new permitted course.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_permittedcourse_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\permittedcourse_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a permitted course.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_instructors_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\instructors_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list of instructors.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_courses_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\courses_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list of courses.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_course_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\course_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a course record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_course_get_by_coursecode' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\course_get_by_coursecode',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a course record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_course_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\course_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a course record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_course_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\course_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new course record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_course_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\course_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a course record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_classes_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list of classes.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_class_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\class_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a class record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_class_get_by_code_and_term' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\class_get_by_code_and_term',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a class record for coursecode and term.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_class_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\class_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a class record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_class_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\class_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new class record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_class_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\class_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a class record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_classes_roster_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_roster_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the list of classes with their roster for a given term.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_classes_taken_get_all' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_taken_get_all',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list classes taken.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_classes_taken_get_unsigned' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_taken_get_unsigned',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the unsigned list of classes taken.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_classes_taken_get_unsigned_by_term' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_taken_get_unsigned_by_term',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the list of classes taken in a given term with unsigned enrollment agreements.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_classes_taken_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_taken_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a classes taken record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_classes_taken_get_by_stu_year' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_taken_get_by_stu_year',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a classes taken record by logical key.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_classes_taken_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_taken_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a classes taken record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_classes_taken_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_taken_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new classes taken record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_classes_taken_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_taken_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a classes taken record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_sch_cats_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_cats_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list accounting scholarship categories.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_cat_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_cat_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves an accounting scholarship category.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_cat_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_cat_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates an accounting scholarship category.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_cat_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_cat_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new accounting scholarship category.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_cat_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_cat_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes an accounting scholarship category.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_students_get_all' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\students_get_all',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list students.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_students_get_with_unsigned_enrollment_agreements' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\students_get_with_unsigned_enrollment_agreements',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the list students with current term unsigned enrollment agreements.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_students_get_with_scholarships' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\students_get_with_scholarships',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the list students with scholarships.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_students_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\students_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a student record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_students_get_by_email' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\students_get_by_email',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a student record by email address.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_students_get_by_name' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\students_get_by_name',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a student record by partial name.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_students_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\students_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a student record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_students_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\students_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new student record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_students_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\students_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a student record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_student_get_logged_in' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\student_get_logged_in',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the logged-in user student record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_enrollment_agreements_get_all' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\enrollment_agreements_get_all',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list of enrollment agreements.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_enrollment_agreements_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\enrollment_agreements_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves an enrollment agreement record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_enrollment_agreements_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\enrollment_agreements_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates an enrollment agreement record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_enrollment_agreements_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\enrollment_agreements_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new enrollment agreement record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_enrollment_agreements_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\enrollment_agreements_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes an enrollment agreement record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_term_dates_get_all' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\term_dates_get_all',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list term definitions.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_term_dates_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\term_dates_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a term definition record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_term_dates_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\term_dates_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a term definition record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_term_dates_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\term_dates_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new term definition record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_term_dates_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\term_dates_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a term definition record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_sch_available_get_all' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_available_get_all',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list scholarships available.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_available_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_available_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a scholarships available record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_available_get_by_code' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_available_get_by_code',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a scholarships available record by code.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_available_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_available_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a scholarships available record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_available_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_available_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new scholarships available record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_available_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_available_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a scholarships available record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    // The name of your web service function.
    'local_gcs_sch_given_get_all' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_given_get_all',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves the current list scholarships given.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_given_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_given_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a scholarships given record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_given_get_logical' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_given_get_logical',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a scholarships given record by logical key.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_given_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_given_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a scholarships given record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_given_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_given_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new scholarships given record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_sch_given_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\sch_given_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a scholarships given record.',

        // Options include read, and write.
        'type'        => 'write',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_enrollments_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\enrollments_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Gets enrollments for a student.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_program_req_completed_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\program_req_completed_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Gets required program courses completed for a student.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_regfox_get_unprocessed_webhooks' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\regfox_get_unprocessed_webhooks',

        // A brief, human-readable, description of the web service function.
        'description' => 'Get list of unprocessed webhooks.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_regfox_process_webhooks' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\regfox_process_webhooks',

        // A brief, human-readable, description of the web service function.
        'description' => 'Process any queued webhooks from RegFox.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_regfox_trigger_processing' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\regfox_trigger_processing',

        // A brief, human-readable, description of the web service function.
        'description' => 'Trigger RegFox processing task.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_regfox_get_unprocessed_registrants' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\regfox_get_unprocessed_registrants',

        // A brief, human-readable, description of the web service function.
        'description' => 'Get list of unprocessed registrants from RegFox.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_regfox_process_registrant' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\regfox_process_registrant',

        // A brief, human-readable, description of the web service function.
        'description' => 'Process a selected unprocessed registrant from RegFox.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_regfox_process_registrants' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\regfox_process_registrants',

        // A brief, human-readable, description of the web service function.
        'description' => 'Process any unprocessed registrants from RegFox.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_regfox_registrant_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\regfox_registrant_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates registrant record from RegFox.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_settings_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\settings_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Gets the program management plugin settings.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_trigger_task' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\trigger_task',

        // A brief, human-readable, description of the web service function.
        'description' => 'Trigger an adhoc task.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_users_get_all' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\users_get_all',

        // A brief, human-readable, description of the web service function.
        'description' => 'Gets the program management plugin settings.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_users_get_instructors' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\users_get_instructors',

        // A brief, human-readable, description of the web service function.
        'description' => 'Gets the program management plugin settings.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_enrollment_agreement_info_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\enrollment_agreement_info_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Gets the formatted enrollment agreement info.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_classes_taken_agreement_info_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\classes_taken_agreement_info_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Gets the formatted enrollment agreement info.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_table_record_dependencies' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\table_record_dependencies',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a list of table names and record ids of record dependents.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_table_field_def_get_by_tableid' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\table_field_def_get_by_tableid',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a list of field definitions given a tableid.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_table_field_def_get' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\table_field_def_get',

        // A brief, human-readable, description of the web service function.
        'description' => 'Retrieves a field definition record given its id.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_table_field_def_update' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\table_field_def_update',

        // A brief, human-readable, description of the web service function.
        'description' => 'Updates a field definition record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_table_field_def_insert' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\table_field_def_insert',

        // A brief, human-readable, description of the web service function.
        'description' => 'Inserts a new field definition record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
    'local_gcs_table_field_def_delete' => [
        // The name of the namespaced class that the function is located in.
        'classname'   => 'local_gcs\external\table_field_def_delete',

        // A brief, human-readable, description of the web service function.
        'description' => 'Deletes a field definition record.',

        // Options include read, and write.
        'type'        => 'read',

        // Whether the service is available for use in AJAX calls from the web.
        'ajax'        => true,

        // An optional list of services where the function will be included.
        'services' => [
            // A standard Moodle install includes one default service:
            // - MOODLE_OFFICIAL_MOBILE_SERVICE.
            // Specifying this service means that your function will be available for
            // use in the Moodle Mobile App.
            'gcs',
        ],
    ],
];
$functionlist = array_keys($functions);
$settings = new \local_gcs\settings();
$services = [
        $settings->pluginname => [
                'functions' => $functionlist,
                'restrictedusers' => 0, // If 1, the administrator must manually select which user can use this service.
                                        // (Administration > Plugins > Web services > Manage services > Authorised users).
                'enabled' => 1,         // If 0, then token linked to this service won't work.
                'shortname' => 'gcs', // Short name used to refer to this service from elsewhere including when fetching a token.
        ],
];
