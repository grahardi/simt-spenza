<?php

namespace App\Services;

use ZipArchive;

/**
 * Isi placeholder $namaguru, $nip, dst LANGSUNG ke XML dalam file .docx asli
 * (bukan bikin ulang tampilan pakai HTML/DomPDF) - supaya hasilnya identik
 * 100% dengan format Word aslinya. Cuma pakai PHP ZipArchive bawaan, tidak
 * perlu LibreOffice/PHPWord di server.
 */
class DocxMergeService
{
    /**
     * @param string $templatePath Path lengkap ke file .docx template (placeholder $xxx)
     * @param array<string,string> $data ['namaguru' => 'Budi', 'nip' => '123', ...] - TANPA tanda $
     * @param string $outputPath Path lengkap tujuan file hasil
     */
    public static function isi(string $templatePath, array $data, string $outputPath): bool
    {
        copy($templatePath, $outputPath);

        $zip = new ZipArchive();
        if ($zip->open($outputPath) !== true) {
            return false;
        }

        $xml = $zip->getFromName('word/document.xml');
        if ($xml === false) {
            $zip->close();
            return false;
        }

        // Urutkan dari NAMA TERPANJANG dulu - penting supaya "$tanggal" tidak
        // "memakan" sebagian "$tanggalselesai"/"$tanggalsurat" sebelum sempat
        // diganti utuh (str_replace bisa salah tangkap kalau urutannya kebalik).
        uksort($data, fn ($a, $b) => strlen($b) - strlen($a));

        foreach ($data as $kunci => $nilai) {
            $aman = htmlspecialchars((string) $nilai, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $xml = str_replace('$'.$kunci, $aman, $xml);
        }

        $zip->addFromString('word/document.xml', $xml);
        $zip->close();

        return true;
    }
}
