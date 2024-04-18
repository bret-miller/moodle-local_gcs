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
 * Admin settings for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if (is_siteadmin()) {
    $s = new \local_gcs\settings();
    $settings = new admin_settingpage('local_gcs', $s->pluginname);
    $ADMIN->add('localplugins', $settings);

    // Plugin name for adminstrator interface.
    $name = 'local_gcs/pluginname';
    $title = get_string('setting_pluginname', 'local_gcs');
    $description = get_string('setting_pluginname_desc', 'local_gcs');
    $default = $s->pluginname;
    $setting = new admin_setting_configtext($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // Menu folder for administration.
    $name = 'local_gcs/menuadmin';
    $title = get_string('setting_menufolderadmin', 'local_gcs');
    $description = get_string('setting_menufolderadmin_desc', 'local_gcs');
    $default = get_string('menufolderadmin', 'local_gcs');
    $setting = new admin_setting_configtext($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // Menu folder for reports.
    $name = 'local_gcs/menureports';
    $title = get_string('setting_menufolderreports', 'local_gcs');
    $description = get_string('setting_menufolderreports_desc', 'local_gcs');
    $default = get_string('menufolderreports', 'local_gcs');
    $setting = new admin_setting_configtext($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // Menu folder for student resources.
    $name = 'local_gcs/menustudent';
    $title = get_string('setting_menufolderstud', 'local_gcs');
    $description = get_string('setting_menufolderstud_desc', 'local_gcs');
    $default = get_string('menufolderstud', 'local_gcs');
    $setting = new admin_setting_configtext($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // Should we print a logo on reports?
    $name = 'local_gcs/printlogoenabled';
    $title = get_string('setting_printlogoenabled', 'local_gcs');
    $description = get_string('setting_printlogoenabled_desc', 'local_gcs');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // Logo file setting.
    $name = 'local_gcs/printlogo';
    $title = get_string('setting_printlogo', 'local_gcs');
    $description = get_string('setting_printlogo_desc', 'local_gcs');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0,
        ['maxfiles' => 1, 'accepted_types' => ['.jpg', '.png']]);
    $settings->add($setting);

    // Default from email address for emails sent from the plugin.
    $name = 'local_gcs/fromemail';
    $title = get_string('setting_fromemail', 'local_gcs');
    $description = get_string('setting_fromemail_desc', 'local_gcs');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // To whom we send email notifications.
    $name = 'local_gcs/notificationemails';
    $title = get_string('setting_notificationemails', 'local_gcs');
    $description = get_string('setting_notificationemails_desc', 'local_gcs');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // Suppress notifications in non-live instances?
    $name = 'local_gcs/notificationsenabled';
    $title = get_string('setting_notificationsenabled', 'local_gcs');
    $description = get_string('setting_notificationsenabled_desc', 'local_gcs');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // Live instances.
    $name = 'local_gcs/livesites';
    $title = get_string('setting_livesites', 'local_gcs');
    $description = get_string('setting_livesites_desc', 'local_gcs');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // Test instances.
    $name = 'local_gcs/testsites';
    $title = get_string('setting_testsites', 'local_gcs');
    $description = get_string('setting_testsites_desc', 'local_gcs');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, true, false);
    $settings->add($setting);

    // Dev instances.
    $name = 'local_gcs/devsites';
    $title = get_string('setting_devsites', 'local_gcs');
    $description = get_string('setting_devsites_desc', 'local_gcs');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, true, false);
    $settings->add($setting);
}
