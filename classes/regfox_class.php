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
 * Defines a RegFox class, part of a RegFox transaction
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;

class regfox_class {
    /** @var int record id */
    public $id;
    /** @var int registrant id */
    public $regid;
    /** @var string course code */
    public $coursecode;
    /** @var string title */
    public $title;
    /** @var string credit type code */
    public $credittypecode;
    /** @var float cost of this class */
    public $cost;
    /** @var float amount actually paid */
    public $paid;
    /** @var float discount amount */
    public $discount;
    /** @var int time processed */
    public $processedtime;
    /** @var int classes taken record id */
    public $ctrid;

    /**
     * Initializes a regfox class record
     *
     * @param none for blank record
     *     or int $id to read from database
     *     or object $rec to build from object properties
     */
    public function __construct() {
        $args = func_get_args();
        $argc = func_num_args();
        if ($argc == 0) {
            // Initialize blank regfox class record.
            $this->id = 0;
            $this->regid = 0;
            $this->coursecode = '';
            $this->title = '';
            $this->credittypecode = '';
            $this->cost = 0;
            $this->paid = 0;
            $this->discount = 0;
            $this->processedtime = 0;
            $this->ctrid = 0;
        } else if (gettype($args[0]) == 'int') {
            // Read from database.
            $rec = data::get_regfox_class($args[0]);
            $this->id = $rec->id;
            $this->regid = $rec->regid;
            $this->coursecode = $rec->coursecode;
            $this->title = $rec->title;
            $this->credittypecode = $rec->credittypecode;
            $this->cost = $rec->cost;
            $this->paid = $rec->paid;
            $this->discount = $rec->discount;
            $this->processedtime = $rec->processedtime;
            $this->ctrid = $rec->ctrid;
        } else {
            // Build from object properties.
            $rec = $args[0];
            $this->id = $rec->id;
            $this->regid = $rec->regid;
            $this->coursecode = $rec->coursecode;
            $this->title = $rec->title;
            $this->credittypecode = $rec->credittypecode;
            $this->cost = $rec->cost;
            $this->paid = $rec->paid;
            $this->discount = $rec->discount;
            $this->processedtime = $rec->processedtime;
            if (property_exists($rec, 'ctrid')) {
                $this->ctrid = $rec->ctrid;
            } else {
                $this->ctrid = 0;
            }
        }
    }

    /**
     * Saves a regfox class record to the database
     */
    public function save() {
        if ($this->id == 0) {
            $this->id = data::insert_regfox_class($this);
        } else {
            data::update_regfox_class($this);
        }
    }
}
