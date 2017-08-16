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

// Get font families.
$fontsans = get_config('certificate', 'fontsans');
$fontserif = get_config('certificate', 'fontserif');

// Add images and lines
certificate_print_image($pdf, $certificate, CERT_IMAGE_BORDER, $brdrx, $brdry, $brdrw, $brdrh);
certificate_draw_frame($pdf, $certificate);
// Set alpha to semi-transparency
//$pdf->SetAlpha(0.2);
certificate_print_image($pdf, $certificate, CERT_IMAGE_WATERMARK, $wmarkx, $wmarky, $wmarkw, $wmarkh);
//$pdf->SetAlpha(1);
certificate_print_image($pdf, $certificate, CERT_IMAGE_SEAL, $sealx, $sealy, '', '');
certificate_print_image($pdf, $certificate, CERT_IMAGE_SIGNATURE, $sigx, $sigy, '', '');
certificate_print_image($pdf, $certificate, CERT_IMAGE_SIGNATURE, 200, $sigy, '', '');
certificate_print_text($pdf, 5, 200, 'L', $fontserif, '', 10, format_string($certificate->nameteacher));
   
// Add text
$pdf->SetTextColor(0, 0, 0);
$pdf->Image('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=SoyUnDios&.png',140,170,20);
//certificate_print_text($pdf, $x, $y + 10, 'C', $fontsans, '', 30, get_string('title', 'certificate'));
certificate_print_text($pdf, $x, $y + 17, 'C', $fontsans, '', 17, "Vicerrectoría Académica<br>Dirección de Nuevas Tecnologías y Educación Virtual");
//certificate_print_text($pdf, $x, $y + 30, 'C', $fontsans, 'B', 20, get_string('certify', 'certificate'));
certificate_print_text($pdf, $x, $y + 35, 'C', $fontsans, 'B', 20, "Certifica que:");
certificate_print_text($pdf, $x, $y + 45, 'C', $fontsans, 'B', 25, fullname($USER));

certificate_print_text($pdf, $x, $y + 70, 'C', $fontsans, 'B', 17, "Entre el ".date('d',format_string($certificate->timestartcourse))." de ".date('M',format_string($certificate->timestartcourse))." al ".date('d',format_string($certificate->timefinalcourse))." de ".date('M',format_string($certificate->timefinalcourse))." de ".date('Y',format_string($certificate->timefinalcourse)));

certificate_print_text($pdf, $x, $y + 80, 'C', $fontsans, 'B', 20, get_string('statement', 'certificate'));
certificate_print_text($pdf, $x, $y + 90, 'C', $fontsans, 'B', 20, format_string($course->fullname));
certificate_print_text($pdf, $x, $y + 115, 'C', $fontsans, 'B', 17,"con una intensidad de ".format_string($certificate->printhours)." horas");
certificate_print_text($pdf, $x, $y + 125, 'C', $fontsans, 'B', 12,"Santiago de Cali - Colombia");

/*
certificate_print_text($pdf, $x, $y + 110, 'C', $fontsans, '', 14,  certificate_get_date($certificate, $certrecord, $course));
certificate_print_text($pdf, $x, $y + 110, 'C', $fontserif, '', 10, certificate_get_grade($certificate, $course));
certificate_print_text($pdf, $x, $y + 110, 'C', $fontserif, '', 10, certificate_get_outcome($certificate, $course));

if ($certificate->printhours) {
    certificate_print_text($pdf, $x, $y + 122, 'C', $fontserif, '', 10, get_string('credithours', 'certificate') . ': ' . $certificate->printhours);
}
*/
certificate_print_text($pdf, 50, 180, 'L', $fontserif, '', 10, format_string($certificate->nameteacher));
certificate_print_text($pdf, 200, 180, 'L', $fontserif, '', 10, "Director Dintev");


certificate_print_text($pdf, $x, $codey, 'C', $fontserif, '', 10, certificate_get_code($certificate, $certrecord));
$i = 0;
if ($certificate->printteacher) {
    $context = context_module::instance($cm->id);
    if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher', '', $sort = 'u.lastname ASC', '', '', '', '', false)) {
        foreach ($teachers as $teacher) {
            $i++;
            certificate_print_text($pdf, $sigx, $sigy + ($i * 4), 'L', $fontserif, '', 12, fullname($teacher));
        }
    }
}

certificate_print_text($pdf, $custx, $custy, 'L', null, null, null, $certificate->customtext);
