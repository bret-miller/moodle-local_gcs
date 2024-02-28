// Global variables.
var local_gcs_students;
var local_gcs_enrollments = false;
var local_gcs_programs = false;

function local_gcs_call_webservice(method,parameters,callback) {
    var url = 'https://dev.gcs.edu/lib/ajax/service.php?info=' + method + '&sesskey=' + M.cfg.sesskey;
    var data = [{ index: 0, methodname: method, args: parameters }];
    var opt = {
        method: 'POST',
        headers: { "Content-Type": "application/json" },
        redirect: "follow",
        body: JSON.stringify(data),
    };
    fetch(url,opt)
    .then(resp => local_gcs_check_response(resp,callback)) // check response status, get text body
    .then(data => callback(data)) // process text body
    .catch(function(error) {
        alert('Web service call failed, '+error,1);
    });
}

function local_gcs_check_response(resp,callback) {
    if (resp.status == 200) {
        return resp.text();
    } else {
        alert('Web service call failed, status='+resp.status.toString(),1);
        return '{status:'+resp.status.toString()+'}';
    }
}

function local_gcs_get_students() {
    var studseldiv = document.getElementsByClassName('studentselect');
    if (studseldiv.length == 0) {
        setTimeout('local_gcs_get_students()',1000);
    } else {
        studseldiv[0].innerHTML='<select id="studentselect" name="studentselect"><option value="">Loading...</option></select>';
        local_gcs_call_webservice('local_gcs_students_get_all',[],local_gcs_got_students);
        local_gcs_call_webservice('local_gcs_programs_get',[],local_gcs_got_programs);
    }
}

function local_gcs_got_students(r) {
    var rsp = JSON.parse(r);
    var students = rsp[0]['data'];
    local_gcs_students = students;
    var studsel = document.getElementById('studentselect');
    var opts = '<option value="">-- Select a Student --</option>';
    for ( x=0; x<students.length; x++) {
        opts += '<option value="' + students[x].id + '">' + students[x].legallastname
            + ', ' + students[x].preferredfirstname + '</option>';
    }
    studsel.innerHTML = opts;
    studsel.onchange = function() { local_gcs_get_enrollments(this); };
    if (students.length == 1) {
        studsel.selectedIndex = 1;
        local_gcs_get_enrollments(studsel);
    }
}

function local_gcs_got_programs(r) {
    var rsp = JSON.parse(r);
    local_gcs_programs = rsp[0]['data'];;
}

function local_gcs_get_enrollments(studsel) {
    var studsel = document.getElementById('studentselect');
    var x = studsel.selectedIndex;
    var studid = parseInt(studsel[x].value);
    var pgmseldiv = document.getElementsByClassName('programselect');
    pgmseldiv[0].innerHTML='<select id="programselect" name="programselect"><option value="">Loading...</option></select>';
    local_gcs_call_webservice('local_gcs_enrollments_get',{'id':studid},local_gcs_got_enrollments);
}

function local_gcs_got_enrollments(r) {
    var rsp = JSON.parse(r);
    local_gcs_enrollments = rsp[0]['data'];;
    local_gcs_load_program_selector();
}

function local_gcs_load_program_selector() {
    var enrollments = local_gcs_enrollments;
    var programs = local_gcs_programs;
    var pgmsel = document.getElementById('programselect');
    var opts = '<option value="">-- Select a Program --</option>';
    var pgm = false;
    for ( x=0; x<programs.length; x++) {
        if (programs[x].programcode == '') {
           continue;
        }
        var e = enrollments.findIndex((enr) => enr.programcode == programs[x].programcode);
        var tag = '';
        if (e != -1) {
            if (enrollments[e].iscurrent) {
                tag = ' (current)';
            } else if (enrollments[e].completiondate != 0) {
                tag = ' (completed)';
            } else {
                tag = ' (enrolled)';
            }
        }
        opts += '<option value="' + programs[x].programcode + '"';
        if (tag == ' (current)') {
            opts += 'selected';
            pgm = programs[x].programcode;
        }
        opts += '>' + programs[x].description + tag;;
        opts += '</option>';
    }
    pgmsel.innerHTML = opts;
    pgmsel.onchange = function() { local_gcs_get_courses_completed(this); };
    if (pgm) {
        local_gcs_get_courses_completed(pgmsel);
    }
}

