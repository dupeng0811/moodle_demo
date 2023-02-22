<?php

/*  DOCUMENTATION
    .............
   
    Follow the documentation from userdata.php

*/

require('../../config.php');
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/tablelib.php');
require_once($CFG->dirroot.'/mod/page/locallib.php');

$instance = optional_param('id', 0, PARAM_INT);
$path = optional_param('path', '', PARAM_PATH);
$pageparams = array();

if ($path) {
    $pageparams['path'] = $path;
}

$vid = required_param('id', PARAM_INT);

global $CFG, $USER, $DB, $OUTPUT, $PAGE, $instance, $vid;

$PAGE->set_url('/local/slack/userdelete.php');

require_login();

$PAGE->set_pagelayout('admin');
$context = context_system::instance();
$PAGE->set_context($context);

admin_externalpage_setup('usermetadata', '', $pageparams);

$header = $SITE->fullname;
$PAGE->set_title(get_string('pluginname', 'local_slack'));
$PAGE->set_heading($header);

$DB->delete_records('userdata', array('id' => $vid));
redirect(new moodle_url('/local/slack/metadata.php'));

echo $OUTPUT->header();

$metadata_title = get_string('metadata', 'local_slack');

echo $OUTPUT->heading_with_help($metadata_title, 'metadatah', 'local_slack');

echo $OUTPUT->footer();
