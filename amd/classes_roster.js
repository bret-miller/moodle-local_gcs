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
 * Display the list of classes and students enrolled in those classes
 * for a given term.
 * GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Global variables.
var local_gcs_terms = false;
var local_gcs_term_codes = false;
var local_gcs_selected_term = '';
var local_gcs_ajax = false;

function local_gcs_ready() {
    var termseldiv = document.getElementsByClassName('termselect');    
    // See if enough of document is loaded or wait a bit longer.
    if ((termseldiv.length == 0) || !local_gcs_webservice) {
        setTimeout('local_gcs_ready()',100);
    } else {
        local_gcs_ajax = new local_gcs_webservice();
        local_gcs_get_terms();
    }
}

async function local_gcs_get_terms() {
    var termseldiv = document.getElementsByClassName('termselect');
    termseldiv[0].innerHTML='<select id="termselect" name="termselect"><option value="">Loading...</option></select>';
    local_gcs_ajax.queue('local_gcs_term_dates_get_all',[]);
    local_gcs_ajax.queue('local_gcs_codes_get',{'codeset' : 'term'});
	var r = await local_gcs_ajax.process();
	local_gcs_terms = r[0].data;
	local_gcs_term_codes = r[1].data;
	local_gcs_got_terms_and_codes();
}

function local_gcs_got_terms_and_codes() {
    if (!local_gcs_terms || !local_gcs_term_codes) {
        setTimeout('local_gcs_got_terms_and_codes()',500);
        return;
    }    
    terms = local_gcs_terms;
    var termsel = document.getElementById('termselect');
    var now = Date.now();
    var gotcur = false;
    var opts = '';
    for ( x=0; x<terms.length; x++) {
        var termname = local_gcs_term_codes.filter( obj => obj.code === terms[x].termcode )[0].description;
        opts += '<option value="' + terms[x].termyear + terms[x].termcode + '"';
        if (!gotcur && (x < terms.length+1)) {
            if (now > terms[x].registrationstart) {
                opts += 'selected="selected"';
                gotcur = true;
            }
        }
        opts += '>' + termname + ' ' + terms[x].termyear + '</option>';
    }
    termsel.innerHTML = opts;
    termsel.onchange = function() { local_gcs_get_roster(this); };
    local_gcs_get_roster(termsel);
}

function local_gcs_get_roster(sel) {
    var x = sel.selectedIndex;
	if (x<0) return; // If there isn't a selected term, we can't get the roster.
    var termsel = sel[x].value;
    // For some reason this has been executing twice, so we prevent it here.
    if (termsel != local_gcs_selected_term) {
        local_gcs_selected_term = termsel;
        var termyear = parseInt(termsel.substr(0,4));
        var termcode = termsel.substr(4,1);
        local_gcs_ajax.call('local_gcs_classes_roster_get',{'termyear' : termyear, 'termcode' : termcode},local_gcs_got_roster);
    }
}

function local_gcs_got_roster(roster) {
    var eol = "\n";
    var classname = '';
    var rpt = '<table><thead><tr><th class="name">Student name</th><th>Taken for</th><th>Class status</th><th>Grade</th></tr></thead>' + eol;
    rpt +='<tbody>'+eol;
    for (x=0; x<roster.length; x++) {
        if (roster[x].studentid) {
            if (roster[x].title != classname) {
                rpt += '<tr class="class"><td colspan="4"><span><span class="classname">' + roster[x].coursecode + ' - ' + roster[x].title + '</span>' + eol;
                rpt += '<span class="info">';
                rpt += '<span class="hours">' + roster[x].coursehours + ' hours</span>' + eol;
                rpt += '<span class="prof">' + roster[x].instructorname + '</span></span></span>' + eol;
                classname = roster[x].title;
            }
            rpt += '<tr><td class="name">' + roster[x].legalfirstname + ' ' + roster[x].legallastname + '</td>';
            rpt += '<td>' + roster[x].credittype + '</td>';
            rpt += '<td>' + roster[x].completion + '</td>';
            rpt += '<td>' + roster[x].grade + '</td></tr>';
        }
    }
    rpt += '</tbody></table>' + eol;

    var rptdiv = document.getElementsByClassName('reportcontent');
    rptdiv[0].innerHTML = rpt;
 }

setTimeout('local_gcs_ready()',100);