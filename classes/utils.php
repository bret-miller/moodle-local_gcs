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
 * General utilities for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_gcs;

/**
 * Data services for local_gcs web services
 */
class utils {
    /**
     * Send an email
     *
     * @param string $to who the email is sent to
     * @param string $subject the subject of the email
     * @param string $message the HTML content of the email
     * @param string $from who the email is sent from, can be blank to use from email in settings
     */
    public static function send_email ($to, $subject, $message, $from='') {

        // When live, send to intended recipient.
        if (self::is_live()) {
            self::send_email_internal($to, $subject, $message, $headers);
            return;
        }

        // BELOW IS ONLY EXECUTED WHEN NOT IN LIVE WEBSITE.
        // Send to us instead.
        $debugsubject = $subject . " - sent to you instead of " . $to;
        self::send_notification_email($debugsubject, $message);
    }

    /**
     * Send an email. This is used to send the actual email by other functions.
     *
     * @param string $to who the email is sent to
     * @param string $subject the subject of the email
     * @param string $message the HTML content of the email
     * @param string $from who the email is sent from, can be blank to use from email in settings
     */
    private static function send_email_internal ($to, $subject, $message, $from='') {
        if ($from == '') {
            $settings = new \local_gcs\settings();
            $fromemail = $settings->fromemail;
        } else {
            $fromemail = $from;
        }
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $fromemail . "\r\n";
        $headers .= 'Reply-To: ' . $fromemail . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);
    }

    /**
     * Send a notification email
     *
     * @param string $subject the subject of the email
     * @param string $message the HTML content of the email
     */
    public static function send_notification_email ($subject, $message) {
        $settings = new \local_gcs\settings();
        $fromemail = $settings->fromemail;
        if (!self::is_live()) {
            $ntfsubj = '(' . self::get_instance_label() . ') ' . $subject;
        } else {
            $ntfsubj = $subject;
        }
        foreach ($settings->notificationemails as $to) {
            self::send_email_internal($to, $ntfsubj, $message, $fromemail);
        }
    }

    /**
     * Is this the production/live Moodle instance?
     *
     * @return bool true if is the live instance
     */
    public static function is_live () {
        return (self::get_instance_label() == 'Live');
    }

    /**
     * Get the Moodle instance label
     *
     * @return string Moodle instance label
     */
    public static function get_instance_label () {
        global $CFG;
        // Can't use the $_SERVER global because it only works on web, not in CLI or tasks.
        $siteurl = new \moodle_url($CFG->wwwroot);
        $hostname = $siteurl->get_host();
        $settings = new \local_gcs\settings();
        $sitelabel = 'Snapshot';
        $livehosts = explode(',', $settings->livesites);
        foreach ($livehosts as $livehost) {
            if ( $hostname == $livehost) {
                $sitelabel = 'Live';
            }
        }
        $testhosts = explode(',', $settings->testsites);
        foreach ($testhosts as $testhost) {
            if ( $hostname == $testhost) {
                $sitelabel = 'Test';
            }
        }
         $devhosts = explode(',', $settings->devsites);
        foreach ($devhosts as $devhost) {
            if ( $hostname == $devhost) {
                $sitelabel = 'Dev';
            }
        }
        return $sitelabel;
    }
}
