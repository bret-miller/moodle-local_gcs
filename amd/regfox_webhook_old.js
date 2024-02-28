// Global variables.
var local_gcs_matchregid;
var local_gcs_matched_students;
var local_gcs_processing_registrant;

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

function local_gcs_get_unprocessed_webhooks() {
    local_gcs_call_webservice('local_gcs_regfox_get_unprocessed_webhooks',[],local_gcs_regfox_got_unprocessed_webhooks);
    local_gcs_call_webservice('local_gcs_regfox_get_unprocessed_registrants',[],local_gcs_regfox_got_unprocessed_registrants);
}

function local_gcs_regfox_got_unprocessed_webhooks(resp) {
    r = JSON.parse(resp);
    if (r.error) {
        err = '<p>' + r.error + '</p><pre>' + r.stacktrace + '</pre>';
        document.getElementsByClassName('response')[0].innerHTML = err;
    } else {
        var hookcountdiv = document.getElementsByClassName('gcswebhooks')[0].childNodes[0];
        hookcountdiv.innerText = r[0].data.length + ' unprocessed webhooks';
        document.getElementById('gcsprocessbutton').innerText='Process Webhooks';
    }
    return;
}

function local_gcs_regfox_got_unprocessed_registrants(resp) {
    r = JSON.parse(resp);
    if (r.error) {
        err = '<p>' + r.error + '</p><pre>' + r.stacktrace + '</pre>';
        document.getElementsByClassName('response')[0].innerHTML = err;
    } else {
        var students = r[0]['data'];
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
    }
    return;Nm
}

function local_gcs_trigger_processing() {
    document.getElementById('gcsprocessbutton').innerText='Processing...';
    local_gcs_call_webservice('local_gcs_regfox_process_webhooks',[],local_gcs_processing_triggered);
}

function local_gcs_processing_triggered(resp) {
    r = JSON.parse(resp);
    if (r.error) {
        err = '<p>' + r.error + '</p><pre>' + r.stacktrace + '</pre>';
        document.getElementsByClassName('response')[0].innerHTML = err;
    } else {
        setTimeout( function() {
            local_gcs_get_unprocessed_webhooks();
        }, 60*1000);
    }
    return;
}

function local_gcs_process_registrant(el) {
    var row = el.parentNode.parentNode;
    local_gcs_matchregid = row.getAttribute('id');
    var regid = parseInt(local_gcs_matchregid.substring(3));
    var email = row.childNodes[3].innerText;
    local_gcs_call_webservice('local_gcs_regfox_process_registrant',{'id':regid},local_gcs_process_registrant_callback);
}

function local_gcs_process_registrant_callback(resp) {
    r = JSON.parse(resp);
    if (r[0].error) {
        err = '<p>' + r[0].exception.message + '</p><pre>' + r[0].exception.backtrace + '</pre>';
        document.getElementsByClassName('response')[0].innerHTML = err;
    } else {
        var result = r[0]['data'][0];
        var log = result.log;
        document.getElementsByClassName('response')[0].innerHTML = '<pre>'+log+"</pre>\n";
        if (result.processed) {
            local_gcs_call_webservice('local_gcs_regfox_get_unprocessed_registrants',[],local_gcs_regfox_got_unprocessed_registrants);
        }
        rfreg = result.registrant;
        local_gcs_processing_registrant = rfreg;
        if (!rfreg.studentid) {
            var fn = rfreg.firstname.substring(0,1);
            var ln = rfreg.lastname.substring(0,1);
            local_gcs_call_webservice('local_gcs_students_get_by_name',
            {'firstname':fn, 'lastname':ln},
                local_gcs_got_potential_matches);
        }
    }
}

function local_gcs_got_potential_matches(resp) {
    r = JSON.parse(resp);
    if (r[0].error) {
        err = '<p>' + r[0].exception.message + '</p><pre>' + r[0].exception.backtrace + '</pre>';
        document.getElementsByClassName('response')[0].innerHTML = err;
    } else {
        var row = document.getElementById(local_gcs_matchregid);
        var name = row.childNodes[1].innerText
        var matches = r[0]['data'];
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
        document.getElementsByTagName('body')[0].innerHTML += html;
        document.getElementById('local_gcs_match_dialog').showModal();
    }
}

function local_gcs_match_selected(el) {
    var studentid = el.options[el.selectedIndex].value;
    var dlg = document.getElementById('local_gcs_match_dialog');
    dlg.close(); // Close the modal dialog.
    dlg.outerHTML = ''; // Destroy the modal dialog.
    if (studentid == 'act') {
        onlyactive = true;
    } else {
        onlyactive = false;
    } 
    if ((studentid == 'act') || (studentid == 'all')) {
        local_gcs_call_webservice('local_gcs_students_get_all',
            {'onlyactive' : onlyactive},local_gcs_got_potential_matches);
    } else if (studentid == '') {
        // Nothing selected, so do nothing.
    } else {
        local_gcs_processing_registrant.studentid = parseInt(studentid);
        local_gcs_call_webservice('local_gcs_regfox_registrant_update',
            {'rec' : local_gcs_processing_registrant},local_gcs_matched_registrant);
        var student = local_gcs_matched_students.find( ({id}) => id === parseInt(studentid));
        if (!student.regfoxemails) {
            student.regfoxemails = local_gcs_processing_registrant.email;
        } else {
            student.regfoxemails += ',' + local_gcs_processing_registrant.email;
        }
        local_gcs_call_webservice('local_gcs_students_update',
            {'rec' : student},local_gcs_updated_student);
    }
}

function local_gcs_matched_registrant(resp) {
    r = JSON.parse(resp);
    if (r[0].error) {
        err = '<p>' + r[0].exception.message + '</p><pre>' + r[0].exception.backtrace + '</pre>';
        document.getElementsByClassName('response')[0].innerHTML = err;
    } else {
        var rfreg = r[0]['data'];
        local_gcs_call_webservice('local_gcs_regfox_process_registrant',{'id':rfreg.id},local_gcs_process_registrant_callback);
    }
}

function local_gcs_updated_student(resp) {
    r = JSON.parse(resp);
    if (r[0].error) {
        err = '<p>' + r[0].exception.message + '</p><pre>' + r[0].exception.backtrace + '</pre>';
        document.getElementsByClassName('response')[0].innerHTML = err;
    }
}

function local_gcs_process_registrants() {
    local_gcs_call_webservice('local_gcs_regfox_process_registrants',[],local_gcs_process_registrants_callback);
}

function local_gcs_process_registrants_callback(resp) {
    r = JSON.parse(resp);
    if (r[0].error) {
        err = '<p>' + r[0].exception.message + '</p><pre>' + r[0].exception.backtrace + '</pre>';
        document.getElementsByClassName('response')[0].innerHTML = err;
    } else {
        var result = r[0]['data'][0];
        var log = result.log;
        document.getElementsByClassName('response')[0].innerHTML = '<pre>'+log+"</pre>\n";
        local_gcs_call_webservice('local_gcs_regfox_get_unprocessed_registrants',[],local_gcs_regfox_got_unprocessed_registrants);
    }
}

setTimeout('local_gcs_get_unprocessed_webhooks()',100);