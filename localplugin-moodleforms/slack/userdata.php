<?php
/*  DOCUMENTATION
    .............

    require('../../config.php');
	It loads all the Moodle core library by initialising the database connection, session, current course, theme and language.
	
	require_once($CFG->libdir.'/adminlib.php');
	states the functions and classes used during installation, upgrades and for admin settings.
	
	$path = optional_param('path', '', PARAM_PATH);
    $pageparams = array();
    if ($path) {
        $pageparams['path'] = $path;
    }
	In Moodle you can call or pass the parameters. As moodle_url doesn't provide you a way of generating the array, so you'll
	have to construct the params yourself. By defining your custom page to the function admin external page.
	
	Core global variables in Moodle are identified using uppercase variables (ie $CFG, $SESSION, $USER, $COURSE, $SITE, $PAGE,
	$DB and $THEME).
	$CFG: $CFG stands for configuration. This global variable contains configuration values of the Moodle setup, such as the
	root directory, data directory, database details, and other config values.
	
	$SESSION: Moodle's wrapper round PHP's $_SESSION.
	
    $USER: Holds the user table record for the current user. This will be the 'guest' user record for people who are not
	logged in.
	
	$SITE: Frontpage course record. This is the course record with id=1.
	
	$COURSE: This global variable holds the current course details. An alias for $PAGE->course.
	
	$PAGE: This is a central store of information about the current page we are generating in response to the user's request.
	ex: $PAGE->set_url('/mod/mymodulename/view.php', array('id' => $cm->id));
        $PAGE->set_title('My modules page title');
        $PAGE->set_heading('My modules page heading');

    $OUTPUT: $OUTPUT is an instance of core_renderer or one of its subclasses. It is used to generate HTML for output.
	ex: echo $OUTPUT->header();
	    echo $OUTPUT->heading($pagetitle);
		
	$CONTEXT: A context is combined with role permissions to define a User's capabilities on any page in Moodle.

    $DB: This holds the database connection details. It is used for all access to the database.

    $PAGE->set_url('/local/slack/userdata.php');
	Every moodle page needs page url through a call to $PAGE->set_url. You are trying to define the page url for setting the 
	custom page.
	
	require_login();
	It verifies that user is logged in before accessing any moodle page.
	
	$PAGE->set_pagelayout('admin'); Set a default pagelayout. 
	(or) 
    $PAGE->set_pagelayout('standard');
	When setting the page layout you should use the layout that is the closest match to the page you are creating. 
    Layouts are used by themes to determine what is shown on the page. There are different layouts that can be, and are used
    throughout Moodle core that you can use within your code. The list of common layouts you are best to look at
	theme/base/config.php or refer to the list below.
	
	It's important to know that the theme determines what layouts are available and how each looks. If you select a layout
	that the theme doesn't support then it will revert to the default layout while using that theme. Themes are also able to 
	specify additional layouts, however its important to spot them and know that while they may work with one theme they are
	unlikely to work as you expect with other themes.
	
	$context = context_system::instance();
	$PAGE->set_context($context);
	Setting the context of the page should call set_context() once with the context that is most appropriate to the page you 
	are creating. If it is a plugin then the context to use would be the context you are using for your capability checks.

    admin_externalpage_setup();
    This function call ensures the user is logged in, and makes sure that they have the proper role permission to access the 
	page.It also configures all $PAGE properties needed for navigation.
	
	$header = $SITE->fullname;
	defines the title of your custom page.
	
	$PAGE->set_title(get_string('pluginname', 'local_slack'));
	defines the title of your plugin at the browser tab.
	
	$PAGE->set_heading($header);
	to display your plugin fullname.

    echo $OUTPUT->header();
	this line prints the header of the page and adds one heading to the page at the top of the content region. Page headings 
	are very important in Moodle and should be applied consistently.
	
	echo $OUTPUT->footer();
	this line prints the footer of the page.
	
	Moodle Forms:
	(1) To create a new form, you need to define a new class that extends moodleform. Typically those classes are put in a 
	    separate file named with _form.php postfix. You will create your custom class, userdata_form for the user input
	    submission. You can save the code under classes directory (ex: local/slack/classes/userdata_form.php) (or) you can 
		save the code outside the classes directory, so Moodle will automatically load the class when required. In Moodle, 
		we name the input fields as form elements: textbox, select, filepicker, filemanager, datetime selector etc.,

    (2) We define the structure of userdata_form by overriding a method called definition().
        definition() is used to define the form elements in the form and this definition will be used for validating data 
		submitted as well as for printing the form. For select and checkbox type elements only data that could have been 
		selected will be allowed. And only data that corresponds to a form element in the definition will be accepted as 
		submitted data.
		
		The definition of the form elements to be included in the form, their 'types' (PARAM_*), helpbuttons etc. is all 
		included in a function you must define in your class.

        The definition() should include all elements that are going to be used on form, some elements may be removed or 
		tweaked later in definition_after_data(). Please do not create conditional elements in definition(), the definition()
		should not directly depend on the submitted data.
	
	    Note that the definition function is called when the form class is instantiated. 
		
	(3) One of the properties of the extended class is $this->_form. This is a MoodleQuickForm class that extends a class 
	    from QuickForm library. We will assign it to the variable $mform as we will use it a lot. 
	
	(4) Form elements:
	    To add a new element we use addElement method, there are few parameters (params) to be defined:
		1. The first param is the type of the element to add. 
		2. The second is the elementname to use which is normally the html name of the element in the form. 
		3. The third is often the text for the label (language string) for the element.
		ex: $mform->addElement('text', 'fullname', get_string('fullname, local_slack'));
		
        After addElement, we set the type of the value that should come from HTML form. This information will be used for 
		validation, you can use any of the PARAM_* constants here.
		ex: $mform->setType('fullname', PARAM_TEXT);
		
		PARAM_TEXT	  1. Cleaning data that is expected to contain multi-lang content. 
                      2. It will strip all html tags, but will still let tags for multilang support through.
        
		PARAM_RAW	  1. PARAM_RAW means no cleaning whatsoever, it is used mostly for data from the html editor. 
                      2. Data from the editor is later cleaned before display using format_text() function. 
                      3. PARAM_RAW can also be used for data that is validated by some other way or printed by p() or s().
        
		PARAM_INT	  can be for integers.
		
		PARAM_FLOAT	  can be for decimal numbers but is not recommended for user input since it does not work for languages 
		              that use, as a decimal separator.
					 
        PARAM_NOTAGS  1. can work for cleaning data that is expected to be plain text. 
		              2. It will strip *all* html type tags. It will still *not* let tags for multilang support through. 
					  3. should be used for instance for email addresses where no multilang support is appropriate.
					  
        PARAM_ACTION  is an alias of PARAM_ALPHA and is used for hidden fields specifying form actions.
		
        PARAM_CLEAN	  is deprecated.
		
		Additional methods:
		...................
		
		$mform->addGroup($saveoptions, "saveclass", get_string('saveclass', 'local_slack'), '', false);
		A 'group' in formslib is just a group of elements that will have a label and will be included on one line.
		Like we added group of buttons, submit and cancel on the same line in the below code.
		
		$mform->addRule('fullname', get_string('required', 'local_slack'), 'required', null, 'client');		
		1. The first param(element) is an element name.
		2. The second is a label(language string) is the error message that will be displayed to the user. 
		3. The third parameter(type) is the type of rule.
		4. The fourth param(format) is used for extra data needed with some rules such as minlength and regex.
		5. The fifth parameter(validation) validates input data on server or client side, if validation is done on client 
		   side then it will be checked on the server side as well.

           * @param    string     $element     Form element name
           * @param    string     $message     Message to display for invalid data
           * @param    string     $type        Rule type, use getRegisteredRules() to get types
           * @param    string     $format      (optional)Required for extra rule data
           * @param    string     $validation  (optional)Where to perform validation: "server", "client"
           * @param    boolean    $reset       Client-side validation: reset the form element to its original value if there is an error?
           * @param    boolean    $force       Force the rule to be applied, even if the target form element does not exist
		
        $mform->addHelpButton('fullname', 'fullname', 'local_slack');
        1. The first param(element) is the name of the form element to add the help button.
		2. The second param(identifier) is the identifier for the help string and its title.
		3. The third param(component) is the component name to look for the help string in language string file(lang/en/local_slack)

        get_string($identifier, $component) is the title of the help page
        get_string("{$identifier}_help", $component) is the content of the help page
        So you will need to have both $identifier and {$identifier}_help defined in order for the help button to be created.

		$mform->setDefault('fullname', 'I'm a moodler');
		To set the default value for the form element.
		
		$mform->disabledIf('expirydate', 'notify', 'eq', 0);
		$mform->disabledIf('expirydate', 'notify', 'neq', 0);
		We can enable/disable an element based on some condition. The first parameter is (again) an id of the element we 
		want to enable/disable.
		The second is the element the value we depend on, 
		The third is condition (eq, neq)/(=, !=).
    	The fourth is a value to compare to.
		In the above examples, The client-side javascript will work here.
        If notify is equal to 0 (enable), expiry notify will forward an expirydate notification to the user.
        If notify is not equal to 0 (disable), expiry notify will not forward an expirydate notification to the user.
 
        add_action_buttons() is a method that adds submit & cancel buttons to the form.
		
		Validation:
		Validation method will validate the user input data by throwing errors.
		
		$errors = parent::validation($data, $files);
		Start with calling a parent validation function. If there are any errors, return them in array - one key/value for
		one error. The key must match one of the element names in the form - Moodle will display an error next to the element
		form. validation() method return an empty array if there are no errors.
		
		Form processing
		Finally, process the form. The basic workflow would be:
		
		$mform = new userdata_form();
        if ($mform->is_cancelled()) {
            // form cancelled, redirect
            redirect(new moodle_url('view.php', array()));
            return;
        } else if (($data = $mform->get_data())) {
            // form has been submitted
            userdata_save_submission($data);
        } else {
            // Form has not been submitted or there was an error
            // Just display the form
            $mform->set_data(array('id' => $id));
            $mform->display();
        }
		
		function data_preprocessing(&$default_values):
		Processing the moodle data is defined by Data Preprocessing.
		Data preprocessing allows the original data to be transformed into a suitable shape to be used by a particular data
		mining algorithm or framework. It is important to notice that preprocessing tasks are normally done by administrators
		This is normally a manual process in which the administrator has to apply a number of general data preprocessing tasks
		(data cleaning, user identification, session identification, path completion, transaction identification, data 
		transformation and enrichment, data integration, data reduction). 
		@param array $default_values passed by reference.
     */

