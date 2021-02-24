<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/home/timetable', 'Home::timetable',  ['as' => 'timetable']);




$routes->get('/admin', 'Admin::index', ['filter' => 'role:admins'], ['as' => 'admin']);
$routes->get('/admin/edit/(:any)', 'Admin::edit/$1', ['as' => 'edit']);
$routes->post('/admin/update/', 'Admin::update/', ['as' => 'update']);
$routes->post('/admin/destroy/', 'Admin::destroy/', ['as' => 'destroy']);
$routes->post('/admin/deactivateuser/', 'Admin::deactivate/', ['as' => 'deactivateuser']);


$routes->get('/classes', 'Classes::index',  ['as' => 'classes']);
$routes->get('/classes/addclass', 'Classes::addclass',  ['as' => 'addclass']);
$routes->get('/classes/updateclassesactivationstatus', 'Classes::updateclassesactivationstatus',  ['as' => 'updateclassesactivationstatus']);
$routes->post('/classes/insertclass', 'Classes::insertclass',  ['as' => 'insertclass']);
$routes->get('/classes/editclass/(:any)', 'Classes::editclass/$1',  ['as' => 'editclass']);
$routes->get('/classes/classactivation/(:num)', 'Classes::classactivation/$1',  ['as' => 'classactivation']);
$routes->post('/classes/commitedit', 'Classes::commitedit',  ['as' => 'commitedit']);
$routes->post('/classes/deleteClass/', 'Classes::deleteClass/', ['as' => 'deleteClass']);
$routes->get('/classes/classescoursestodo/(:num)', 'Classes::classescoursestodo/$1',  ['as' => 'classescoursestodo']);
$routes->post('/classes/classescoursestodosave', 'Classes::classescoursestodosave',  ['as' => 'classescoursestodosave']);




$routes->get('/students', 'Students::index',  ['as' => 'showactivestudents']);
$routes->post('/students/editstudent/', 'Students::editstudent',  ['as' => 'editstudent']);
$routes->post('/students/updateStudent/', 'Students::updateStudent',  ['as' => 'updateStudent']);


$routes->get('/courses', 'Courses::index',  ['as' => 'courses']);
$routes->get('/courses/addcourse', 'Courses::addCourse',  ['as' => 'addcourse']);
$routes->post('/courses/insertcourse', 'Courses::insertcourse',  ['as' => 'insertcourse']);
$routes->post('/courses/insertdateofcourse', 'Courses::insertdateofcourse',  ['as' => 'insertdateofcourse']);
$routes->post('/courses/commitInsertDateOfCourse', 'Courses::commitInsertDateOfCourse',  ['as' => 'commitInsertDateOfCourse']);
$routes->post('/courses/deleteDateOfCourse', 'Courses::deleteDateOfCourse',  ['as' => 'deleteDateOfCourse']);
// $routes->post('/courses/editDateOfCourse', 'Courses::editDateOfCourse',  ['as' => 'editDateOfCourse']);
$routes->post('/courses/submitDateEdit', 'Courses::submitDateEdit',  ['as' => 'submitDateEdit']);
$routes->post('/courses/deletecourse', 'Courses::deletecourse',  ['as' => 'deletecourse']);


// $routes->get('/courses/editDateOfCourse/(:mum)', 'Courses::editDateOfCourse/$1',  ['as' => 'editDateOfCourse']);
$routes->get('/courses/editdateofcourse/(:num)', 'Courses::editdateofcourse/$1',  ['as' => 'editdateofcourse']);

$routes->get('/rooms', 'Rooms::index',  ['as' => 'rooms']);
$routes->get('/rooms/addroom', 'Rooms::addroom',  ['as' => 'addroom']);
$routes->post('/rooms/insertroom', 'Rooms::insertroom',  ['as' => 'insertroom']);
$routes->get('/rooms/editroom/(:any)', 'Rooms::editroom/$1',  ['as' => 'editroom']);
$routes->post('/rooms/updateroom', 'Rooms::updateroom',  ['as' => 'updateroom']);
$routes->post('/rooms/deleteroom', 'Rooms::deleteroom',  ['as' => 'deleteroom']);

