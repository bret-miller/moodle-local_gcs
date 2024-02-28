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
        
		// when live, send to intended recipient
		if (utils::is_live()) {
			mail($to, $subject, $message, $headers);
			return;
		}
		
		// BELOW IS ONLY EXECUTED WHEN NOT IN LIVE WEBSITE
		// send to us instead
		$settings = new \local_gcs\settings();
		// but leave be if $to is present in our list (this means one of us is the target)
		foreach ($settings->notificationemails as $debugemail) {
			if (strcasecmp($debugemail, $to) == 0) {
				mail($to, $subject, $message, $headers);
				return;
			}
		}
		
		// send to internal
		$debugsubject = $subject . " (TEST - sent to you instead of " . $to . ")";
		foreach ($settings->notificationemails as $debugemail) {
			mail($debugemail, $debugsubject, $message, $headers);
		}
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
        foreach ($settings->notificationemails as $to) {
            utils::send_email($to, $subject, $message, $fromemail);
        }
    }

    /**
     * Send a notification email
     *
     * @param string $subject the subject of the email
     * @param string $message the HTML content of the email
     */
    public static function is_live () {
        //$f = fopen(__DIR__ . "/glenndebug.log", "w");
        //fwrite($f, print_r($_SERVER, true));
        //fwrite($f, "------------------------------------------------------------------------------------------\n");
        //fclose($f);
		
		return $$_SERVER['HTTP_HOST'] == 'learn.gcs.edu';
   }
}
