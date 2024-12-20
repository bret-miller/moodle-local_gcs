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
 * Retrieves plugin settings
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;

/**
 * Retrieve settings
 */
class settings {
    /** @var string name of the program manqagement plugin */
    public $pluginname;
    /** @var string name of the program manqagement menu item */
    public $menuadmin;
    /** @var string name of the student menu item */
    public $menustudent;
    /** @var string name of the program manqagement menu item */
    public $logourl;
    /** @var boolean print the logo on reports? */
    public $printlogoenabled;
    /** @var string email address to send notifications from */
    public $fromemail;
    /** @var string email addresses to send notifications to */
    public $notificationemails;
    /** @var boolean enable notification emails in non-live? */
    public $notificationenabled;
    /** @var string hostnames that are labeled Live */
    public $livesites;
    /** @var string hostnames that are labeled Dev */
    public $devsites;
    /** @var string hostnames that are labeled Test */
    public $testsites;
    /** @var int version */
    public $version;
    /** @var string release id */
    public $release;

    /**
     * Initializes the settings class
     */
    public function __construct() {
        $config = get_config('local_gcs');
        if (property_exists($config, 'pluginname')) {
            $this->pluginname = $config->pluginname;
        } else {
            $this->pluginname = get_string('pluginname', 'local_gcs');
        }
        $pluginman = \core_plugin_manager::instance();
        $plugininfo = $pluginman->get_plugins();
        $this->menuadmin = $config->menuadmin;
        $this->menustudent = $config->menustudent;
        $this->printlogoenabled = $config->printlogoenabled;
        $this->version = $config->version;
        $this->release = $plugininfo['local']['gcs']->release;
        $this->logourl = \moodle_url::make_pluginfile_url(\context_system::instance()->id, 'local_gcs', 'logo', null,
            null, $config->printlogo);
        $this->fromemail = $config->fromemail;
        $this->notificationemails = explode(',', $config->notificationemails);
        $this->notificationenabled = $config->notificationsenabled;
        $this->livesites = $config->livesites;
        $this->devsites = $config->devsites;
        $this->testsites = $config->testsites;
    }
}
