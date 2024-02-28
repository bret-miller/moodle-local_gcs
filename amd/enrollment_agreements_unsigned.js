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
 * Display the list of unsigned enrollment agreements
 * GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Global variables.
var local_gcs_terms = false;
var local_gcs_term_codes = [];
var local_gcs_courses = [];
var local_gcs_students = [];
var local_gcs_selected_term = '';
var local_gcs_ajax = false;

/**
 * Start processing. This function checks for the existence of the items it requires
 * to begin processing and delays itself until those are present.
 *
 * @param none
 */
async function local_gcs_ready() {
    var termseldiv = document.getElementsByClassName('termselect');    
    // See if enough of document is loaded or wait a bit longer.
    if ((termseldiv.length == 0) || !local_gcs_webservice) {
        setTimeout('local_gcs_ready()',100);
        return;
    }

    // Set up our class to call webservice functions.
    local_gcs_ajax = new local_gcs_webservice();

    // Get the list of academic terms, term name codes, students, and courses.
    var termseldiv = document.getElementsByClassName('termselect');
    termseldiv[0].innerHTML='<select id="termselect" name="termselect"><option value="">Loading...</option></select>';
    local_gcs_ajax.queue('local_gcs_term_dates_get_all',[]);
    local_gcs_ajax.queue('local_gcs_codes_get',{'codeset' : 'term'});
    local_gcs_ajax.queue('local_gcs_students_get_all',{'onlyactive' : false});
    local_gcs_ajax.queue('local_gcs_courses_get',{});
    var r = await local_gcs_ajax.process();
	
	// Build the term code dictionary.
    local_gcs_terms = r[0].data;
	r[1].data.forEach((tc) => {
		local_gcs_term_codes[tc.code] = tc.description;
	});

	// Build the list of courses so we can get titles easily.
	r[3].data.forEach((crs) => {
		local_gcs_courses[crs.coursecode] = crs;
	});

	// Build the list of students so we can get names easily.
	r[2].data.forEach((stu) => {
		local_gcs_students[stu.id] = stu;
	});


    // Build the select options for the term list
    var termsel = document.getElementById('termselect');
    var now = Date.now();
    var gotcur = false;
    var opts = '';
    local_gcs_terms.forEach( (term) => {
        var termname = local_gcs_term_codes[term.termcode];
        opts += '<option value="' + term.termyear + term.termcode + '"';
        if (!gotcur) {
            // Default to the current term.
            if (now > term.registrationstart) {
                opts += 'selected="selected"';
                gotcur = true;
            }
        }
        opts += '>' + termname + ' ' + term.termyear + '</option>';
    });
    termsel.innerHTML = opts;
    termsel.onchange = function() { local_gcs_get_agreements(this); };
    // Display unsigned agreements for the current term.
    local_gcs_get_agreements(termsel);
}

/**
 * Start processing. This function checks for the existence of the items it requires
 * to begin processing and delays itself until those are present.
 *
 * @param DOM <select> element to obtain the selected term.
 */
async function local_gcs_get_agreements(sel) {
    // Get the term year and term code from the selected option.
    var x = sel.selectedIndex;
    if (x<0) return; // If there isn't a selected term, we can't get the roster.
    var termsel = sel[x].value;
    // For some reason this has been executing twice, so we prevent it here.
    if (termsel == local_gcs_selected_term) {
        return;
    }
    local_gcs_selected_term = termsel;
    var termyear = parseInt(termsel.substr(0,4));
    var termcode = termsel.substr(4,1);
    
    // Get the unsigned enrollment agreements from the webservice.
    local_gcs_ajax.queue('local_gcs_classes_taken_get_unsigned_by_term',{'termyear':termyear, 'termcode':termcode});
    var r = await local_gcs_ajax.process();

    // Set up the table to list the students/classes.
    var eol = "\n";
    var rpt= '<table><thead><tr>\
        <th class="name">Student name</th>\
        <th class="class">Class</th>';
	rpt += '</tr></thead>' + eol;
    rpt +='<tbody>'+eol;
	scnt = 0;

	// List the students with unsigned agreements in the selected term.
	r[0].data.forEach( (ctr) => {
		scnt++;
		var oddoreven = 'even';
		if (scnt%2==1) {
			oddoreven = 'odd';
		}
		let student = local_gcs_students[ctr.studentid];
		let crs = local_gcs_courses[ctr.coursecode];
		var title = 'coursecode = ' + ctr.coursecode + ' (undefined)';
		if (crs) {
			var title = crs.title;
		}
		rpt += '<tr class="' + oddoreven + '">';
		rpt += '<td class="name" data-studentid="' + ctr.studentid + '">' + student.preferredfirstname;
		rpt += ' ' + student.legallastname + '</td>' + eol;
		rpt += '<td class="class">' + title + '</td></tr>' + eol;
	});
	rpt += '</tbody></table>' + eol;
	var rdiv = document.getElementsByClassName('reportcontent')[0];
	rdiv.innerHTML = rpt;
}

// Trigger startup.
setTimeout('local_gcs_ready()',100);