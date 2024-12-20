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
 * Ajax handling for the RegFox Processing page.
 *
 * @package    local_gcs
 * @copyright  2023-2024 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Global variables.
var local_gcs_matchregid;
var local_gcs_matched_students;
var local_gcs_processing_registrant;
var local_gcs_processing_registrant_el;
var local_gcs_ajax = false;

/**
 * Get and display the count of unprocessed webhooks and registrants.
 *
 * @param none
 */
async function local_gcs_get_unprocessed_webhooks() {
    if (!local_gcs_ajax) {
        local_gcs_ajax = new local_gcs_webservice();
    }
    // Get the data we need to display an unprocessed webhook count and the unmatched registrants.
    local_gcs_ajax.queue('local_gcs_regfox_get_unprocessed_webhooks', []);
    local_gcs_ajax.queue('local_gcs_regfox_get_unprocessed_registrants', []);
    var r = await local_gcs_ajax.process();
    // Display unprocessed webhook count.
    var hookcountdiv = document.getElementsByClassName('gcswebhooks')[0].childNodes[0];
    hookcountdiv.innerText = r[0].data.length + ' unprocessed webhooks';
    document.getElementById('gcsprocessbutton').innerText='Process Webhooks';
    // Display unmatched registrants.
    var students = r[1]['data'];
    var div = contentdiv = document.getElementsByClassName('gcscontent')[0];
    html = '<h3>' + students.length + " unprocessed RegFox Registrants</h3>\n";
    if (students.length > 0) {
        html += "<table><thead><tr><th>Name</th><th>Email</th><th></th></tr></thead><tbody>\n";
        for ( x=0; x<students.length; x++ ) {
            if ( (x+1) % 2 == 0 ) {
                oddoreven = 'even';
            } else {
                oddoreven = 'odd';
            }
            html += '<tr id="reg' + students[x].id + '" class="' + oddoreven + '">'
            html += '    <td class="name">' + students[x].firstname + ' ' + students[x].lastname + '</td>';
            html += '    <td class="email">' + students[x].email + '</td>';
            html += '    <td class="match"><button onclick="local_gcs_process_registrant(this);">Process</button>'; 
            html += "</tr>\n";
        }
        html += "</tbody></table>\n";
    }
    contentdiv.innerHTML = html;
    return;
}

/**
 * Trigger the task that processes webhooks.
 *
 * @param none
 */
async function local_gcs_process_webhooks() {
    document.getElementById('gcsprocessbutton').innerText='Processing...';
    local_gcs_ajax.queue('local_gcs_regfox_process_webhooks', []);
    var r = await local_gcs_ajax.process();
    setTimeout( function() {
        local_gcs_get_unprocessed_webhooks();
    }, 60*1000);
    return;
}

/**
 * Trigger other adhoc tasks
 *
 * @param string tasksel task identifier
 */
async function local_gcs_trigger_task(tasksel) {
    local_gcs_ajax.queue('local_gcs_trigger_task', {'tasksel':tasksel});
    var r = await local_gcs_ajax.process();
	alert('Task triggered.');
    return;
}

/**
 * Process a single registrant.
 *
 * @param dom element that was clicked. Used to find the registrant id.
 */
