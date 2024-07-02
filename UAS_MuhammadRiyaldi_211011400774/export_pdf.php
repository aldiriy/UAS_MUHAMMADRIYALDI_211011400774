<?php
require 'db.php';
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Klasemen UEFA 2024 - Grup A', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function LoadData()
    {
        global $conn;
        $sql = "SELECT groups.name as group_name, countries.name as country_name, wins, draws, losses, points 
                FROM countries 
                JOIN groups ON countries.group_id = groups.id
                WHERE groups.name = 'Grup A'"; // Filter hanya grup A
        return $conn->query($sql);
    }

    function FancyTable($header, $data)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(30, 7, $header[0], 1);
        $this->Cell(40, 7, $header[1], 1);
        $this->Cell(20, 7, $header[2], 1);
        $this->Cell(20, 7, $header[3], 1);
        $this->Cell(20, 7, $header[4], 1);
        $this->Cell(20, 7, $header[5], 1);
        $this->Ln();

        $this->SetFont('Arial', '', 12);
        while ($row = $data->fetch_assoc()) {
            $this->Cell(30, 6, $row['group_name'], 1);
            $this->Cell(40, 6, $row['country_name'], 1);
            $this->Cell(20, 6, $row['wins'], 1);
            $this->Cell(20, 6, $row['draws'], 1);
            $this->Cell(20, 6, $row['losses'], 1);
            $this->Cell(20, 6, $row['points'], 1);
            $this->Ln();
        }
    }
}

$pdf = new PDF();
$header = array('Group', 'Nama Negara', 'Menang', 'Seri', 'Kalah', 'Poin');
$data = $pdf->LoadData();
$pdf->SetFont('Arial', '', 14);
$pdf->AddPage();
$pdf->FancyTable($header, $data);
$pdf->Output();
?>
