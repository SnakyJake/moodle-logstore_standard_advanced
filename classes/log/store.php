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
 * Standard log reader/writer.
 *
 * @package    logstore_standard_advanced
 * @copyright  2013 Petr Skoda {@link http://skodak.org}, 2022 Jakob Heinemann
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_standard_advanced\log;

require_once($CFG->dirroot."/admin/tool/log/store/standard_advanced/classes/helper/buffered_writer.php");

defined('MOODLE_INTERNAL') || die();

class store extends \logstore_standard\log\store {
    use \logstore_standard_advanced\helper\buffered_writer;

    protected $haship;

    public function __construct(\tool_log\log\manager $manager) {
        parent::__construct($manager);
        //Don't hash ip on default
        $this->haship = $this->get_config('haship', 0);
    }
}
