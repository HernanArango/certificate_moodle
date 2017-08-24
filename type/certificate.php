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
 * A4_embedded certificate type
 *
 * @package    mod_certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$pdf = new PDF($certificate->orientation, 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetTitle($certificate->name);
$pdf->SetProtection(array('modify'));
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(false, 0);
$pdf->AddPage();

// Define variables
// Landscape
if ($certificate->orientation == 'L') {
    $x = 10;
    $y = 30;
    $sealx = 230;
    $sealy = 150;
    $sigx = 50;
    $sigy = 175;
    $custx = 47;
    $custy = 155;
    $wmarkx = 135;
    $wmarky = 20;
    $wmarkw = 18;
    $wmarkh = 25;
    $brdrx = 0;
    $brdry = 0;
    $brdrw = 297;
    $brdrh = 210;
    $codey = 175;
} else { // Portrait
    $x = 10;
    $y = 40;
    $sealx = 150;
    $sealy = 220;
    $sigx = 30;
    $sigy = 230;
    $custx = 30;
    $custy = 230;
    $wmarkx = 26;
    $wmarky = 58;
    $wmarkw = 158;
    $wmarkh = 170;
    $brdrx = 0;
    $brdry = 0;
    $brdrw = 210;
    $brdrh = 297;
    $codey = 250;
}
$pdf->SetTextColor(1,1,1);
// Get font families.
$fontsans = get_config('certificate', 'fontsans');
$fontserif = get_config('certificate', 'fontserif');

// Add images and lines
certificate_print_image($pdf, $certificate, CERT_IMAGE_BORDER, $brdrx, $brdry, $brdrw, $brdrh);
certificate_draw_frame($pdf, $certificate);

//Load UV Icon
certificate_print_image($pdf, $certificate, CERT_IMAGE_WATERMARK, $wmarkx, $wmarky, $wmarkw, $wmarkh);

//Print Tittle/Certification to stutend
certificate_print_text($pdf, $x, $y + 17, 'C', $fontsans, '', 17, "Vicerrectoría Académica<br>Dirección de Nuevas Tecnologías y Educación Virtual");
certificate_print_text($pdf, $x, $y + 35, 'C', $fontsans, 'B', 20, "Certifica que:");

//Print User name
$pdf->SetTextColor(0,5,82);
certificate_print_text($pdf, $x, $y + 45, 'C', $fontsans, 'B', 25, format_string($USER->firstname)." ".format_string($USER->lastname));
$pdf->SetTextColor(1,1,1);
//Print Date
setlocale(LC_TIME,"es_ES");
certificate_print_text($pdf, $x, $y + 70, 'C', $fontsans, 'B', 17, "Entre el ".date('d',format_string($certificate->timestartcourse))." de ".date('M',format_string($certificate->timestartcourse))." al ".date('d',format_string($certificate->timefinalcourse))." de ".date('M',format_string($certificate->timefinalcourse))." de ".date('Y',format_string($certificate->timefinalcourse)));

//Print Certification to course/name course
certificate_print_text($pdf, $x, $y + 80, 'C', $fontsans, 'B', 20, get_string('statement', 'certificate'));
certificate_print_text($pdf, $x, $y + 90, 'C', $fontsans, 'B', 20, format_string($course->fullname));

//Print hours per course/City
certificate_print_text($pdf, $x, $y + 115, 'C', $fontsans, 'B', 17,"con una intensidad de ".format_string($certificate->printhours)." horas");
certificate_print_text($pdf, $x, $y + 125, 'C', $fontsans, 'B', 12,"Santiago de Cali - Colombia");

//Print Student Name
certificate_print_text($pdf, 5, 200, 'L', $fontserif, '', 10, format_string($certificate->nameteacher));

//Load image and name teacher/tutor
$path_tutor="$CFG->dirroot/mod/certificate/pix/signatures/".$certificate->idteacher.".png";
$pdf->Image($path_tutor,$sigx,$sigy-5,50);
certificate_print_text($pdf, 50, 180, 'L', $fontserif, '', 10, certificate_get_teacher_signature(format_string($certificate->idteacher))."<br>Tutor");

//Load image and name Director
//Firma Director
$path_director="$CFG->dirroot/mod/certificate/pix/signatures/directora.png";
$pdf->Image($path_director,200,$sigy-5,50);
certificate_print_text($pdf, 200, 180, 'L', $fontserif, '', 10, " Gloria Isabel Toro <br>Directora -DINTEV-");

//Load QR Code
//$pdf->Image('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.format_string($USER->firstname)."-".format_string($course->fullname).'&.png',140,170,20);