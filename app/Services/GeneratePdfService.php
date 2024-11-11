<?php

namespace App\Services;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\DataVendor;
use App\Models\KuisionerPdfData;
use App\Models\Material;
use App\Models\Peralatan;
use App\Models\ShortlistVendor;
use App\Models\TenagaKerja;
use Illuminate\Validation\Rules\Exists;
use setasign\Fpdi\Fpdi;

class GeneratePdfService
{
    public function generatePdfMaterial($data)
    {
        $dataVendor = $this->getVendorById($data['vendor_id']);

        if (!isset($dataVendor)) {
            throw new \Exception('data not found');
        }

        if ($data['material_id']) {
            $pdfMaterial = $this->pdfMaterial($dataVendor, json_decode($data['material_id']));
        }
        if ($data['peralatan_id']) {
            $pdfPeralatan = $this->pdfPeralatan($dataVendor, json_decode($data['peralatan_id']));
        }
        if ($data['tenaga_kerja_id']) {
            $pdfTenagaKerja = $this->pdfTenagaKerja($dataVendor, json_decode($data['tenaga_kerja_id']));
        }
        //dd($pdfTenagaKerja);

        return $pdfTenagaKerja;
    }

    private function getVendorById($id)
    {
        return DataVendor::with(['provinces', 'cities'])->find($id);
    }

    private function getIdentifikasi($id, $category)
    {
        if ($category == 'material') {
            $query = Material::select('id', 'nama_material', 'satuan', 'spesifikasi', 'merk')->find($id)->all();
        } elseif ($category == 'peralatan') {
            $query = Peralatan::select('id', 'nama_peralatan', 'satuan', 'spesifikasi', 'merk')->find($id)->all();
        } elseif ($category == 'tenaga_kerja') {
            $query = TenagaKerja::select('id', 'jenis_tenaga_kerja', 'satuan', 'kodefikasi')->find($id)->all();
        } else {
            return null;
        }

        return $query;
    }

    private function pdfMaterial($dataVendor, $id)
    {

        $identifikasiKebutuhan = $this->getIdentifikasi($id, 'material');

        $templatePath = resource_path('views/pdf/template_material_natural.jpg');
        $templateIdentifikasiPath = resource_path('views/pdf/template_material_natural_identifikasi.jpg');

        if (!file_exists($templatePath) || !file_exists($templateIdentifikasiPath)) {
            throw new \Exception('Template not found');
        }

        $pdfInformasiUmum = $this->materialPdfInformasiUmum($templatePath, $dataVendor);
        $pdfIdentifikasi = $this->materialPdfIdentifikasi($templateIdentifikasiPath, $identifikasiKebutuhan);


        return $pdfIdentifikasi;
        // return ('kuisioner/' . $fileName);
    }

    private function materialPdfIdentifikasi($templatePath, $data)
    {
        if (!is_array($data) || empty($data)) {
            throw new \Exception('Data is not an array or is empty.');
        }

        $count = 1;
        $Y = 36;
        $pdf = new Fpdf();
        $pdf->AddPage('L');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Image($templatePath, 0, 0, 297, 210);

        foreach ($data as $value) {

            //no
            $pdf->SetXY(30, $Y);
            $pdf->Cell(5, 8, $count, 0, 0, 'C');

            //nama
            $pdf->SetXY(43, $Y);
            //$pdf->MultiCell(21, 4, $value['nama_material'], 1, 'L');
            $pdf->MultiCell(37, 4, 'eksavator PC-200', 0, 'L');

            //spesifikasi
            $pdf->SetXY(82, $Y);
            //$pdf->MultiCell(22, 4, $value['spesifikasi'], 1, 'L');
            $pdf->MultiCell(35, 4, 'kapasitas bucket 0,8 m3', 0, 'L');

            //satuan
            $pdf->SetXY(119, $Y);
            //$pdf->MultiCell(10, 4, $value['satuan'], 1, 'L');
            $pdf->MultiCell(18, 4, '1 jam', 0, 'L');

            $Y += 9.5;

            if ($count % 16 == 0) {
                $pdf->Output();

                $pdf->AddPage('L');
                $pdf->SetFont('Arial', 'B', 6);
                $pdf->Image($templatePath, 0, 0, 297, 210);
                $Y = 60;
            }

            $count++;
        }

        return $pdf->Output('I', 'material_identifikasi.pdf');
    }

