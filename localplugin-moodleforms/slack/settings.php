<?php

/*  DOCUMENTATION
    .............

    $hassiteconfig which can be used as a quick way to check for the moodle/site:config permission. This variable is set by
	the top-level admin tree population scripts. 
	
	$ADMIN->add('root', new admin_category();
	Add admin settings for the plugin with a root admin category as Slack variable.
	
	$ADMIN->add('slack', new admin_externalpage();
	Add new external pages for your Slack plugin.
*/

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    $ADMIN->add('root', new admin_category('slack', get_string('pluginname', 'local_slack')));
	
	$ADMIN->add('slack', new admin_externalpage('userdata', get_string('userdata', 'local_slack'),
                 new moodle_url('/local/slack/userdata.php')));

    $ADMIN->add('slack', new admin_externalpage('usermetadata', get_string('usermetadata', 'local_slack'),
                 new moodle_url('/local/slack/metadata.php')));			 
}