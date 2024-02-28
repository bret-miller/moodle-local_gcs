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
 * Generic function to call the GCS webservice
 *
 * @package    local_gcs
 * @copyright  2023-2024 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_gcs_webservice {
    // Public properties.
    /** @var array functions list of functions being called */
    functions;
    /** @var array requests list of requests to process */
    requests;
    
    /**
     * Initializes an the ajax webservice class
     *
     * @param none
     */
    constructor() {
        this.functions=[];
        this.requests=[];
        return;
    }
    
    /**
     * Queues one webservice function with its parameters
     *
     * @param string method the web service function to call
     * @param parameters object parameters for the function
     * @return nothing
     */
    queue(method,parameters) {
        this.functions.push(method);
        var req = { index: this.requests.length, methodname: method, args: parameters};
        this.requests.push(req);
    }
    
    /**
     * Processes the queued webservice functions and returns the data requested
     *
     * @param none
     * @return object response data returned by the webservice functions
     */
    async process() {
        let url = M.cfg.wwwroot + '/lib/ajax/service.php';
        url += '?info=' + encodeURIComponent(this.functions.join());
        url += '&sesskey=' + M.cfg.sesskey;
        let opt = {
            method: 'POST',
            headers: { "Content-Type": "application/json" },
            redirect: "follow",
            body: JSON.stringify(this.requests),
        };
        let response = await fetch(url,opt);
        let sts = await response.status;
        if (sts == 200) {
            var resptext = await response.text();
        } else {
            var resptext = '{status:'+resp.status.toString()+'}';
            alert(resptext);
        }
        let respjson = JSON.parse(resptext);
        if (respjson.error) {
            alert('Web service call returned error: '+respjson.error);
        }
        if (respjson[0].error) {
            alert('Web service call returned error: '+respjson[0].exception.message);
        }
        // We processed the queued requests, so clear the queue.
        this.functions=[];
        this.requests=[];
        return respjson;
    }

    /**
     * Queues and process one webservice function
     *
     * @param string method the web service function to call
     * @param parameters object parameters for the function
     * @param callback function to call with returned data
     * @return nothing
     */
    async call(method,parameters,callback) {
        var ajax = new local_gcs_webservice();
        ajax.queue(method,parameters);
        var r = await ajax.process();
        var data = r[0].data;
        callback(data);
        return;
    }
}