// Global variables.
var local_gcs_students = false;
var local_gcs_orderby = false;
var local_gcs_programs = [];
var local_gcs_program_completion = false;
var local_gcs_classes_taken = false;
var local_gcs_status = [];
var local_gcs_country = [];
var local_gcs_grade_codes = [];
var local_gcs_term_codes = [];
var local_gcs_courses = [];
var local_gcs_ajax = false;

async function local_gcs_ready() {
    var eol = "\n";
    var ssel = document.getElementById('statussel');
    var osel = document.getElementById('orderby');
    var wsel = document.getElementById('showsel');
    var rdiv = document.getElementsByClassName('reportcontent');
    
    // See if enough of document is loaded or wait a bit longer.
    if (!ssel || !osel || !rdiv.length || !local_gcs_webservice) {
        setTimeout('local_gcs_ready()',100);
    } else {
        local_gcs_ajax = new local_gcs_webservice();
        ssel.onchange = function() { local_gcs_show_students(); }
        osel.onchange = function() { local_gcs_show_students(); }
		wsel.onchange = function() { local_gcs_new_showsel(this); }
        local_gcs_ajax.queue('local_gcs_students_get_all',{'onlyactive' : false},local_gcs_got_students);
        local_gcs_ajax.queue('local_gcs_programs_get',{},local_gcs_got_programs);
        local_gcs_ajax.queue('local_gcs_codes_get',{'codeset' : 'status'},local_gcs_got_statuscodes);
        var r = await local_gcs_ajax.process();
        local_gcs_students = r[0].data;
        var psopt = '<option value="" selected="selected">All Programs</option>' + eol;;
        r[1].data.forEach( (p) => {
            local_gcs_programs[p.programcode] = p.title;
            psopt += '<option value="' + p.programcode + '">' + p.title + '</option>' + eol;
        });
        var psel = document.getElementById('progsel');
        psel.innerHTML = psopt;
        psel.onchange = function() { local_gcs_show_students(); }
        r[2].data.forEach( (c) => {
            local_gcs_status[c.code] = c.description;
        });
        local_gcs_show_students();
    }
}

function local_gcs_got_students(students) {
    local_gcs_students = students;
    local_gcs_show_students();
}

function local_gcs_got_programs(programs) {
    var eol = "\n";
    var psopt = '<option value="" selected="selected">All Programs</option>' + eol;;
    programs.forEach( (p) => {
        local_gcs_programs[p.programcode] = p.title;
        psopt += '<option value="' + p.programcode + '">' + p.title + '</option>' + eol;
    });
    var psel = document.getElementById('progsel');
    psel.innerHTML = psopt;
    psel.onchange = function() { local_gcs_show_students(); }
}

function local_gcs_got_statuscodes(codes) {
    codes.forEach( (c) => {
        local_gcs_status[c.code] = c.description;
    });
}

async function local_gcs_new_showsel(wsel) {
	var showopt = wsel[wsel.selectedIndex].value;
	if (showopt == 'p') {
		// Show programs each student completed.
		// Load the program completion table if we don't have it.
		if (!local_gcs_program_completion) {
			local_gcs_ajax.queue('local_gcs_program_completion_get_all',{});
			var r = await local_gcs_ajax.process();
			local_gcs_program_completion = r[0].data;
			// Sort the table by studentid and completion date.
			local_gcs_program_completion.sort( (a,b) => {
				var rv = 0;
				if (a.studentid < b.studentid) {
					rv = -1;
				} else if (a.studentid > b.studentid) {
					rv = 1;
				} else if (a.completiondate < b.completiondate) {
					rv = -1;
				} else if (a.completiondate > b.completiondate) {
					rv = 1;
				}
				return rv;
			});
		}
	} else if (showopt == 'c') {
		// Show classes each student has taken.
		// Load the classes taken table and grade codes if we don't have them.
		if (!local_gcs_classes_taken) {
			local_gcs_ajax.queue('local_gcs_classes_taken_get_all',{'stuid':0});
			local_gcs_ajax.queue('local_gcs_codes_get',{'codeset':'grade'});
			local_gcs_ajax.queue('local_gcs_codes_get',{'codeset':'term'});
			local_gcs_ajax.queue('local_gcs_courses_get',{});
			var r = await local_gcs_ajax.process();
			local_gcs_classes_taken = r[0].data;
			r[1].data.forEach((gc) => {
				local_gcs_grade_codes[gc.code] = gc.description;
			});
			local_gcs_grade_codes[''] = '';
			r[2].data.forEach((tc) => {
				local_gcs_term_codes[tc.code] = tc.description;
			});
			r[3].data.forEach((crs) => {
				local_gcs_courses[crs.coursecode] = crs;
			});
			// Sort the table by studentid and term.
			local_gcs_classes_taken.sort( (a,b) => {
				var rv = 0;
				if (a.studentid < b.studentid) {
					rv = -1;
				} else if (a.studentid > b.studentid) {
					rv = 1;
				} else if (a.termyear < b.termyear) {
					rv = -1;
				} else if (a.termyear > b.termyear) {
					rv = 1;
				} else if (a.termcode < b.termcode) {
					rv = -1;
				} else if (a.termcode > b.termcode) {
					rv = 1;
				} else if (a.coursecode < b.coursecode) {
					rv = -1;
				} else if (a.coursecode > b.coursecode) {
					rv = 1;
				}
				return rv;
			});
		}
	}
	local_gcs_show_students();
}

