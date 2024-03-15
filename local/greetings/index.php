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
require_once($CFG->dirroot. '/local/greetings/lib.php');

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

// Create instance of greeting form.
$messageform = new \local_greetings\form\message_form();


// Save messages to the db.
if ($data = $messageform->get_data()) {
    $message = required_param('message', PARAM_TEXT);

    if (!empty($message)) {
        $record = new stdClass;
        $record->message = $message;
        $record->timecreated = time();
        $record->userid = $USER->id;

        $DB->insert_record('local_greetings_messages', $record);
    }
}

// This uses Moodle's output API to structure the page.
echo $OUTPUT->header();

if (isloggedin()) {
    echo local_greetings_get_greeting($USER);
} else {
    echo get_string('greetinguser', 'local_greetings');
}

echo userdate(time(), get_string('strftimedaydate', 'core_langconfig'));

$messageform->display();

$userfields = \core_user\fields::for_name()->with_identity($context);
$userfieldssql = $userfields->get_sql('u');

$sql = "SELECT m.id, m.message, m.timecreated, m.userid
{$userfieldssql->selects}
        FROM {local_greetings_messages} m
        LEFT JOIN {user} u ON u.id = m.userid
        ORDER BY timecreated DESC";
$messages = $DB->get_records_sql($sql);

// Display messages from db.
echo $OUTPUT->box_start('card-columns');

foreach ($messages as $m) {
    echo html_writer::start_tag('div', array('class' => 'card'));
    echo html_writer::start_tag('div', array('class' => 'card-body'));
    echo html_writer::tag('p', $m->message, array('class' => 'card-text'));
    echo html_writer::tag('p', get_string('postedby', 'local_greetings', $m->firstname), array('class' => 'card-text'));
    echo html_writer::start_tag('p', array('class' => 'card-text'));
    echo html_writer::tag('small', userdate($m->timecreated), array('class' => 'text-muted'));
    echo html_writer::end_tag('p');
    echo html_writer::end_tag('div');
    echo html_writer::end_tag('div');
}

echo $OUTPUT->box_end();

echo $OUTPUT->footer();
