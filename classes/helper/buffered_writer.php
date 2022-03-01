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
 * Helper trait buffered_writer
 *
 * @package    tool_log
 * @copyright  2014 onwards Ankit Agarwal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_standard_advanced\helper;
defined('MOODLE_INTERNAL') || die();

/**
 * Helper trait buffered_writer. Adds buffer support for the store.
 *
 * @package    logstore_standard_advanced
 * @copyright  2014 onwards Ankit Agarwal, 2022 Jakob Heinemann
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait buffered_writer {
    /**
     * Write event in the store with buffering. Method insert_event_entries() must be
     * defined.
     *
     * @param \core\event\base $event
     *
     * @return void
     */
    public function write(\core\event\base $event) {
        global $PAGE;

        if ($this->is_event_ignored($event)) {
            return;
        }

        // We need to capture current info at this moment,
        // at the same time this lowers memory use because
        // snapshots and custom objects may be garbage collected.
        $entry = $event->get_data();
        if ($this->jsonformat) {
            $entry['other'] = json_encode($entry['other']);
        } else {
            $entry['other'] = serialize($entry['other']);
        }
        $entry['origin'] = $PAGE->requestorigin;
        $entry['ip'] = $PAGE->requestip;
        if($this->haship){
            $entry['ip'] = sha1($entry['ip']);
        }
        $entry['realuserid'] = \core\session\manager::is_loggedinas() ? $GLOBALS['USER']->realuser : null;

        $this->buffer[] = $entry;
        $this->count++;

        if (!isset($this->buffersize)) {
            $this->buffersize = $this->get_config('buffersize', 50);
        }

        if ($this->count >= $this->buffersize) {
            $this->flush();
        }
    }
}