    private function mergePdf($pdfFiles)
    {
        $pdf = new Fpdi();
        foreach ($pdfFiles as $file) {
            $pageCount = $pdf->setSourceFile($file);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($templateId);
            }
            unlink($file);
        }
        $pdf->Output('I', 'merged.pdf');
    }

    private function materialPdfInformasiUmum($templatePath, $dataVendor)
    {
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

        $pdf = new Fpdf();
        $pdf->AddPage('L');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Image($templatePath, 0, 0, 297, 210);

        //provinsi
        $pdf->SetXY(110, 22);
        $pdf->Cell(40, 100, $provinsi);

        //id provinsi
        $pdf->SetXY(246, 69);
        $pdf->Cell(24, 5, $idProvinsi, 0, 0, 'L');

        //id kabupaten kota
        $pdf->SetXY(246, 75);
        $pdf->Cell(24, 5, $idKabupatenKota, 0, 0, 'L');

        //kota/kabupaten
        $pdf->SetXY(110, 29);
        $pdf->Cell(40, 100, $kabupaten);

        //nama responden
        $pdf->SetXY(110, 35);
        $pdf->Cell(40, 100, $namaResponden);

        //alamat responden
        $pdf->SetXY(110, 41);
        $pdf->Cell(40, 100, $alamat);

        //tagging responden
        $pdf->SetXY(223, 41);
        $pdf->Cell(40, 100, $geoTagging, 0, 0, 'L');

        //telepon responden
        $pdf->SetXY(110, 47);
        $pdf->Cell(40, 100, $telepon);

        //telepon responden
        $pdf->SetXY(223, 47);
        $pdf->Cell(40, 100, $email, 0, 0, 'L');

        //kategori responden
        $pdf->SetXY(110, 53);
        $pdf->Cell(40, 100, $kategoriResponden);

        // $fileName = 'generated_pdf_' . time() . '.pdf';
        // $filePath = public_path('kuisioner/' . $fileName);

        // $pdf->Output($filePath, 'F');

        return $pdf->Output('I', 'material_identifikasi.pdf');
    }

    private function pdfPeralatan($id, $dataVendor)
    {
        $identifikasiKebutuhan = $this->getIdentifikasi($id, 'peralatan');

        $templatePath = resource_path('views/pdf/template_peralatan.jpg');
        $templateIdentifikasiPath = resource_path('views/pdf/template_peralatan_identifikasi.jpg');

        if (!file_exists($templatePath) || !file_exists($templateIdentifikasiPath)) {
            throw new \Exception('Template not found');
        }

        $pdfInformasiUmum = $this->materialPdfInformasiUmum($templatePath, $dataVendor);
        //$pdfIdentifikasi = $this->materialPdfIdentifikasi($templateIdentifikasiPath, $identifikasiKebutuhan);


        return $pdfInformasiUmum;
    }

    private function pdfTenagaKerja($dataVendor, $id)
    {
        $identifikasiKebutuhan = $this->getIdentifikasi($id, 'tenaga_kerja');

        $templatePath = resource_path('views/pdf/template_tenaga_kerja.jpg');
        $templateIdentifikasiPath = resource_path('views/pdf/template_peralatan_identifikasi.jpg');

        if (!file_exists($templatePath) || !file_exists($templateIdentifikasiPath)) {
            throw new \Exception('Template not found');
        }

        //$pdfInformasiUmum = $this->materialPdfInformasiUmum($templatePath, $dataVendor);
        $pdfIdentifikasi = $this->materialPdfIdentifikasi($templateIdentifikasiPath, $identifikasiKebutuhan);


        return $pdfIdentifikasi;
    }

    private function tenagaKerjaPdfIdentifikasi($templatePath, $data)
    {
        if (!is_array($data) || empty($data)) {
            throw new \Exception('Data is not an array or is empty.');
        }

        $count = 1;
        $Y = 60;
        $pdf = new Fpdf();
        $pdf->AddPage('L');
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Image($templatePath, 0, 0, 297, 210);

        foreach ($data as $value) {

            $pdf->SetXY(30, $Y);
            $pdf->Cell(5, 5, $count, 0, 0, 'C');

            $pdf->SetXY(43, $Y);
            $pdf->MultiCell(60, 4, $value['jenis_tenaga_kerja'], 0, 'L');

            $pdf->SetXY(107, $Y);
            $pdf->MultiCell(22, 4, $value['satuan'], 0, 'L');

            $Y += 11;

            if ($count % 12 == 0) {
                $pdf->Output();

                $pdf->AddPage('L');
                $pdf->SetFont('Arial', 'B', 6);
                $pdf->Image($templatePath, 0, 0, 297, 210);
                $Y = 60;
            }

            $count++;
        }

        return $pdf->Output('I', 'material_identifikasi.pdf');
    }
}
