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
 * Bulk user upload forms
 *
 * @package    tool
 * @subpackage uploaduser
 * @copyright  2007 Dan Poltawski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot . '/user/editlib.php');

/**
 * Upload a file CVS file with user information.
 *
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class emailtrigger_uploadcsvuser_form extends moodleform {
    public function definition () {
        $mform = $this->_form;
        $url = new moodle_url('example.csv');
        $link = html_writer::link($url, 'example.csv');
        $mform->addElement('static', 'examplecsv', get_string('examplecsv', 'tool_uploaduser'), $link);
        $mform->addHelpButton('examplecsv', 'examplecsv', 'tool_uploaduser');

        $mform->addElement(
            'filepicker',
            'userfile',
            get_string('file'),
            null,
            [
                'accepted_types' => ['csv'],
            ]
        );

        $this->add_action_buttons(false, get_string('uploadusers', 'tool_uploaduser'));
    }
}
