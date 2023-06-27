<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * App Class
 *
 * Stop talking and start faking!
 */
class App extends Admin_Controller
{
    protected $fake;

    protected $teacherLimit = 15;
    protected $parentsLimit = 20;
    protected $studentLimit = 15; //  Each Class
    protected $schoolyearLimit = 2;
    protected $noticeLimit = 15;

    function __construct()
    {
        parent::__construct();

        // initiate faker
        $this->load->library('faker');
        $this->fake = Faker\Factory::create();

        // load any required models
        $this->load->model('teacher_m');
        $this->load->model('parents_m');
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('schoolyear_m');
        $this->load->model('student_m');
        $this->load->model('studentextend_m');
        $this->load->model('studentrelation_m');
        $this->load->model('subject_m');
        $this->load->model('routine_m');
        $this->load->model('notice_m');

        // redirect('dashboard/index');
    }

    public function index()
    {
        $this->_truncate_db();
        $this->teacher();
        $this->classes();
        $this->section();
        $this->parents();
        $this->schoolYear();
        $this->student(1);
        $this->studentLimit = 7;
        $this->student(2);
        $this->subject();
        $this->routine(1);
        $this->routine(3);
        $this->notice(1);
    }


    public function teacher()
    {
        for ($i = 0; $i < $this->teacherLimit; $i++) {
            //teacher table
            $data = array(
                'name' => $this->fake->name,
                'designation' =>  'Professor',
                'dob' => $this->fake->date,
                'sex' => 'Male',
                'religion' => 'Muslim',
                'email' =>  $this->fake->email,
                'phone' => $this->fake->phoneNumber,
                'address' =>  $this->fake->address,
                'jod' => $this->fake->date($format = 'Y-m-d', $max = 'now'),
                'photo' => 'default.png',
                'username' => 'teacher'.($i+1),
                'password' => $this->teacher_m->hash('123456'),
                'usertypeID' => 2,
                'create_date' => date('Y-m-d h:i:s'),
                'modify_date' => date('Y-m-d h:i:s'),
                'create_userID' => 1,
                'create_username' => 'admin',
                'create_usertype' => 'Admin',
                'active' => 1,
            );

            $this->teacher_m->insert($data);
        }
        echo "seeding $this->teacherLimit teacher<br/>";
    }

    public function parents()
    {
        for ($i = 0; $i < $this->parentsLimit; $i++) {
            $data = array(
                'name' => $this->fake->name('male'),
                'father_name' => $this->fake->name('male'),
                'mother_name' => $this->fake->name('female'),
                'father_profession' => 'Father Profession',
                'mother_profession' => 'Mother Profession',
                'email' => $this->fake->email,
                'phone' => $this->fake->phoneNumber,
                'address' => $this->fake->address,
                'photo' => 'default.png',
                'username' => 'parent'.($i+1),
                'password' => $this->parents_m->hash('123456'),
                'usertypeID' => 4,
                'create_date' => date('Y-m-d h:i:s'),
                'modify_date' => date('Y-m-d h:i:s'),
                'create_userID' => 1,
                'create_username' => 'admin',
                'create_usertype' => 'Admin',
                'active' => 1,
            );

            $this->parents_m->insert($data);
        }
        echo "seeding $this->parentsLimit parents<br/>";
    }

    public function classes()
    {
        $class = array('One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Ex-Student');
        for ($i = 0; $i <= 10; $i++) {
            $data = array(
                'classes' => $class[$i],
                'classes_numeric' => ($i+1),
                'teacherID' => $this->fake->numberBetween(1,$this->teacherLimit),
                'note' => '',
                'create_date' => date('Y-m-d h:i:s'),
                'modify_date' => date('Y-m-d h:i:s'),
                'create_userID' => 1,
                'create_username' => 'admin',
                'create_usertype' => 'Admin',
            );
            $this->classes_m->insert($data);
        }
        echo "seeding 11 classes<br/>";

    }

