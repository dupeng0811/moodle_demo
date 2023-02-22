<?php

/*  DOCUMENTATION
    .............
   
    Follow the documentation from userdata.php

*/

require('../../config.php');
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/mod/page/locallib.php');

$instance = optional_param('id', 0, PARAM_INT);
$path = optional_param('path', '', PARAM_PATH);
$pageparams = array();

if ($path) {
    $pageparams['path'] = $path;
}

$imsid = required_param('imsid', PARAM_INT);

global $CFG, $USER, $DB, $OUTPUT, $PAGE, $instance, $imsid;

$PAGE->set_url('/local/slack/useredit.php', array('imsid' => $imsid));

require_login();

$PAGE->set_pagelayout('admin');
$context = context_system::instance();
$PAGE->set_context($context);

admin_externalpage_setup('usermetadata', '', $pageparams);

$header = $SITE->fullname;
$PAGE->set_title(get_string('pluginname', 'local_slack'));
$PAGE->set_heading($header);

/* Add your custom code here..*/

class useredit_form extends moodleform {

    /**
     *
     * The definition() function defines the form elements.
     *
     */
    public function definition() {

        global $DB, $CFG, $PAGE, $USER, $context, $instance, $imsid;
			
		$mform = $this->_form;		
			
		$fullname = [];
	    $email = [];
	    $age = [];
		$saveskills = [];		
		$profession = [];
		$introexp = [];
		$dob = [];
		$userstartdate = [];
		$userenddate = [];
		$userpic = [];
		$certificate = [];
	    $multimedia = [];
	    $description = [];
		$descriptionformat = [];
		
		$userdata = $DB->get_recordset_sql('SELECT * FROM {userdata} WHERE id = '.$imsid.'');
		
		foreach($userdata as $ud) {
            $fullname = $ud->fullname;
	        $password = $ud->password;
	        $email = $ud->email;
	        $age = $ud->age;
			$saveskills = $ud->saveskills;
	        $profession = $ud->profession;
			$introexp = $ud->introexp;
	        $dob = $ud->dob;
	        $userstartdate = $ud->userstartdate;
	        $userenddate = $ud->userenddate;
	        $userpic = $ud->userpic;
	        $certificate = $ud->certificate;
	        $multimedia = $ud->multimedia;
	        $description = $ud->description;		
			$descriptionformat = $ud->descriptionformat;
        }
         
		/* HEADER
		    mformheader:Userdata Form
		 */		
        $mform->addElement('header', 'mformheader', get_string('mformheader', 'local_slack'));
		
		/* TEXTBOX (HIDDEN)
		   userid:Userid
		   Rule types: No rules (optional).
		 */
        $mform->addElement('hidden', 'imsid');
        $mform->setType('imsid', PARAM_INT);
        $mform->setDefault('imsid', $imsid);

        /* TEXTBOX (TEXT)
  		   fullname:Fullname
           Rule types: Must not be empty, characters should not exceed 50.
         */
        $mform->addElement('text', 'fullname', get_string('fullname', 'local_slack'), array('size' => 40,
                           'maxlength = "50"', 'pattern' => '[A-Za-z0-9-|:~`!@#$%^+&,)\-=}({:;>.|<@?/<!&$_ ]+'));
        $mform->setType('fullname', PARAM_TEXT);
        $mform->addRule('fullname', get_string('required', 'local_slack'), 'required', null, 'client');
        $mform->addRule('fullname', get_string('maximumchars', 'local_slack', 50), 'maxlength', 50, 'client');
        $mform->addHelpButton('fullname', 'fullname', 'local_slack');
		$mform->setDefault('fullname', $fullname);
		
		/* TEXTBOX (PASSWORD)
		   password:Password
           Rule types: Must not be empty, characters should not exceed 50.
         */
		$attributes = array('size' => '12', 'maxlength' => '32', 'autocomplete' => 'new-password');
        $mform->addElement('passwordunmask', 'password', get_string('password', 'local_slack'), $attributes);
        $mform->addHelpButton('password', 'password', 'local_slack');
        $mform->addRule('password', get_string('required'), 'required', null, 'client');
        $mform->addRule('password', get_string('maximumchars', 'local_slack', 32), 'maxlength', 32, 'server');
		$mform->setType('password', core_user::get_property_type('password'));
 		$mform->addHelpButton('password', 'password', 'local_slack');
		    
        /* TEXTBOX (EMAIL FORMAT)
		   email:Email
           Rule types: Must not be empty, characters should not exceed 35.
         */
        $mform->addElement('text', 'email', get_string('email', 'local_slack'), array('size' => 40, 'maxlength = "35"'));
        $mform->setType('email', PARAM_RAW);
        $mform->addRule('email', get_string('required', 'local_slack'), 'required', null, 'client');
        $mform->addRule('email', get_string('maximumchars', 'local_slack', 35), 'maxlength', 35, 'client');
		$mform->addRule('email', get_string('emailformat', 'local_slack'), 'email', null, 'client');
        $mform->addHelpButton('email', 'email', 'local_slack');
		$mform->setDefault('email', $email);

        /* TEXTBOX (NUMBER FORMAT)
		   age:Age
           Rule types: No rules (optional).
         */
		$mform->addElement('text', 'age', get_string('age', 'local_slack'), array("pattern = '^[0-9]{2}$'",
                           'maxlength' => '2', 'size' => '1'));
        $mform->setType('age', PARAM_INT);		
        $mform->addRule('age', get_string('maximumchars', 'local_slack', 2), 'maxlength', 2, 'client');
        $mform->addHelpButton('age', 'age', 'local_slack');
		$mform->setDefault('age', $age);
		
		/* CHECKBOX
		   saveskills:Save your skills?
           Rule types: selecting this option is mandatory.	   
         */
		$mform->addElement('checkbox', 'saveskills', get_string('saveskills', 'local_slack'));
		// $mform->addRule('saveskills', null, 'required', null, 'client');
		$mform->setType('saveskills', PARAM_INT);
        $mform->addHelpButton('saveskills', 'saveskills', 'local_slack');
		$mform->setDefault('saveskills', $saveskills);
        
		/* SELECT
		   profession:Profession.
           Rule types: Must not be empty.
         */		
        $options = array(0 => get_string('prof_select', 'local_slack'),
                         1 => get_string('prof_software', 'local_slack'),
                         2 => get_string('prof_education', 'local_slack'),
                         3 => get_string('prof_business', 'local_slack'),
                         4 => get_string('prof_student', 'local_slack'),
                         5 => get_string('prof_agric', 'local_slack'));
        $mform->addElement('select', 'profession', get_string('profession', 'local_slack'), $options);
        $mform->setType('profession', PARAM_INT);
        $mform->addRule('profession', get_string('required', 'local_slack'), 'required', null, 'client');
        $mform->addHelpButton('profession', 'profession', 'local_slack');
		$mform->setDefault('profession', $profession);			

		/* STATIC TEXT
		   proftip:Profession tip
           Rule types: No rules (optional).
         */
		$mform->addElement('static', 'proftip', '', get_string('proftip', 'local_slack'));		
		
		/* TEXTAREA
		   introexp:Share your experience
		   Rule types: Must not be empty.
		 */
		$mform->addElement('textarea', 'introexp', get_string("introexp", "local_slack"), 'wrap="virtual" rows="5" cols="5"', array('maxlength' => '700'));
		// $mform->addElement('textarea', 'introexp', get_string("introexp", "local_slack"), array('cols'=>'60', 'rows'=>'8'));
		$mform->setType('introexp', PARAM_RAW);
		$mform->addRule('introexp', get_string('required', 'local_slack'), 'required', null, 'client');
		$mform->addHelpButton('introexp', 'introexp', 'local_slack');
		$mform->setDefault('introexp', $introexp);
		
		/* DATE TIME SELECTOR
		   dob:Date-of-birth.
           Rule types: Must not be empty.
         */		
		$mform->addElement('date_time_selector', 'dob', get_string('dob', 'local_slack'), array('required' => true));
		// $mform->addRule('dob', get_string('required', 'local_slack'), 'required', null, 'client');
        $mform->setDefault('dob', 0);
        $mform->addHelpButton('dob', 'dob', 'local_slack');
		$mform->setDefault('dob', $dob);
		
		/* DATE TIME SELECTOR (ENABLE OPTION)
		   userstartdate:User start date.
           Rule types: No rules (optional).
         */
		$mform->addElement('date_time_selector', 'userstartdate', get_string('userstartdate', 'local_slack'), array('optional' => true));
        $mform->setDefault('userstartdate', 0);
        $mform->addHelpButton('userstartdate', 'userstartdate', 'local_slack');
		$mform->setDefault('userstartdate', $userstartdate);
		
		/* DATE TIME SELECTOR (ENABLE OPTION)
		   userenddate:User end date.
           Rule types: No rules (optional).
         */
		$mform->addElement('date_time_selector', 'userenddate', get_string('userenddate', 'local_slack'), array('optional' => true));
        $mform->setDefault('userenddate', 0);
        $mform->addHelpButton('userenddate', 'userenddate', 'local_slack');
        $mform->setDefault('userenddate', $userenddate);		
		
		/* UPLOAD FILE (IMAGE FORMAT)
		   userpic:User picture.
           Rule types: User has to upload an image.
         */
        $userpicoptions = array('subdirs' => 0, 'maxbytes' => '', 'context' => $context,
                                'accepted_types' => array('.png', '.jpeg'),
 								'return_types' => FILE_INTERNAL | FILE_EXTERNAL);
        $mform->addElement('filemanager', 'userpic', get_string('userpic', 'local_slack'), null, $userpicoptions);
        $mform->addRule('userpic', get_string('required', 'local_slack'), 'required', null, 'client');
        $mform->setType('userpic', PARAM_RAW);
        $mform->addHelpButton('userpic', 'userpic', 'local_slack');
		$mform->setDefault('userpic', $userpic);
		
		/* UPLOAD FILE (DOCUMENT FORMAT)
		   certificate:Certificate
           Rule types: User has to upload an certificate.
         */
        $certoptions = array('subdirs' => 0, 'maxbytes' => '', 'context' => $context,
                             'accepted_types' => array('.txt', '.doc', '.docx', '.pdf'),
								 'return_types' => FILE_INTERNAL | FILE_EXTERNAL );
        $mform->addElement('filemanager', 'certificate', get_string('certificate', 'local_slack'), null, $certoptions);
	    $mform->addRule('certificate', get_string('required', 'local_slack'), 'required', null, 'client');
        $mform->setType('certificate', PARAM_RAW);
        $mform->addHelpButton('certificate', 'certificate', 'local_slack');
		$mform->setDefault('certificate', $certificate);
		
		/* UPLOAD FILE (MULTIMEDIA FORMAT)
		   multimedia:Multimedia
           Rule types: No rules (optional).
         */
        $mediaoptions = array('subdirs' => 0, 'maxbytes' => '', 'maxfiles' => 1, 'context' => $context,
                              'accepted_types' => array('video/mp4', 'audio/mp3'),
		  				      'return_types' => FILE_INTERNAL | FILE_EXTERNAL );
        $mform->addElement('filemanager', 'multimedia', get_string('multimedia', 'local_slack'), null, $mediaoptions);		
        $mform->setType('certificate', PARAM_CLEANHTML);
        $mform->addHelpButton('multimedia', 'multimedia', 'local_slack');
        $mform->setDefault('multimedia', $multimedia);     	
		
		/* PAGE EDITOR
		   page:Description.
		   Rule types: User has to enter the text input.
         */
        $mform->addElement('editor', 'page', get_string('description', 'local_slack'), null, page_get_editor_options($context));
        $mform->setType('page', PARAM_RAW);
        $mform->addHelpButton('page', 'description', 'local_slack');
		$mform->setDefault('page', array('text' => $description, 'format' => FORMAT_HTML));

		// Action buttons.
        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('userdata-submit', 'local_slack'));
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', '', false);        
	}
	
    /**
     *
     * The validation() function defines the form validation.
     *
     * @param My_Type $data
     * @param My_Type $files
     */
    public function validation($data, $files) {
        global $DB, $CFG;
        $errors = parent::validation($data, $files);        
		return $errors;
    }
	
	/**
     *
     * The data_preprocessing() function defines the html editor data.
     *
     * @param My_Type $defaultvalues
     */
    public function data_preprocessing($defaultvalues) {
        if ($this->current->instance) {
            $draftitemid = file_get_submitted_draft_itemid('page');
            $defaultvalues['page']['format'] = $defaultvalues['contentformat'];
            $defaultvalues['page']['text']   = file_prepare_draft_area($draftitemid, $context->id,
                                                'local_slack', 'content', 0,
                                                page_get_editor_options($context), $defaultvalues['content']);
            $defaultvalues['page']['itemid'] = $draftitemid;
        }
        if (!empty($defaultvalues['displayoptions'])) {
            $displayoptions = unserialize($defaultvalues['displayoptions']);
            if (isset($displayoptions['printintro'])) {
                $defaultvalues['printintro'] = $displayoptions['printintro'];
            }
            if (isset($displayoptions['printheading'])) {
                $defaultvalues['printheading'] = $displayoptions['printheading'];
            }
            if (!empty($displayoptions['popupwidth'])) {
                $defaultvalues['popupwidth'] = $displayoptions['popupwidth'];
            }
            if (!empty($displayoptions['popupheight'])) {
                $defaultvalues['popupheight'] = $displayoptions['popupheight'];
            }
        }
    }
}

$mform = new useredit_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/index.php'));
} else if ($mform->is_submitted()) {
    if ($data = $mform->get_data()) {		
        $saveinfo = new stdClass();
		$saveinfo->id = $data->imsid;        
		$saveinfo->fullname = $data->fullname;
		$saveinfo->password = $data->password;		
        $saveinfo->email = $data->email;
        $saveinfo->age = $data->age;
		$saveinfo->saveskills = $data->saveskills;		
		$saveinfo->profession = $data->profession;
		$saveinfo->introexp = $data->introexp;
		$saveinfo->dob = $data->dob;
		$saveinfo->userstartdate = $data->userstartdate;
		$saveinfo->userenddate = $data->userenddate;
		$saveinfo->userpic = $data->userpic;
		$saveinfo->certificate = $data->certificate;
		$saveinfo->multimedia = $data->multimedia;
        $saveinfo->description = $data->page['text'];
		$saveinfo->descriptionformat = $data->page['format'];		
		    
        if ($DB->record_exists('userdata', array('id' => $imsid))) {
		    $DB->update_record('userdata', $saveinfo);			
		}		        		
		redirect(new moodle_url('/local/slack/metadata.php'));
	}    
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
