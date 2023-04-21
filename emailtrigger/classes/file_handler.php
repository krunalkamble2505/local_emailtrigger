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
require_once($CFG->libdir.'/csvlib.class.php');

/**
 *
 */
class file_handler {
    /**
     * File storage.
     */
    protected $fs = false;

    /**
     * Initialize file variable.
     */
    public function __construct() {
        $this->fs = get_file_storage();
    }

    /**
     * Handles file saving.
     *
     * @param object $mform form data.
     * @param object $context context.
     */
    public function save_file_data($mform, $context) {
        global $USER;
        $files = $this->fs->get_area_files($context->id, 'local_emailtrigger', 'content', $USER->id);
        foreach ($files as $f) {
            if ($f->get_filename() !== '.') {
                $f->delete();
            }
        }
        $storedfile = $mform->save_stored_file('userfile', $context->id, 'local_emailtrigger', 'content', $USER->id);
    }

    /**
     * Get stored file.
     *
     * @param int $userid user id.
     * @param object $context context.
     */
    public function get_stored_file($userid, $context) {
        $files = $this->fs->get_area_files($context->id, 'local_emailtrigger', 'content', $userid);

        $storedfile = false;
        foreach ($files as $f) {
            if ($f->get_filename() !== '.') {
                $storedfile = $f;
                continue;
            }
        }
        return $storedfile;
    }

    /**
     * Save draft file.
     *
     * @param int $userid user id.
     * @param object $context context.
     */
    public function save_draft_file($userid, $storedfile) {
        $fileinfo = array(
            'component' => 'user',     // Usually = table name.
            'filearea' => 'draft',     // Usually = table name.
            'itemid' => $userid,               // Usually = ID of row in table.
            'contextid' => 5, // ID of context.
            'filepath' => '/',           // Any path beginning and ending in.
            'filename' => $storedfile->get_filename()
        ); // Any filename.
        if (!$draftfile = $this->fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                              $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename'])) {
            $draftfile = $this->fs->create_file_from_storedfile($fileinfo, $storedfile);
        }

        return $draftfile;
    }

    /**
     * Get file data.
     *
     * @param int $userid user id.
     * @param object $context context.
     */
    public function get_file_data($userid, $context) {
        $storedfile = $this->get_stored_file($userid, $context);

        if ($storedfile) {
            $iid = \csv_import_reader::get_new_iid('uploaduser');
            $cir = new \csv_import_reader($iid, 'uploaduser');
            $readcount = $cir->load_csv_content($storedfile->get_content() , 'UTF-8', ',');
            $columns = $cir->get_columns();
            $cir->init();

            $data = array();
            while ($line = $cir->next()) {
                $d = array();
                foreach ($line as $key => $l) {
                    $d[$columns[$key]] = $l;
                }
                $data[] = (object) $d;
            }

            return $data;
        }

        return array();
    }

    /**
     * Get html table.
     *
     * @param int $userid user id.
     * @param object $context context.
     */
    public function get_table($userid, $context) {
        $storedfile = $this->get_stored_file($userid, $context);
        $filecontent = $storedfile->get_content();

        $table = new \html_table();
        $table->head = array(
            'Firstname',
            'Lastname',
            'Email'
        );
        $iid = \csv_import_reader::get_new_iid('uploaduser');
        $cir = new \csv_import_reader($iid, 'uploaduser');
        $readcount = $cir->load_csv_content($filecontent, 'UTF-8', ',');
        $columns = $cir->get_columns();
        $cir->init();
        $recordcount = 0;
        $tabledata = [];
        while ($line = $cir->next()) {
            $tabledata[] = $line;
            $recordcount++;
        }
        $table->data = $tabledata;

        return \html_writer::table($table);
    }
}
