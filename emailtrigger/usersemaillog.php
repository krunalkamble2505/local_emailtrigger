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
require_once('classes/output/renderable.php');

require_login();

global $CFG, $COURSE, $PAGE;

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
$PAGE->set_heading(get_string('usersemaillog', 'local_emailtrigger'));
$PAGE->set_title(get_string('csvuserslist', 'local_emailtrigger'));

// Get active users renderable.
$renderable = new \local_emailtrigger\output\usersemaillog_renderable();
$output = $PAGE->get_renderer($component)->render($renderable);

// Print output for course completion page.
echo $OUTPUT->header();

echo $output;

echo $OUTPUT->footer();
