<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * 
 *
 * @package    mod_certificate
 * @copyright  Hernan Arango <hernan.arango@correounivalle.edu.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once('lib.php');

require_login($course, false, $cm);
$idCertificado = required_param('id', PARAM_INT);    // Course Module ID
 
/*if (!$cm = get_coursemodule_from_id('certificate', $id)) {
    print_error('Course Module ID was incorrect'); // NOTE this is invalid use of print_error, must be a lang string id
}
if (!$course = $DB->get_record('course', array('id'=> $cm->course))) {
    print_error('course is misconfigured');  // NOTE As above
}
if (!$certificate = $DB->get_record('certificate', array('id'=> $cm->instance))) {
    print_error('course module is incorrect'); // NOTE As above
}*/


$certificate = $DB->get_record('certificate', array('id'=> $idCertificado));
//idcourse
$id =$certificate->id;


$PAGE->navbar->add("Cursos Demo");

$PAGE->set_title(format_string($certificate->name));
$PAGE->set_heading(format_string($course->fullname));
echo $OUTPUT->header();



	//creamos los encabezados de la tabla	
    $table = new html_table();
    $table->classes = array('logtable','generaltable');
    $table->head = array(
        "Código",
        "Nombre",
        "Opción"

    );
    $table->data = array();			

	$result = certificate_get_user_course($id);
	
	//global $CFG;
	foreach ($result as $key => $obj) {
		$row=array();
		$row[]=$obj->username;
		$row[]="<a href='$CFG->wwwroot/user/view.php?id=$obj->id'>".$obj->fullname."</a>";
		//$row[]="<input type='checkbox' class='checkoption' name='checkoption' userid='$obj->userid' courseid='$obj->courseid' >";
		
		if(certificate_get_permission_user($obj->userid,$obj->courseid)){
			
			$row[]="<input type='checkbox' class='checkoption'  userid='$obj->userid' courseid='$obj->courseid' checked>";
		}
		else{
			$row[]="<input type='checkbox' class='checkoption'  userid='$obj->userid' courseid='$obj->courseid' >";
			
		}
		$table->data[] = $row;
	}
echo "<div class='box generalbox boxaligncenter boxwidthnormal'>";
echo html_writer::table($table);
echo "</div>";

$PAGE->requires->js_call_amd('mod_certificate/checkbox','init');

echo $OUTPUT->footer();





