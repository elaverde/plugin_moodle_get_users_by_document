<?php
namespace local_user_get_users_by_field\external;

defined('MOODLE_INTERNAL') || die();

require_once ($CFG->libdir . "/externallib.php");

use external_api;
use external_function_parameters;
use external_single_structure;
use external_multiple_structure;
use external_value;
use context_system;
use moodle_url;

class get_users extends external_api {

    public static function get_users_parameters() {
        return new external_function_parameters(
            array(
                'search' => new external_value(PARAM_RAW, 'Search term', VALUE_DEFAULT, ''),
            )
        );
    }

    public static function get_users($search) {
        global $DB, $CFG, $USER;

        // Validate parameters.
        $params = self::validate_parameters(self::get_users_parameters(), array('search' => $search));

        // Capability check.
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('moodle/user:viewdetails', $context);

        $sql = "SELECT u.*
        FROM {user} u
        LEFT JOIN {user_info_data} uid ON u.id = uid.userid
        LEFT JOIN {user_info_field} uif ON uid.fieldid = uif.id
        WHERE u.email = :value1 OR u.username = :value2 OR (uif.shortname = :customfield AND uid.data = :value3)";

        $users = $DB->get_records_sql($sql, array('value1' => $search, 'value2' => $search, 'value3' => $search, 'customfield' => 'identificacion'));

        $userlist = array();
        foreach ($users as $user) {
            // Fetch custom fields for the user.
            $customfields = array();
            $customfield_data = $DB->get_records_sql(
                "SELECT uif.shortname, uid.data
                FROM {user_info_data} uid
                JOIN {user_info_field} uif ON uid.fieldid = uif.id
                WHERE uid.userid = :userid",
                array('userid' => $user->id)
            );

            foreach ($customfield_data as $field) {
                $customfields[] = array(
                    'type' => 'text',
                    'value' => $field->data,
                    'displayvalue' => $field->data,
                    'name' => $field->shortname,
                    'shortname' => $field->shortname
                );
            }
            $userdetails = array(
                'id' => $user->id,
                'username' => $user->username,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'fullname' => fullname($user),
                'email' => $user->email,
                'department' => $user->department,
                'institution' => $user->institution,
                'idnumber' => $user->idnumber,
                'firstaccess' => $user->firstaccess,
                'lastaccess' => $user->lastaccess,
                'auth' => $user->auth,
                'suspended' => $user->suspended,
                'confirmed' => $user->confirmed,
                'lang' => $user->lang,
                'theme' => $user->theme,
                'timezone' => $user->timezone,
                'mailformat' => $user->mailformat,
                'trackforums' => $user->trackforums,
                'description' => $user->description,
                'descriptionformat' => $user->descriptionformat,
                'city' => $user->city,
                'country' => $user->country,
                'profileimageurlsmall' => (new moodle_url('/user/pix.php/' . $user->id . '/f2.jpg'))->out(false),
                'profileimageurl' => (new moodle_url('/user/pix.php/' . $user->id . '/f1.jpg'))->out(false),
                'customfields' => $customfields, // Add the custom fields here
            );
            $userlist[] = $userdetails;
        }

        return array(
            'users' => $userlist,
            'warnings' => array(),
        );
    }

    public static function get_users_returns() {
        return new external_single_structure(
            array(
                'users' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'ID of the user'),
                            'username' => new external_value(PARAM_TEXT, 'Username'),
                            'firstname' => new external_value(PARAM_TEXT, 'First name'),
                            'lastname' => new external_value(PARAM_TEXT, 'Last name'),
                            'fullname' => new external_value(PARAM_TEXT, 'Full name'),
                            'email' => new external_value(PARAM_TEXT, 'Email'),
                            'department' => new external_value(PARAM_TEXT, 'Department'),
                            'institution' => new external_value(PARAM_TEXT, 'Institution'),
                            'idnumber' => new external_value(PARAM_TEXT, 'ID number'),
                            'firstaccess' => new external_value(PARAM_INT, 'First access time'),
                            'lastaccess' => new external_value(PARAM_INT, 'Last access time'),
                            'auth' => new external_value(PARAM_TEXT, 'Auth method'),
                            'suspended' => new external_value(PARAM_BOOL, 'Suspended status'),
                            'confirmed' => new external_value(PARAM_BOOL, 'Confirmed status'),
                            'lang' => new external_value(PARAM_TEXT, 'Language'),
                            'theme' => new external_value(PARAM_TEXT, 'Theme'),
                            'timezone' => new external_value(PARAM_TEXT, 'Timezone'),
                            'mailformat' => new external_value(PARAM_INT, 'Mail format'),
                            'trackforums' => new external_value(PARAM_INT, 'Track forums'),
                            'description' => new external_value(PARAM_RAW, 'Description'),
                            'descriptionformat' => new external_value(PARAM_INT, 'Description format'),
                            'city' => new external_value(PARAM_TEXT, 'City'),
                            'country' => new external_value(PARAM_TEXT, 'Country'),
                            'profileimageurlsmall' => new external_value(PARAM_URL, 'URL of the small profile image'),
                            'profileimageurl' => new external_value(PARAM_URL, 'URL of the profile image'),
                            'customfields' => new external_multiple_structure(
                                new external_single_structure(
                                    array(
                                        'type' => new external_value(PARAM_TEXT, 'Custom field type'),
                                        'value' => new external_value(PARAM_RAW, 'Custom field value'),
                                        'displayvalue' => new external_value(PARAM_RAW, 'Display value of the custom field'),
                                        'name' => new external_value(PARAM_TEXT, 'Name of the custom field'),
                                        'shortname' => new external_value(PARAM_TEXT, 'Short name of the custom field'),
                                    )
                                )
                            ),
                        )
                    )
                ),
                'warnings' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'item' => new external_value(PARAM_TEXT, 'item'),
                            'itemid' => new external_value(PARAM_INT, 'itemid'),
                            'warningcode' => new external_value(PARAM_TEXT, 'warningcode'),
                            'message' => new external_value(PARAM_TEXT, 'message'),
                        )
                    ),
                    'warnings'
                ),
            )
        );
    }
}
