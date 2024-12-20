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
 * Defines a RegFox registrant, part of a RegFox transaction
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;

class regfox_registrant {
    /** @var int id */
    public $id;
    /** @var string email */
    public $email;
    /** @var string firstname */
    public $firstname;
    /** @var string lastname */
    public $lastname;
    /** @var string scholarship code */
    public $scholarshipcode;
    /** @var float total amount for this registrant */
    public $amount;
    /** @var int transaction id */
    public $tranid;
    /** @var int date/time registrant processed */
    public $processedtime;
    /** @var int student id */
    public $studentid;
    /** @var array class list */
    public $classes;

    /**
     * Initializes a RegFox registrant from transaction data
     *
     * @param int $arg registrant id or object $arg registrant data from transaction
     */

    public function __construct($arg) {
        if (!$arg) {
            // Create empty registrant.
            $this->blankrec();
        } else if ( (gettype($arg) == 'integer')
            || ( (gettype($arg) == 'string') && (intval($arg) > 0) ) ) {
            // Load registrant from database.
            $id = intval($arg);
            $recs = data::get_regfox_registrants($id);
            if (count($recs)) {
                $rec = array_pop($recs);
                $this->fill($rec);
            }
        } else {
            // Build from object properties.
            $this->fill($arg);
        }
    }

    /** Initialize blank registrant class
     *
     * @param onone
     */
    public function blankrec() {
        $this->id = 0;
        $this->email = '';
        $this->firstname = '';
        $this->lastname = '';
        $this->scholarshipcode = '';
        $this->amount = 0;
        $this->tranid = 0;
        $this->processedtime = 0;
        $this->studentid = 0;
        $this->classes = [];
    }

    /** Initialize registrant class from object properties
     *
     * @param object $rec registrant data
     */
    public function fill($rec) {
        $this->id = $rec->id;
        $this->email = $rec->email;
        $this->firstname = $rec->firstname;
        $this->lastname = $rec->lastname;
        $this->scholarshipcode = $rec->scholarshipcode;
        $this->amount = $rec->amount;
        if (is_null($this->amount)) {
            $this->amount = 0;
        }
        if (property_exists($rec, 'processedtime')) {
            $this->processedtime = $rec->processedtime;
        } else {
            $this->processedtime = 0;
        }
        $this->tranid = $rec->tranid;
        $this->classes = [];
        if (property_exists($rec, 'studentid')) {
            $this->studentid = $rec->studentid;
        } else {
            $this->studentid = 0;
        }
    }

    /**
     * Initializes a RegFox registrant from webhook transaction data
     *
     * @param object $reg registrant data from transaction
     */
    public function from_webhook($reg) {
        // Build registrant from transaction data.
        $this->blankrec();
        $this->id = 0;
        $this->amount = $reg->amount;
        if (is_null($this->amount)) {
            $this->amount = 0;
        }
        $this->classes = [];
        $regitems = $reg->data;
        $products = [];
        foreach ($regitems as $item) {
            $products = [];
            switch ($item->key) {
                case 'name':
                    $this->firstname = $item->first->value;
                    $this->lastname = $item->last->value;
                    break;
                case 'email':
                    $this->email = $item->value;
                    break;
                case 'classes':
                    $products = $item->products;
                    break;
                case 'scholarshipCode':
                    $this->scholarshipcode = $item->value;
                    break;
            }
            foreach ($products as $product) {
                $class = new regfox_class();
                $cinf = explode(' – ', $product->label);
                if (count($cinf) == 2) {
                    $class->coursecode = trim($cinf[0]);
                    $class->title = trim($cinf[1]);
                } else {
                    $class->coursecode = substr($product->label, 0, 5);
                    $class->title = substr($product->label, 8);
                }
                foreach ($product->variants as $var) {
                    $class->credittypecode = $var->label;
                    $class->cost = $var->price;
                    $class->paid = $var->amount;
                    $class->discount = $var->discount;
                }
                array_push($this->classes, $class);
            }
        }
    }


    /**
     * Removes the classes property. Required for an external service function.
     *
     */
    public function remove_classes() {
        unset($this->classes);
    }


    /**
     * Saves a RegFox registrant record from the current object
     *
     * @param int $tranid transaction id
     */
    public function save($tranid=0) {
        if ($tranid) {
            $this->tranid = $tranid;
        }
        $rec = [
            'id' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'scholarshipcode' => $this->scholarshipcode,
            'amount' => $this->amount,
            'tranid' => $this->tranid,
            'processedtime' => $this->processedtime,
            'studentid' => $this->studentid,
        ];
        if ($this->id == 0) {
            $regid = data::insert_regfox_registrant($rec);
            $this->id = $regid;
            foreach ($this->classes as $class) {
                $class->regid = $regid;
                $class->save();
            }
        } else {
            data::update_regfox_registrant($rec);
        }
        return;
    }
}
