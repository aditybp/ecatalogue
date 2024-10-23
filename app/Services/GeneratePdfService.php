<?php

namespace App\Services;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\DataVendor;
use Illuminate\Validation\Rules\Exists;

class GeneratePdfService
{
    public function generatePdfMaterialNatural($id)
    {
        $dataVendor = $this->getVendorById($id);

        if (!isset($dataVendor)) {
            throw new \Exception('data not found');
        }

        $provinsi = $dataVendor->provinces->nama_provinsi;
        $kabupaten = $dataVendor->cities->nama_kota;
        $namaResponden = $dataVendor['nama_vendor'];
        $alamat = $dataVendor['alamat'];
        $geoTagging = $dataVendor['koordinat'];
        $telepon = $dataVendor['no_telepon'];
        $email = '-';
        $kategoriResponden = '-';
        $idProvinsi = $dataVendor->cities->provinsi_id;
        $idKabupatenKota = $dataVendor->cities->kode_kota;

        $templatePath = resource_path('views/pdf/template_material_natural.jpg');

        if (!file_exists($templatePath)) {
            throw new \Exception('Template not found');
        }

        $pdf = new Fpdf();
        $pdf->AddPage('L');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Image($templatePath, 0, 0, 297, 210);

        //provinsi
        $pdf->SetXY(83, 22);
        $pdf->Cell(40, 100, $provinsi);

        //id provinsi
        $pdf->SetXY(180, 22);
        $pdf->Cell(40, 100, $idProvinsi);

        //id kabupaten kota
        $pdf->SetXY(180, 27);
        $pdf->Cell(40, 100, $idKabupatenKota);

        //kota/kabupaten
        $pdf->SetXY(83, 27);
        $pdf->Cell(40, 100, $kabupaten);

        //nama responden
        $pdf->SetXY(83, 32);
        $pdf->Cell(40, 100, $namaResponden);

        //alamat responden
        $pdf->SetXY(83, 37);
        $pdf->Cell(40, 100, $alamat);

        //tagging responden
        $pdf->SetXY(153, 37);
        $pdf->Cell(40, 100, $geoTagging);

        //telepon responden
        $pdf->SetXY(83, 42);
        $pdf->Cell(40, 100, $telepon);

        //telepon responden
        $pdf->SetXY(153, 42);
        $pdf->Cell(40, 100, $email);

        //kategori responden
        $pdf->SetXY(83, 48);
        $pdf->Cell(40, 100, $kategoriResponden);

        $fileName = 'generated_pdf_'. time() .'.pdf';
        $filePath = public_path('kuisioner/'.$fileName);

        $pdf->Output($filePath, 'F');

        $pdfUrl = url('kuisioner/'. $fileName);
        return $pdfUrl;
    }

    private function getVendorById($id)
    {
        return DataVendor::with(['provinces', 'cities'])->find($id);
    }

}