$routes->get('/vacation', 'Vacation::index',  ['as' => 'vacation']);
$routes->get('/vacation/addvacation', 'Vacation::addVacation',  ['as' => 'addvacation']);
$routes->post('/vacation/insertvacation', 'Vacation::insertvacation',  ['as' => 'insertvacation']);
$routes->post('/vacation/deletevacation', 'Vacation::deletevacation',  ['as' => 'deletevacation']);
$routes->get('/vacation/editvacation/(:num)', 'Vacation::editvacation/$1',  ['as' => 'editvacation']);
$routes->post('/vacation/insertvacationedit', 'Vacation::insertVacationEdit',  ['as' => 'insertvacationedit']);

$routes->get('/dashboard', 'Dashboard::index',  ['as' => 'dashboard']);

$routes->get('/virtualclassroom', 'VirtualClassroom::index',  ['as' => 'virtualclassroom']);
$routes->post('/virtualclassroom/addclassroompost', 'VirtualClassroom::addclassroompost',  ['as' => 'addclassroompost']);
$routes->get('/virtualclassroom/textimageupload', 'VirtualClassroom::textimageupload',  ['as' => 'textimageupload']);
$routes->post('/virtualclassroom/deletedatepost', 'VirtualClassroom::deletedatepost',  ['as' => 'deletedatepost']);
$routes->get('/virtualclassroom/editdatepost/(:num)', 'VirtualClassroom::editdatepost/$1',  ['as' => 'editdatepost']);
$routes->post('/virtualclassroom/insertdatepostedit', 'VirtualClassroom::insertdatepostedit',  ['as' => 'insertdatepostedit']);
$routes->post('/virtualclassroom/datenotice', 'VirtualClassroom::datenotice',  ['as' => 'datenotice']);

$routes->get('/news', 'News::index',  ['as' => 'news']);
$routes->get('/news/create', 'News::create',  ['as' => 'createnews']);
$routes->get('/news/insertnews', 'News::insertnews',  ['as' => 'insertnews']);
$routes->post('/news/deletenews', 'News::deletenews',  ['as' => 'deletenews']);

$routes->get('/health', 'Health::index',  ['as' => 'health']);
$routes->get('/health/callinsick', 'Health::callinsick',  ['as' => 'callinsick']);
$routes->post('/health/certificate', 'Health::uploadhealtcertificate',  ['as' => 'uploadhealtcertificate']);
$routes->post('/health/illnessform', 'Health::uploadillnessform',  ['as' => 'uploadillnessform']);
$routes->get('/health/illnessmanagement', 'Health::illnessmanagement',  ['as' => 'illnessmanagement']);
$routes->get('/health/viewnotification/(:num)', 'Health::viewnotification/$1',  ['as' => 'viewnotification']);
$routes->post('/health/complete', 'Health::completenotification',  ['as' => 'completenotification']);
$routes->post('/health/completeunexcused', 'Health::completenotificationunexcused',  ['as' => 'completenotificationunexcused']);
$routes->post('/health/notificationenddate', 'Health::notificationenddate',  ['as' => 'notificationenddate']);
$routes->get('/health/editnotification/(:num)', 'Health::editnotification/$1',  ['as' => 'editnotification']);

$routes->get('/jobs', 'Jobs::index',  ['as' => 'jobs']);
$routes->get('/jobs/create', 'Jobs::create',  ['as' => 'createjobs']);
$routes->get('/jobs/insertjobs', 'Jobs::insertjobs',  ['as' => 'insertjobs']);
$routes->post('/jobs/deletejobs', 'Jobs::deletejobs',  ['as' => 'deletejobs']);

$routes->get('/lecturers', 'Lecturers::index',  ['as' => 'showactivelecturers']);
$routes->get('/lecturers/editlecturer/(:num)', 'Lecturers::editlecturer/$1',  ['as' => 'editlecturer']);
$routes->post('/lecturers/save', 'Lecturers::save',  ['as' => 'savelecturer']);

$routes->get('/news/unread', 'News::getUsersUnreadNews',  ['as' => 'showUnread']);
$routes->post('/news/markread', 'News::markNewsAsRead',  ['as' => 'markread']);





























//$routes->addRedirect('/', 'login');



/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
