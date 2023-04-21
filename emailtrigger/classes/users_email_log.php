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

namespace local_emailtrigger;

defined('MOODLE_INTERNAL') || die();

require_once('classes/file_handler.php');

/**
 * Users email log handler.
 */
class users_email_log {
    /**
     * Get users log from table.
     */
    public function get_users_log() {
        global $DB;
        $log = $DB->get_records('emailtrigger_log');

        $table = new \html_table();
        $table->head = array(
            'Email Id',
            'Email Sent Date'
        );

        foreach ($log as $data) {
            $date = date("d M Y h:i A", $data->mailsenttime);
            $table->data[] = array($data->email, $date);
        }
        $htmltable = \html_writer::table($table);
        return $htmltable;
    }
}
