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
 * Defines a RegFox transaction
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;

class regfox_transaction {
    /** @var int record id */
    public $id;
    /** @var string order status */
    public $orderstatus;
    /** @var string order number */
    public $ordernumber;
    /** @var string first name */
    public $firstname;
    /** @var string last name */
    public $lasname;
    /** @var string email */
    public $email;
    /** @var string payment method */
    public $paymethod;
    /** @var string form name */
    public $formname;
    /** @var string transaction id */
    public $transactionid;
    /** @var float transaction total */
    public $total;
    /** @var int transaction time */
    public $transactiontime;
    /** @var int processed time */
    public $timestamp;
    /** @var int id of the webhook raw data */
    public $webhookid;
    /** @var array registrants */
    public $registrants;

    /**
     * Initializes a RegFox transaction record
     *
     * @param int    $webhookid webhook record id or registrant record id
     * @param string $jsondata  optional JSON data from RegFox webhook post
     */
 
    public function __construct() {
        $args = func_get_args();
        $argc = func_num_args();
		
		if ($argc==2) {
			// Build transaction from JSON webhook data.
			$webhookid = $args[0];
			$jsondata = $args[1];
			$this->registrants = [];
			$this->id = 0;
			$hook = json_decode($jsondata);
			if ($hook) {
				$this->webhookid = $webhookid;
				$tran = $hook->data;
				$this->orderstatus = $tran->orderStatus;
				$this->ordernumber = $tran->orderNumber;
				$bill = $tran->billing;
				$this->firstname = $bill->name->first;
				$this->lastname = $bill->name->last;
				$this->email = $bill->email;
				if (property_exists($bill,'paymentMethod')) {
					$this->paymethod = $bill->paymentMethod;
				} else {
					$this->paymethod = 'no pay method';
				}
				$this->transactionid = $tran->transactionId;
				$this->total = $tran->total;
				$this->formname = $tran->formName;
				$this->transactiontime = strtotime($tran->registrationTimestamp);
				$this->timestamp = time();
				$regs = $tran->registrants;
				foreach ($regs as $regrec) {
					$reg = new regfox_registrant(null);
					$reg->from_webhook($regrec);
					array_push($this->registrants,$reg);
				}
			}
		} else {
			// Load transaction from database.
			$id = intval($args[0]);
			global $DB;
			$rec = $DB->get_record('local_gcs_regfox_transaction', ['id' => $id]);
			if ($rec) {
				$this->id = $rec->id;
				$this->orderstatus = $rec->orderstatus;
				$this->ordernumber = $rec->ordernumber;
				$this->firstname = $rec->firstname;
				$this->lasname = $rec->lastname;
				$this->email = $rec->email;
				$this->paymethod = $rec->paymethod;
				$this->formname = $rec->formname;
				$this->transactionid = $rec->transactionid;
				$this->total = $rec->total;
				$this->transactiontime = $rec->transactiontime;
				$this->timestamp = $rec->timestamp;
				$this->webhookid = $rec->webhookid;
				$this->registrants = [];
			} else {
				$this->id = 0;
			}
		}
    }

    /**
     * Saves a RegFox transaction record from the current object
     */
 	public function save() {
		$rec = [
			'id' => 0,
			'transactionid' => $this->transactionid,
			'ordernumber' => $this->ordernumber,
			'orderstatus' => $this->orderstatus,
			'email' => $this->email,
			'firstname' => $this->firstname,
			'lastname' => $this->lastname,
			'paymethod' => $this->paymethod,
			'formname' => $this->formname,
			'total' => $this->total,
			'transactiontime' => $this->transactiontime,
			'timestamp' => time(),
			'webhookid' => $this->webhookid,
		];
		$tranid = data::insert_regfox_transaction($rec);
		foreach($this->registrants as $reg) {
			$regid = $reg->save($tranid);
		}
		return $tranid;
	}
}