function local_gcs_show_students() {
    var ssel = document.getElementById('statussel');
    var osel = document.getElementById('orderby');
    var psel = document.getElementById('progsel');
    var csel = document.getElementById('ctrysel');
	var wsel = document.getElementById('showsel');
    var rdiv = document.getElementsByClassName('reportcontent')[0];
    var onlyactive = parseInt(ssel[ssel.selectedIndex].value);
    var orderby = osel[osel.selectedIndex].value;
	var showopt = wsel[wsel.selectedIndex].value;
    var selectedprogram = '';
    var selectedcountry = '* All Countries';
    if (orderby == '') {
        return; // We need an order selected before we can display.
    }
    if (orderby == 'p') {
        psel.className = 'shown';
        csel.className = 'hidden';
        if (psel.selectedIndex != -1) {
            selectedprogram = psel[psel.selectedIndex].value;
        }
    } else if (orderby == 's') {
        psel.className = 'hidden';
        csel.className = 'shown';
        if (csel.selectedIndex != -1) {
            selectedcountry = csel[csel.selectedIndex].value;
        }
    } else {
        psel.className = 'hidden';
        csel.className = 'hidden';
    }
    local_gcs_orderby = orderby;
    local_gcs_students.sort((a, b) => {
        var countrycode;
        if (local_gcs_orderby == 'p') {
            fa = local_gcs_programs[a.programcode].toLowerCase();
            fb = local_gcs_programs[b.programcode].toLowerCase();
        } else if (local_gcs_orderby == 'n') {
            fa = '';
            fb = '';
        } else {
            if ((a.country == 'United States')||(a.country == '')) {
                countrycode = '  us';
            } else {
                countrycode = a.country;
            }
            fa = countrycode + a.stateprovince.toLowerCase();
            if ((b.country == 'United States')||(b.country == '')) {
                countrycode = '  us';
            } else {
                countrycode = b.country;
            }
            fb = countrycode + b.stateprovince.toLowerCase();
        }
        lna = a.legallastname.toLowerCase();
        lnb = b.legallastname.toLowerCase();
        fna = a.legalfirstname.toLowerCase();
        fnb = b.legalfirstname.toLowerCase();
        var rv = 0;
        if (fa < fb) {
            rv = -1;
        } else if (fa > fb) {
            rv = 1;
        } else if (lna < lnb) {
            rv = -1;
        } else if (lna > lnb) {
            rv = 1;
        } else if (fna < fnb) {
            rv = -1;
        } else if (fna > fnb) {
            rv = 1
        }
        return(rv);
    });
    var countries = ['* All Countries','United States'];
    var eol = "\n";
    var rpt= '<table><thead><tr>\
        <th class="name">Student name</th>\
        <th class="state">State/Country</th>\
        <th class="status">Status</th>\
        <th class="program">Program</th>';
	if (showopt == 'p') {
		rpt += '<th class="completed">Completed</th>';
	}
	rpt += '</tr></thead>' + eol;
    rpt +='<tbody>'+eol;
	scnt = 0;
    local_gcs_students.forEach( (student) => {
        if (((student.statuscode == 'ACT') || !onlyactive) 
            && ((student.programcode==selectedprogram) || selectedprogram == '') 
            && ((student.country==selectedcountry) || selectedcountry == '* All Countries')) {
			scnt++;
			var oddoreven = 'even';
			if (scnt%2==1) {
				oddoreven = 'odd';
			}
				
            rpt += '<tr class="' + oddoreven + '">';
			rpt += '<td class="name" data-studentid="' + student.id + '">' + student.preferredfirstname;
			rpt += ' ' + student.legallastname + '</td>' + eol;
            rpt += '<td class="state">';
            if (((student.country == 'United States') || (student.country == '')) && (student.stateprovince != '')) {
                rpt += student.stateprovince;
            } else {
                if (!countries.includes(student.country)) {
                    countries.push(student.country);
                }
                rpt += student.country;
            }
            rpt += '</td><td class="status">' + local_gcs_status[student.statuscode] + '</td>' + eol;
            rpt += '<td class="program">' + local_gcs_programs[student.programcode] + '</td>' + eol;
			if (showopt == 'p') {
				rpt += '<td class="completed">(enrolled)</td>';
			}
			rpt += '</tr>' + eol;
			if (showopt == 'p') {
				// Show programs completed.
				pcmp = local_gcs_program_completion.filter( (p) => ((p.studentid == student.id)&&(p.source=='GCS')) );
				pcmp.forEach( (p) => {
					rpt += '<tr class="' + oddoreven + '">';
					rpt += '<td colspan="3">&nbsp;</td><td class="program">' + local_gcs_programs[p.programcode] + '</td>';
					rpt += '<td class="completed">';
					if (p.completiondate == 0) {
						rpt += '(enrolled)';
					} else {
						let cd = new Date(p.completiondate * 1000);
						let cdstr = new Intl.DateTimeFormat().format(cd);
						rpt += cdstr;
					}
					rpt += '</td></tr>' + eol;
				});
			} else if (showopt == 'c') {
				// Show classes taken.
				rpt += '<tr class="' + oddoreven + '"><td colspan="4">' + eol;
				rpt += '    <table><thead><tr><th class="course">Course</th><th class="term">Class Term</th>';
				rpt += '    <th class="transfer">Transfer Class</th><th class="completed">Completed</th>';
				rpt += '    <th class="grade">Grade</th></tr></thead>' + eol;
				rpt += '    <tbody>' + eol;
				ct = local_gcs_classes_taken.filter( (c) => (c.studentid == student.id) );
				//if (student.id == 176) { alert('debug'); }
				ct.forEach( (ctr) => {
					var coursecode = ctr.coursecode;
					if ((ctr.assignedcoursecode != '') && (ctr.assignedcoursecode != null)) {
						coursecode = ctr.assignedcoursecode;
						if (coursecode.length = 2) {
							coursecode = ctr.coursecode;
						}
					}
					var transfertitle = ctr.titleoverride;
					if ((transfertitle == '') && (transfertitle == null)) {
						transfertitle = ctr.shorttitleoverride;
					}
					let cdstr = '(enrolled)';
					if ((ctr.completiondate != 0)&&(ctr.completiondate != null)) {
						let cd = new Date(ctr.completiondate * 1000);
						cdstr = new Intl.DateTimeFormat().format(cd);
					} else if (ctr.gradecode != '') {
						cdstr = '';
					}
					let crs = local_gcs_courses[coursecode];
					let title = '';
					if (crs) {
						title = crs.title;
					}
					rpt += '    <tr><td class="course">' + coursecode + ' - ' + title + '</td>' + eol;
					rpt += '    <td class="term">' + local_gcs_term_codes[ctr.termcode] + ' ' + ctr.termyear + '</td>' + eol;
					rpt += '    <td class="transfer">' + transfertitle + '</td>';
					rpt += '    <td class="completed">' + cdstr + '</td>';
					rpt += '    <td class="grade">' + local_gcs_grade_codes[ctr.gradecode] + '</td></tr>' +eol;
				});
				rpt += '    </tbody></table>' + eol;
				rpt += '</td></tr>' + eol;
			}
        }
    });
    rpt += '</tbody></table>' + eol;
    rdiv.innerHTML = rpt;
    countries.sort();
    if (!local_gcs_country.length) {
        local_gcs_country = countries;
        var opts='';
        var first = true;
        countries.forEach((c) => {
            opts += '<option';
            if (first) {
                opts += 'selected="selected"';
                first = false;
            }
            opts += '>' + c + '</option>' + eol;
        });
        csel.innerHTML = opts;
        csel.onchange = function () { local_gcs_show_students(); };
    }
}

setTimeout('local_gcs_ready()',100);
