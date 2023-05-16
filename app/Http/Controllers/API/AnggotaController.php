<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AnggotaModel;
use App\Models\TemplateModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use TCPDF;
use Illuminate\Support\Str;


class AnggotaController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input dari form
        $validation = Validator::make($request->all(), [
            'nama' => 'required',
            'template_id' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 401,
                'message' => 'check your validation',
                'errors' => $validation->errors()
            ]);
        }

        try {
            // Buat objek anggota baru
            $anggota = new AnggotaModel;
            $anggota->template_id = $request->input('template_id');
            $anggota->uuid = Uuid::uuid4()->toString();
            $anggota->nama = $request->input('nama');
            $anggota->save();

            // Mengambil template gambar dari model TemplateModel
            $template = TemplateModel::find($anggota->template_id);

            // Simpan nama file kartu anggota
            $filename = 'kartu_anggota_' . Str::slug($anggota->nama) . '.pdf';
            $filePath = public_path('uploads/anggota/') . $filename;

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // Add a page
            $pdf->AddPage();
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);

            // Set template image
            $imagePath = public_path('uploads/template/') . $template->image_template;
            $pdf->Image($imagePath, 10, 10, 0, 0, '', '', '', false, 300, '', false, false, 0, false, false, false);

            // Set font
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetXY(43, 90);
            $pdf->Cell(0, 0, $anggota->nama, 0, 0, 'L', false, '', 0, false, 'T', 'C');

            $pdf->Output($filePath, 'F');
            $anggota->image_kartu_anggota = $filename;
            $anggota->save();
        } catch (\Throwable $th) {
            return response()->json([
                'errors' => $th->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'Data anggota berhasil disimpan dan kartu anggota telah dibuat',
            'pdf_url' => url('uploads/anggota/' . $filename)
        ]);
    }



    public function getPdfUrl($id)
    {
        $anggota = AnggotaModel::find($id);

        if (!$anggota) {
            return response()->json(['message' => 'Anggota not found'], 404);
        }

        $pdfUrl = url('uploads/anggota/' . $anggota->image_kartu_anggota);

        return response()->json(['pdf_url' => $pdfUrl]);
    }
}
