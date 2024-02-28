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
 * Validate current session/user for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_gcs\local;

/**
 * Session security checks for GCS Program Management web service
 */
class session {
    /**
     * Check if currently signed in user is a site administrator
     * @param int $sid current session id from cookies
     * @return boolean whether the user is admin or not
     */
    public static function user_is_admin($sid) {
        global $DB;
        $hasrole = false;
        $user = get_user_from_sid($sid);
        if ( $user ) {
            $sql = "select * from {config} where name = 'siteadmins'";
            $ra = $DB->get_record_sql($sql);
            if ( $ra ) {
                $adminstr = $ra['value'];
                $admins = explode(',', $adminstr);
                if ( in_array( $user['userid'], $admins ) ) {
                    $hasrole = true;
                }
            }
        }
        return $hasrole;
    }

    /**
     * Check if currently signed in user has a specific role
     * @param  int    $sid  current session id from cookies
     * @param  string $role role to check
     * @return boolean whether the user is admin or not
     */
    public static function user_has_role($sid, $role) {
        global $DB;
        $hasrole = false;
        $user = get_user_from_sid($sid);
        if ( $user ) {
            $sql = 'select ra.*
                      from {role} r
                      join {role_assignments} ra on ra.roleid = r.id
                     where name = :role or shortname = :role and userid = :userid';
            $ra = $DB->get_record_sql(
                $sql,
                [ 'role' => $role, 'userid' => $user['userid'] ]
            );
            if ( $ra ) {
                $hasrole = true;
            }
        }
        return $hasrole;
    }

    /**
     * Retrieve the user id from the session id if the IP address matches
     * @param int $sid current session id from cookies
     * @return array [
     *     integer id
     *     string username
     *     ]
     *     or false if session is invalid
     */
    public static function get_user_from_sid($sid) {
        global $DB;
        $user = false;
        $sql = 'select * from {sessions} where sid=:sid';
        $session = $DB->get_record_sql(
            $sql,
            [ 'sid' => $sid ]
        );
        if ( $session ) {
            if ( $session['lastip'] == $_SERVER['REMOTE_ADDR'] ) {
                $sql = 'select * from {user} where id=:id';
                $ur = $DB->get_record_sql(
                    $sql,
                    [ 'id' => $session['userid'] ]
                );
                if ( $ur ) {
                    $user = [
                        'id' => $ur['id'],
                        'username' => $ur['username'],
                    ];
                }
            }
        }
        return $user;
    }
}
