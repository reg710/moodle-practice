<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_greetings
 * @category    string
 * @copyright   2024 RCon <reg710@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

// This uses the system context.
$context = context_system::instance();
$PAGE->set_context($context);

// This creates the page url.
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));

// This decides which layout to use.
$PAGE->set_pagelayout('standard');

// Sets title for page (what browser tab will show).
$PAGE->set_title(get_string('pluginname', 'local_greetings'));

// Sets heading within page layout.
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

// This uses Moodle's output API to structure the page.
echo $OUTPUT->header();

if (isloggedin()) {
    echo '<h3>Greetings, ' . fullname($USER) . '</h3>';
} else {
    echo '<h3>Greetings, user</h3>';
}


echo $OUTPUT->footer();
