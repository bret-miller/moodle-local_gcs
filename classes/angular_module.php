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
 * Defines an angular module for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;
use renderable;
use cache;

/**
 * Defines a more complex Angular module
 */
class angular_module implements renderable {
    /** @var string name of the folder where the module exists, also the name of the module */
    public $name;
    /** @var string path of the folder where the module exists */
    public $folder;
    /** @var array stylesheets to load */
    public $stylesheets;
    /** @var string inline css to include */
    public $inlinecss;
    /** @var string contents to render the app in the body of the page */
    public $appcode;
    /** @var array scripts to loa
    public $scripts;
    /** @var bool flag to indicate the body tag and not one of its children is being processed */
    public $inbody;
    /** @var app index.html modification time */
    public $modtime;
    /** @var app definition is from cache */
    public $cachetime;
    /** @var bool flag to indicate from cache */
    public $fromcache;
    /** @var cache array */
    public $cache;

    /**
     * Initializes an Angular module definition
     *
     * @param string $modulename name of the folder where the module exists
     */
    public function __construct(string $modulename) {
        global $CFG;
        $mydir = $CFG->dirroot . "/local/gcs";
        $folder = "$mydir/amd/angular/$modulename";
        $this->name = '';
        $this->folder = '';
        $this->stylesheets = ['https://fonts.googleapis.com/icon?family=Material+Icons'];
        $this->inlinecss = '';
        $this->scripts = [];
        $this->appcode = '';
        $this->inbody = false;
        $this->modtime = 0;
        $this->fromcache = false;
        $this->cache = [];
        $appindex = "$folder/index.html";
        if (file_exists($appindex)) {
            $this->modtime = filemtime($appindex);
            // See if cache is more recent than index.
            $cache = cache::make('local_gcs', 'angularmodules');
            $c = $cache->get($modulename);
            $this->cache = $c;
            if (($c) && (array_key_exists('modtime', $c))) {
                $cachetime = $c['modtime'];
            } else {
                $cachetime = 0;
            }
            $this->cachetime = $cachetime;
            if (($c) && ($cachetime == $this->modtime)) {

                // Use the cache.
                $this->name = $modulename;
                $this->stylesheets = $c['stylesheets'];
                $this->appcode = $c['appcode'];
                $this->scripts = $c['scripts'];
                $this->fromcache = true;
            } else {

                // Load the app's index.html file. We will load these in the page head.
                error_reporting(0);
                $dom = new \DOMDocument();
                $r = $dom->LoadHTMLFile($appindex);
                error_reporting(E_ALL);
                if ($r) {
                    $this->name = $modulename;
                    $this->folder = "/local/gcs/amd/angular/$modulename";
                    $this->inbody = false;
                    $this->process_node($dom);
                    $this->appcode = str_replace("\r", "", $this->appcode)."\n<script>\nvar gcsBreak = 1;\n</script>\n";
                    if ($this->inlinecss) {
                        $cssfile = $folder."/".$this->name.".css";
                        if (file_exists($cssfile)) {
                            $cssmtime = filemtime($cssfile);
                        } else {
                            $cssmtime = 0;
                        }
                        if ($this->modtime > $cssmtime) {
                            $f = fopen($cssfile, "w");
                            fwrite($f, $this->inlinecss);
                            fclose($f);
                        }
                        $cssurl = $CFG->wwwroot . "/" . $this->folder . "/$modulename.css";
                        $this->stylesheets[] = $cssurl . '?ver=' . $this->modtime;;
                    }

                    // Create a new cache entry.
                    $c = [
                        'stylesheets' => $this->stylesheets,
                        'appcode' => $this->appcode,
                        'scripts' => $this->scripts,
                        'modtime' => $this->modtime,
                    ];
                    $cache->set($modulename, $c);
                    $this->fromcache = false;
                }
            }
        }
    }

    /**
     * Process a node in the index.html DOMDocument
     *
     * @param DOMNode $node one node in index.html
     */
    private function process_node($node) {
        global $CFG;
        $tags = $node->childNodes;
        for ($i = 0; $i < $tags->count(); $i++) {
            $tag = $tags[$i];
            if ($tag->nodeName == 'body') {
                // Special processing for <body>.
                $this->inbody = true;
                $this->process_node($tag);
                $this->inbody = false;

            } else if ($tag->nodeName == 'link') {
                // Get all link tags and save stylesheets to load on the page.
                $atts = $tag->attributes;
                $rel = $atts->getNamedItem('rel')->nodeValue;
                if ($rel == 'stylesheet') {
                    if ($stylesheet = $atts->getNamedItem('href')) {
                        $cssfile = $stylesheet->nodeValue;
                        if ( (substr($cssfile, 0, 5) == 'http:')
                            || (substr($cssfile, 0, 6) == 'https:')
                            || (substr($cssfile, 0, 2) == '//') ) {
                            $cssurl = $cssfile;
                        } else {
                            $cssurl = $CFG->wwwroot . '/' . $this->folder . '/' . $cssfile . '?ver=' . $this->modtime;
                        }
                        $this->stylesheets[] = $cssurl;
                    }
                }

            } else if ($tag->nodeName == 'style') {
                // Get all style tags and save inline styles to load on the page.
                $thiscss = $tag->nodeValue;
                $this->inlinecss .= $thiscss;

            } else if ($tag->nodeName == 'script') {
                // Get all script tags and save javascript to load on the page.
                $atts = $tag->attributes;
                $jsfile  = $atts->getNamedItem('src')->nodeValue;
                if ( (substr($jsfile, 0, 5) == 'http:')
                    || (substr($jsfile, 0, 6) == 'https:')
                    || (substr($jsfile, 0, 2) == '//') ) {
                    $jsurl = $jsfile;
                } else {
                    $jsurl = $CFG->wwwroot . '/' . $this->folder . '/' . $jsfile . '?ver=' . $this->modtime;
                }
                $this->scripts[] = $jsurl;

            } else if ($this->inbody) {
                // Other tags in body just get saved for later.
                $this->appcode .= $tag->ownerDocument->saveXML( $tag );

            } else if ($tag->hasChildNodes()) {
                if ($tag->nodeName != '#text') {
                    // Process other tags with children.
                    $this->process_node($tag);
                }
            }
        }
    }

    /**
     * Add the Angular module stylesheet to the page
     *
     * @param moodle_page $page the current $PAGE constant
     */
    public function load_styles($page) {
        global $CFG;
        foreach ($this->stylesheets as $stylepath) {
            if ( (substr($stylepath, 0, 5) == 'http:')
                || (substr($stylepath, 0, 6) == 'https:')
                || (substr($stylepath, 0, 2) == '//') ) {
                $styleurl = $stylepath;
            } else {
                $styleurl = $CFG->wwwroot . '/' . $this->folder . '/' . $stylepath . '?ver=' . $this->modtime;
            }
            $stylesheet = new \moodle_url($styleurl);
            $page->requires->css($stylesheet);
        }
    }
}
