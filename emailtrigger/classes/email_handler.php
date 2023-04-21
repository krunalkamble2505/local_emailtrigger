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
 * @package    local_emailtrigger
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_emailtrigger;

defined('MOODLE_INTERNAL') || die();
require_once('file_handler.php');

/**
 * Email handler file
 */
class email_handler {
    /**
     * Email generator
     */
    public function email_generator() {
        global $USER;

        $context = \context_system::instance();
        $filehandler = new file_handler();
        $filedata = $filehandler->get_file_data($USER->id, $context);
        $noerror = 1;

        foreach ($filedata as $data) {
            $mail = $this->generate_random_email();
            $status = $this->send_email($data, $mail['subject'], $mail['content']);

            if ($status != false) {
                $data->mailid = $mail['id'];
                $data->mailsenttime = time();
                $this->log_email($data);
            } else {
                $noerror = 0;
                break;
            }
        }
        return $noerror;
    }

    /**
     * Send email
     * @param object $data users data.
     * @param string $subject email subject.
     * @param string $content email content.
     * @return int $status
     */
    public function send_email($data, $subject, $content) {
        global $USER, $PAGE;
        $context = \context_system::instance();
        $PAGE->set_context($context);
        $fromuser = \core_user::get_noreply_user();
        $user = $fromuser;
        $user->firstname = $data->firstname;
        $user->lastname = $data->lastname;
        $user->email = $data->email;
        $status = email_to_user($user, $USER, $subject, $content);
        return $status;
    }

    /**
     * update log entry to table email
     * @param object $data users data.
     */
    protected function log_email($data) {
        global $DB;
        $DB->insert_record('emailtrigger_log', $data);
    }

    /**
     * Email array
     */
    protected function generate_random_email() {
        $emails = array(
            array(
                'id' => 1,
                'subject' => 'Test 1',
                'content' => 'This is test 1 message'
            ),
            array(
                'id' => 2,
                'subject' => 'Test 2',
                'content' => 'This is test 2 message'
            ),
            array(
                'id' => 3,
                'subject' => 'Test 3',
                'content' => 'This is test 3 message'
            )
        );
        return $emails[rand(0, 2)];
    }
}