require('../../config.php');
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/mod/page/locallib.php');

$path = optional_param('path', '', PARAM_PATH); // $nameofarray = optional_param_array('nameofarray', null, PARAM_INT);
$pageparams = array();

if ($path) {
    $pageparams['path'] = $path;
}

global $CFG, $USER, $DB, $OUTPUT, $PAGE, $instance;

$PAGE->set_url('/local/slack/userdata.php');

require_login();

$PAGE->set_pagelayout('admin');
$context = context_system::instance();
$PAGE->set_context($context);

admin_externalpage_setup('userdata', '', $pageparams);

$header = $SITE->fullname;
$PAGE->set_title(get_string('pluginname', 'local_slack'));
$PAGE->set_heading($header);

/* Add your custom code here..*/

class userdata_form extends moodleform {

    /**
     *
     * The definition() function defines the form elements.
     *
     */
    public function definition() {

        global $DB, $CFG, $PAGE, $USER, $context, $instance;
			
		$mform = $this->_form;
		
		/* FORM ELEMENTS:
		   HEADER
		   TEXTBOX (HIDDEN)
		   TEXTBOX (TEXT)
		   TEXTBOX (PASSWORD)
		   TEXTBOX (EMAIL FORMAT)
		   TEXTBOX (NUMBER FORMAT)
           CHECKBOX	   
		   SELECT
		   STATIC
		   TEXTAREA
		   DATE TIME SELECTOR
		   DATE TIME SELECTOR (ENABLE OPTION)
		   UPLOAD FILE (IMAGE FORMAT)
		   UPLOAD FILE (DOCUMENT FORMAT)
		   UPLOAD FILE (MULTIMEDIA)
		   PAGE EDITOR
		   SUBMIT BUTTON
		   CANCEL BUTTON
		 */		

        /* HEADER
		   mformheader:Userdata Form
		 */		
        $mform->addElement('header', 'mformheader', get_string('mformheader', 'local_slack'));
		
		/* TEXTBOX (HIDDEN)
		   userid:Userid
		   Rule types: No rules (optional).
		 */
		$mform->addElement('hidden', 'userid');
        $mform->setType('userid', PARAM_INT);		
		$mform->setDefault('userid', $USER->id);

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

        /* TEXTBOX (NUMBER FORMAT)
		   age:Age
           Rule types: No rules (optional).
         */
		$mform->addElement('text', 'age', get_string('age', 'local_slack'), array("pattern = '^[0-9]{2}$'",
                           'maxlength' => '2', 'size' => '1'));
        $mform->setType('age', PARAM_INT);		
        $mform->addRule('age', get_string('maximumchars', 'local_slack', 2), 'maxlength', 2, 'client');
        $mform->addHelpButton('age', 'age', 'local_slack');
		
        /* CHECKBOX
		   saveskills:Save your skills?
           Rule types: selecting this option is mandatory.	   
         */
		$mform->addElement('checkbox', 'saveskills', get_string('saveskills', 'local_slack'));
		// $mform->addRule('saveskills', null, 'required', null, 'client');		
        $mform->addHelpButton('saveskills', 'saveskills', 'local_slack');
		$mform->setType('saveskills', PARAM_INT);
		$mform->setDefault('saveskills', 1);	
   
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
		
		/* DATE TIME SELECTOR
		   dob:Date-of-birth.
           Rule types: Must not be empty.
         */		
		$mform->addElement('date_time_selector', 'dob', get_string('dob', 'local_slack'), array('required' => true));
		// $mform->addRule('dob', get_string('required', 'local_slack'), 'required', null, 'client');
        $mform->setDefault('dob', 0);
        $mform->addHelpButton('dob', 'dob', 'local_slack');
		
		/* DATE TIME SELECTOR (ENABLE OPTION)
		   userstartdate:User start date.
           Rule types: No rules (optional).
         */
		$mform->addElement('date_time_selector', 'userstartdate', get_string('userstartdate', 'local_slack'), array('optional' => true));
        $mform->setDefault('userstartdate', 0);
        $mform->addHelpButton('userstartdate', 'userstartdate', 'local_slack');
		
		/* DATE TIME SELECTOR (ENABLE OPTION)
		   userenddate:User end date.
           Rule types: No rules (optional).
         */
		$mform->addElement('date_time_selector', 'userenddate', get_string('userenddate', 'local_slack'), array('optional' => true));
        $mform->setDefault('userenddate', 0);
        $mform->addHelpButton('userenddate', 'userenddate', 'local_slack');		
		
		/* UPLOAD FILE (IMAGE FORMAT)
		   userpic:User picture.
           Rule types: User has to upload an image.
         */
        $userpicoptions = array('subdirs' => 0, 'maxbytes' => '', 'context' => $context,
                          'accepted_types' => array('.png', '.jpeg'), 'return_types' => FILE_INTERNAL | FILE_EXTERNAL);
        $mform->addElement('filemanager', 'userpic', get_string('userpic', 'local_slack'), null, $userpicoptions);
        $mform->addRule('userpic', get_string('required', 'local_slack'), 'required', null, 'client');
        $mform->setType('userpic', PARAM_RAW);
        $mform->addHelpButton('userpic', 'userpic', 'local_slack');
		
		/* UPLOAD FILE (DOCUMENT FORMAT)
		   certificate:Certificate
           Rule types: User has to upload an certificate.
         */
        $certoptions = array('subdirs' => 0, 'maxbytes' => '', 'context' => $context,
                        'accepted_types' => array('.txt', '.doc', '.docx', '.pdf'), 'return_types' => FILE_INTERNAL | FILE_EXTERNAL );
        $mform->addElement('filemanager', 'certificate', get_string('certificate', 'local_slack'), null, $certoptions);
		$mform->addRule('certificate', get_string('required', 'local_slack'), 'required', null, 'client');
        $mform->setType('certificate', PARAM_RAW);
        $mform->addHelpButton('certificate', 'certificate', 'local_slack');
		
		/* UPLOAD FILE (MULTIMEDIA FORMAT)
		   multimedia:Multimedia
           Rule types: No rules (optional).
         */
        $mediaoptions = array('subdirs' => 0, 'maxbytes' => '', 'maxfiles' => 1, 'context' => $context,
                              'accepted_types' => array('video/mp4', 'audio/mp3'), 'return_types' => FILE_INTERNAL | FILE_EXTERNAL );
        $mform->addElement('filemanager', 'multimedia', get_string('multimedia', 'local_slack'), null, $mediaoptions);		
        $mform->setType('certificate', PARAM_CLEANHTML);
        $mform->addHelpButton('multimedia', 'multimedia', 'local_slack');       	
		
		/* PAGE EDITOR
		   page:Description.
		   Rule types: User has to enter the text input.
         */
        $mform->addElement('editor', 'page', get_string('description', 'local_slack'), null, page_get_editor_options($context));
        $mform->setType('page', PARAM_RAW);
        $mform->addHelpButton('page', 'description', 'local_slack');

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
        
		// Condition for profession.
        if (($data['profession'] <= 0)) {
            $errors['profession'] = get_string('error_profession', 'local_slack');
        }
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

$mform = new userdata_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/index.php'));
} else if ($mform->is_submitted()) {
    if ($data = $mform->get_data()) {
		
		// Serverside validation for the profession.
        if (($data->profession <= 0)) {
            $errors['profession'] = get_string('error_profession', 'local_slack');
        }
        $record = new stdClass();
        $record->userid = $data->userid;
		$record->fullname = $data->fullname;
        $record->password = $data->password;		
        $record->email = $data->email;
        $record->age = $data->age;        
		$record->saveskills = $data->saveskills;		
		$record->profession = $data->profession;
		$record->introexp = $data->introexp;
		$record->dob = $data->dob;
		$record->userstartdate = $data->userstartdate;
		$record->userenddate = $data->userenddate;
		$record->userpic = $data->userpic;
		$record->certificate = $data->certificate;
		$record->multimedia = $data->multimedia;
        $record->description = $data->page['text'];
        $record->descriptionformat = $data->page['format'];
		
        $DB->insert_record('userdata', $record);
        redirect(new moodle_url('/local/slack/metadata.php'));
	}    
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
