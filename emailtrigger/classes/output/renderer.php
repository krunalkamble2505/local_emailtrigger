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
 * Plugin administration pages are defined here.
 *
 * @package     local_emailtrigger
 * @category    admin
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_emailtrigger\output;
use plugin_renderer_base;

/**
 * Edwiser report renderer
 */
class renderer extends plugin_renderer_base {

    /**
     * Rendersemail log table.
     * @param  usersemaillog_renderable $report Object of Edwiser Reports renderable class
     * @return string  Html Structure of the view page
     */
    public function render_usersemaillog(usersemaillog_renderable $renderable) {
        $templatecontext = $renderable->export_for_template($this);
        return $this->render_from_template('local_emailtrigger/usersemaillog', $templatecontext);
    }

    /**
     * send random email.
     * @param  sendrandomemail_renderable $report Object of Edwiser Reports renderable class
     * @return string  Html Structure of the view page
     */
    public function render_sendrandomemail(sendrandomemail_renderable $renderable) {
        $templatecontext = $renderable->export_for_template($this);
        return $this->render_from_template('local_emailtrigger/sendrandomemails', $templatecontext);
    }
}