    public function section()
    {
        $letter = array ('A'=>'Best','B'=>'Better','C'=>'Good');
        for ($i = 0; $i < 10; $i++) {
            foreach ($letter as $key => $value) {
                $data = array(
                    'section' => $key,
                    'category' => $value,
                    'capacity' => 15,
                    'classesID' => ($i+1),
                    'teacherID' => $this->fake->numberBetween(1,$this->teacherLimit),
                    'note' => '',
                    'create_date' => date('Y-m-d h:i:s'),
                    'modify_date' => date('Y-m-d h:i:s'),
                    'create_userID' => 1,
                    'create_username' => 'admin',
                    'create_usertype' => 'Admin',
                );
                $this->section_m->insert($data);
            }
        }
        echo "seeding 10 section<br/>";

    }

    public function schoolYear()
    {
        $schoolyear = ['2017-2018', '2018-2019'];

        for ($i = 0; $i < $this->schoolyearLimit; $i++) {
            $data = array(
                'schooltype' => 'classbase',
                'schoolyear' => $schoolyear[$i],
                'create_date' => date('Y-m-d h:i:s'),
                'modify_date' => date('Y-m-d h:i:s'),
                'create_userID' => 1,
                'create_username' => 'admin',
                'create_usertype' => 'Admin',
            );
            $this->schoolyear_m->insert($data);
        }
        echo "seeding $this->schoolyearLimit Academic Year<br/>";
    }

    public function student($schoolYear)
    {
        $letter = array ('A','B','C');
        $country = ['BD', 'AU', 'IN', 'NG', 'US', 'PK', 'GB', 'ES', 'TR', 'SA', 'CA'];
        $bloodgroup = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        $studentGroup = [1, 2 , 3];
        $username = 1;


        for($j=1; $j <= 10; $j++) {
            for ($i = 0; $i < $this->studentLimit; $i++) {
                $section = $this->fake->numberBetween(1,3);
                $data = array(
                    'name' => $this->fake->name,
                    'dob' => date('Y-m-d'),
                    'sex' =>'Male',
                    'religion' => 'Muslim',
                    'email' => $this->fake->email,
                    'phone' => $this->fake->phoneNumber,
                    'address' => $this->fake->address,
                    'classesID' => $j,
                    'sectionID' => ((3*($j-1))+$section),
                    'roll' => $i+1,
                    'bloodgroup' => $this->fake->randomElement($bloodgroup),
                    'country' => $this->fake->randomElement($country),
                    'registerNO' => $this->fake->numerify('INI###'),
                    'state' => $this->fake->state,
                    'library' => 0,
                    'hostel' => 0,
                    'transport' => 0,
                    'create_date' => date('Y-m-d h:i:s'),
                    'photo' => 'default.png',
                    'parentID' => $this->fake->numberBetween(1,$this->parentsLimit),
                    'createschoolyearID' => $schoolYear,
                    'schoolyearID' => $schoolYear,
                    'username' => 'student'.$username,
                    'password' => $this->student_m->hash('123456'),
                    'usertypeID' => 3,
                    'modify_date' => date('Y-m-d h:i:s'),
                    'create_userID' => 1,
                    'create_username' => 'admin',
                    'create_usertype' => 'Admin',
                    'active' => 1,
                );

                $studentID = $this->student_m->insert_student($data);
                $groupID = $this->fake->randomElement($studentGroup);
                $studentExtend = [
                    'studentID' => $studentID,
                    'optionalsubjectID' => 0,
                    'studentgroupID' => $groupID,
		    'extracurricularactivities' => '',
                    'remarks' => ''
                ];
                $this->studentextend_m->insert($studentExtend);

                $getStudent = $this->student_m->get_student($studentID);

                
                $section = $this->section_m->get_section($getStudent->sectionID);
                $classes = $this->classes_m->get_classes($getStudent->classesID);

                if(count($classes)) {
                    $setClasses = $classes->classes;
                } else {
                    $setClasses = NULL;
                }

                if(count($section)) {
                    $setSection = $section->section;
                } else {
                    $setSection = NULL;
                }



                $arrayStudentRelation = array(
                    'srstudentID' => $studentID,
                    'srname' => $getStudent->name,
                    'srclassesID' => $getStudent->classesID,
                    'srclasses' => $setClasses,
                    'srroll' => $getStudent->roll,
                    'srregisterNO' => $getStudent->registerNO,
                    'srsectionID' => $getStudent->sectionID,
                    'srsection' => $setSection,
                    'srschoolyearID' => $getStudent->schoolyearID,
                    'srstudentgroupID' => $groupID,
                    'sroptionalsubjectID' => 0
                );
                
                $this->studentrelation_m->insert_studentrelation($arrayStudentRelation);

                $username++;
            }
        }
        echo "seeding $this->studentLimit student of each class in $schoolYear<br/>";

    }

