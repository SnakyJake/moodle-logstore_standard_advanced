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
 * Standard log store.
 *
 * @package    logstore_standard_advanced
 * @copyright  2022 Jakob Heinemann
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function xmldb_logstore_standard_advanced_uninstall(){
    $all = \tool_log\log\manager::get_store_plugins();
    if(isset($all['logstore_standard'])){
        $cfg = get_config('logstore_standard_advanced');
        if(isset($cfg->enable_standard_on_uninstall) && $cfg->enable_standard_on_uninstall){
            $enabled = get_config('tool_log', 'enabled_stores');
            if ($enabled) {
                $enabled = array_flip(explode(',', $enabled));
            } else {
                $enabled = array();
            }
            $enabled = array_keys($enabled);
            $enabled[] = 'logstore_standard';
            set_config('enabled_stores', implode(',', $enabled), 'tool_log');
            add_to_config_log('tool_logstore_visibility', '0', '1', 'logstore_standard');
        }
    }
}