function local_gcs_get_courses_completed(pgmsel) {
    var studsel = document.getElementById('studentselect');
    var x = studsel.selectedIndex;
    var studid = parseInt(studsel[x].value);
    var x = pgmsel.selectedIndex;
    var pgmcode = pgmsel[x].value;
    if (pgmcode != '') {
        local_gcs_call_webservice('local_gcs_program_req_completed_get',
            {'id':studid, 'programcode':pgmcode},
            local_gcs_got_courses_completed);
    }
}

function local_gcs_got_courses_completed(r) {
    var studsel = document.getElementById('studentselect');
    var x = studsel.selectedIndex;
    var studid = parseInt(studsel[x].value);
    var students = local_gcs_students;
    var st;
    for ( x=0; x<students.length; x++) {
        if (studid == students[x].id) {
            st = students[x];
            break;
        }
    }
    var rsp = JSON.parse(r);
    var matrix = rsp[0]['data'];
    var eol = "\n";
    var rpt =  '<div class="logo">';
    if (local_gcs_printlogoenabled) {
        rpt += '<img src="' + local_gcs_printlogo + '">';
    }
    rpt += '</div>'+eol;
    rpt += '<h1>Program Requirements Completed</h1>'+eol;
   
   // Student name and address block.
    rpt += '<div class="nameaddr">' + st.preferredfirstname + ' ' + st.legallastname + '<br />' + eol;
    rpt += st.address + '<br />' + eol;
    if (st.address2 != '') {
        rpt += st.address2 + '<br />' + eol;
    }
    rpt += st.city + ', ' + st.stateprovince + '  ' + st.zip + '<br />' + eol;
    rpt += st.country + '</div>' + eol;
   
    // Date block
    rpt += '<div class="date">' + new Date().toLocaleDateString('en-US') + '</div>' + eol;

    // Student programs completed block.
    rpt += '<div class="degrees"><table>'+eol;
    rpt += '<thead><tr><th>Description</th><th>Enrolled</th><th>Completed</th></tr></thead>'+eol;
    rpt +='<tbody>'+eol;
    var enr = local_gcs_enrollments;
    for ( x=0; x<enr.length; x++) {
      if (enr[x].programcode != '') {
        rpt += '<tr><td>' + enr[x].description + '</td><td>';
        rpt += enr[x].enrolldate + '</td><td>' + enr[x].completiondate + '</td></tr>' + eol;
      }
    }
    rpt += '</tbody></table></div>'+eol;

    // Main completed requirements table.
    rpt += '<div class="clear">&nbsp;</div>' + eol;
    rpt += '<h2>' + matrix[0].programname + '</h2>' + eol
    rpt += '<table>' + eol;
    rpt += '<thead><tr><th>Course</th><th>Class Term</th><th>Transfer Class</th><th>Completed</th><th>Grade</th></tr></thead>' + eol;
    rpt += '<tbody>' + eol;
    var cat = '';

    for ( x=0; x<matrix.length; x++) {
       if (cat != matrix[x].categorycode) {
           cat = matrix[x].categorycode;
           rpt += '<tr><td colspan="5" class="category"><h3>' + matrix[x].categoryname + '</h3></td></tr>' + eol;
       }
       rpt += '<tr';
       if ((matrix[x].completiondate == 0) && (matrix[x].coursecode != '')) {
           rpt += ' class="inprogress"';
       }
       if (matrix[x].gradecode=='AUD') {
           rpt += ' class="audit"';
       }
       if (matrix[x].title == '') {
           matrix[x].title = matrix[x].shorttitle;
       }
       if (matrix[x].transfertitle == '') {
          matrix[x].transfertitle = matrix[x].transfershorttitle;
       }
       rpt+= '><td>' + matrix[x].coursecode + ' - ' + matrix[x].title + '</td>' + eol;
       rpt += '<td>' + matrix[x].termname + '</td><td>' + matrix[x].transfertitle + '</td>' + eol;
       rpt += '<td>';
       if (matrix[x].completiondate) {
           rpt += new Date(matrix[x].completiondate*1000).toLocaleDateString('en-US');
       }
       rpt += '</td><td>' + matrix[x].gradecode + '</td></tr>' + eol;
    }
    rpt += '</tbody></table>' + eol;

    var rptdiv = document.getElementsByClassName('reportcontent');
    rptdiv[0].innerHTML = rpt;
    
    var pgmseldiv = document.getElementsByClassName('programselect');
    var printhtml = '<div class="print"><a href="#" onclick="window.print();return false;"><i class="icon fa fa-print fa-fw"></i></a></div>' + eol;
    pgmseldiv[0].outerHTML = printhtml + pgmseldiv[0].outerHTML;
}

setTimeout('local_gcs_get_students()',100);