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
 * Course Activities Summary report table.
 *
 * @package     local_emailtrigger
 * @author      krunal Kamble
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once('classes/forms/csvusers.php');
require_once('classes/file_handler.php');
require_once($CFG->libdir.'/csvlib.class.php');

require_login();

global $CFG, $COURSE, $PAGE;

$page = optional_param('page', 0, PARAM_INT);

// Context system.
$context = \context_system::instance();
$component = "local_emailtrigger";

// Page URL.
$pageurl = new moodle_url($CFG->wwwroot . "/local/emailtrigger/csvuserslist.php");

// Set page context.
$PAGE->set_context($context);

// Set page layout.
$PAGE->set_pagelayout('standard');

// Set page URL.
$PAGE->set_url($pageurl);

$mform = new emailtrigger_uploadcsvuser_form();

$PAGE->set_heading(get_string('uploadusers', 'local_emailtrigger'));
$PAGE->set_title(get_string('csvuserslist', 'local_emailtrigger'));

$filehandler = new \local_emailtrigger\file_handler();

$fs = get_file_storage();
if ($formdata = $mform->get_data()) {
    $filehandler->save_file_data($mform, $context);
}

$storedfile = $filehandler->get_stored_file($USER->id, $context);
$htmltable = '';

if ($storedfile) {
    $draftfile = $filehandler->save_draft_file($USER->id, $storedfile);
    $data = new stdClass();
    $data->userfile = $draftfile->get_itemid();
    $mform->set_data($data);
    $htmltable = $filehandler->get_table($USER->id, $context);
}

// Print output for course completion page.
echo $OUTPUT->header();
$mform->display();
echo $htmltable;
echo $OUTPUT->footer();