async function local_gcs_process_registrant(el) {
    local_gcs_processing_registrant_el = el;
    // Get the registrant record id and email from the table row.
    var row = el.parentNode.parentNode;
    local_gcs_matchregid = row.getAttribute('id');
    var regid = parseInt(local_gcs_matchregid.substring(3));
    var email = row.childNodes[3].innerText;
    // Try to process the registrant.
    local_gcs_ajax.queue('local_gcs_regfox_process_registrant', {'id':regid});
    var r = await local_gcs_ajax.process();
    var result = r[0]['data'][0];
    var log = result.log;
    document.getElementsByClassName('response')[0].innerHTML = '<pre>'+log+"</pre>\n";
    if (result.processed) {
        local_gcs_get_unprocessed_webhooks();
    }
    // We couldn't process the registrant, see if we can prompt the user to match with a student.
    rfreg = result.registrant;
    local_gcs_processing_registrant = rfreg;
    if (!rfreg.studentid) {
        var fn = rfreg.firstname.substring(0,1);
        var ln = rfreg.lastname.substring(0,1);
        // Get potential matches based on first and last initial.
        local_gcs_ajax.call('local_gcs_students_get_by_name', {'firstname':fn, 'lastname':ln}, local_gcs_got_potential_matches);
    }
    return;

/**
 * Display a dialog of potential matches for this registrant.
 *
 * @param array matches list of potential matches
 */
function local_gcs_got_potential_matches(matches) {
        var row = document.getElementById(local_gcs_matchregid);
        var name = row.childNodes[1].innerText
        local_gcs_matched_students = matches;
        var html = '<dialog id="local_gcs_match_dialog" style="width:33%;">' + "\n";
        html += "<p>Unable to match registrant (" + name + ") to a student.";
        html += "Please select a student from the list of possible matches.</p>\n";
        html += '<select id="local_gcs_match_select" onchange="local_gcs_match_selected(this)">' + "\n";
        html += '<option value="">-- Select the student that matches --</option>' + "\n";
        for (x=0; x<matches.length; x++) {
            html += '   <option value="' + matches[x].id + '">';
            html += matches[x].preferredfirstname + ' ' + matches[x].legallastname;
            html += "</option>\n";
        }
        html += '<option value="act">Show all active students</option>' + "\n";
        html += '<option value="all">Show all students</option>' + "\n";
        html += "</select></dialog>";
		var dlg = document.getElementById('local_gcs_match_dialog');
		if (dlg) {
			dlg.outerHTML = html;
		} else {
			document.getElementsByTagName('body')[0].innerHTML += html;
		}
        document.getElementById('local_gcs_match_dialog').showModal();
    }
}

/**
 * Apply the selection made by the user to the registrant and try to process.
 *
 * @param select dom element
 */
async function local_gcs_match_selected(el) {
    var studentid = el.options[el.selectedIndex].value;
    var dlg = document.getElementById('local_gcs_match_dialog');
    dlg.close(); // Close the modal dialog.
    dlg.outerHTML = ''; // Destroy the modal dialog.
    // If the user selected "all students" or "active students", get the full list and redisplay.
    if (studentid == 'act') {
        onlyactive = true;
    } else {
        onlyactive = false;
    } 
    if ((studentid == 'act') || (studentid == 'all')) {
        local_gcs_ajax.call('local_gcs_students_get_all',
            {'onlyactive' : onlyactive}, local_gcs_got_potential_matches);
    } else if (studentid == '') {
        // Nothing selected, so do nothing.
    } else {
        // Add the email to the regfox email list for the selected student.
        local_gcs_processing_registrant.studentid = parseInt(studentid);
        var student = local_gcs_matched_students.find( ({id}) => id === parseInt(studentid));
        if (!student.regfoxemails) {
            student.regfoxemails = local_gcs_processing_registrant.email;
        } else {
            student.regfoxemails += ',' + local_gcs_processing_registrant.email;
        }
        local_gcs_ajax.queue('local_gcs_students_update', {'rec' : student});
        var r = await local_gcs_ajax.process();
        // Add the student record id to the registrant record.
        local_gcs_ajax.queue('local_gcs_regfox_registrant_update', {'rec' : local_gcs_processing_registrant});
        var r = await local_gcs_ajax.process();
        // Attempt to process again.
        local_gcs_process_registrant(local_gcs_processing_registrant_el);
    }
}

function local_gcs_process_registrants() {
    local_gcs_ajax.call('local_gcs_regfox_process_registrants',[],local_gcs_process_registrants_callback);
}

function local_gcs_process_registrants_callback(data) {
	var result = ['data'][0];
	var log = result.log;
	document.getElementsByClassName('response')[0].innerHTML = '<pre>'+log+"</pre>\n";
	local_gcs_get_unprocessed_webhooks();
}

setTimeout('local_gcs_get_unprocessed_webhooks()',100);