    public function subject()
    {
        $teachers = $this->db->query('Select teacherID,name From teacher')->result();
        $option = [1,0,1,1];
        $array = array ('Bangla','Math','English', 'Psychology', 'Computer Science', 'Physics', 'Chemistry', 'Sociology');
        for ($i = 1; $i <=10; $i++) {
            for ($k=0; $k < 8; $k++) {
                $teacherRandomID = $this->fake->numberBetween(0,$this->teacherLimit-1);
                $data = array(
                    'classesID' => $i,
                    'teacherID' => $teachers[$teacherRandomID]->teacherID,
                    'type' => $this->fake->randomElement($option),
                    'passmark' => 33,
                    'finalmark' => 100,
                    'subject' => $array[$k],
                    'subject_author' => $this->fake->name,
                    'subject_code' => $this->fake->bothify('??? ###') ,
                    'teacher_name' => $teachers[$teacherRandomID]->name,
                    'create_date' => date('Y-m-d h:i:s'),
                    'modify_date' => date('Y-m-d h:i:s'),
                    'create_userID' => 1,
                    'create_username' => 'admin',
                    'create_usertype' => 'Admin',
                );
                $this->subject_m->insert($data);
            }
        }
        echo "seeding 8 subject of each class<br/>";

    }

     public function routine($schoolyearID)
     {
         $days_array = array(
             'SUNDAY',
             'MONDAY',
             'TUESDAY',
             'WEDNESDAY',
             'THURSDAY',
             'FRIDAY',
             'SATURDAY',
         );

         for ($day=0; $day < 7; $day++) {
             for ($subject=1; $subject <=8; $subject++) {
                 $data = array(
                     'classesID' => $this->fake->numberBetween(1,10),
                     'sectionID' => $this->fake->numberBetween(1,3),
                     'subjectID' => $this->fake->numberBetween(1,8),
                     'teacherID' => $this->fake->numberBetween(1,$this->teacherLimit),
                     'schoolyearID' => $schoolyearID,
                     'day' => $days_array[$day],
                     'start_time' => $this->fake->time($format = 'H:i A'),
                     'end_time' => $this->fake->time($format = 'H:i A'),
                     'room' => $this->fake->bothify('###'),
                 );
                 $this->routine_m->insert($data);
             }
         }
         echo "seeding Routine in $schoolyearID<br/>";
     }

    public function notice($schoolyearID)
    {
        for ($i = 0; $i < $this->noticeLimit; $i++) {
            $data = array(
                'title' => $this->fake->sentence(4, true),
                'notice' => $this->fake->paragraph(5, true),
                'schoolyearID' => $schoolyearID,
                'date' => date('Y-m-d'),
                'create_date' => date('Y-m-d h:i:s')
            );
            $this->notice_m->insert($data);
        }
        echo "seeding $this->noticeLimit  Notice in $schoolyearID<br/>";
    }


    private function _truncate_db()
    {
		$this->db->truncate('teacher');
		$this->db->truncate('parents');
		$this->db->truncate('classes');
		$this->db->truncate('section');
        $this->db->truncate('schoolyear');
		$this->db->truncate('student');
		$this->db->truncate('studentextend');
        $this->db->truncate('studentrelation');
		$this->db->truncate('subject');
		// $this->db->truncate('routine');
        $this->db->truncate('notice');
    }

    public function version()
    {
        echo config_item('ini_version');
    }
}
