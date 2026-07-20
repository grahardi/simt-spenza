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

        // Word sering "memecah" $placeholder jadi beberapa <w:t> terpisah
        // (misal gara-gara ditandai spell-check, autocorrect, atau proses
        // edit/simpan berulang) - contoh nyata: "<w:t> $</w:t>...<w:t>hari</w:t>".
        // Disatukan di sini SEBELUM diganti, dengan cara AMAN: cuma ubah ISI
        // TEKS di dalam tag <w:t> yang sudah ada, TIDAK PERNAH menghapus/
        // menggabung tag <w:r>/<w:t> itu sendiri - supaya XML dijamin tetap valid.

        // 1) Penanda spell-check Word aman dihapus (cuma metadata visual, tidak ada teks di dalamnya)
        $xml = preg_replace('/<w:proofErr[^>]*\/>/', '', $xml);

        // 2) Satukan run yang berakhir dengan "$" dengan run berikutnya kalau
        // berikutnya itu murni huruf dan cuma dipisahkan tag run-boundary biasa
        preg_match_all('/(<w:t\b[^>]*>)([^<]*)(<\/w:t>)/', $xml, $semuaRun, PREG_OFFSET_CAPTURE);
        $isiList = $semuaRun[2]; // [ [teks, offset], ... ]
        $jumlah = count($isiList);
        $penggantian = []; // [start, end, teks_baru]
        $lewati = false;

        for ($i = 0; $i < $jumlah; $i++) {
            if ($lewati) {
                $lewati = false;
                continue;
            }
            [$isi, $offsetIsi] = $isiList[$i];
            if (str_ends_with($isi, '$') && $i + 1 < $jumlah) {
                $akhirTagIni = $offsetIsi + strlen($isi) + strlen('</w:t>');
                $celah = substr($xml, $akhirTagIni, max(0, $isiList[$i + 1][1] - $akhirTagIni));
                $isiNext = $isiList[$i + 1][0];
                if (preg_match('/^<\/w:r><w:r[^>]*>(<w:rPr>.*?<\/w:rPr>)?$/s', $celah) && preg_match('/^[a-zA-Z]+$/', $isiNext)) {
                    $penggantian[] = [$offsetIsi, strlen($isi), $isi.$isiNext];
                    $penggantian[] = [$isiList[$i + 1][1], strlen($isiNext), ''];
                    $lewati = true;
                }
            }
        }

        // Terapkan dari BELAKANG supaya offset tidak bergeser
        usort($penggantian, fn ($a, $b) => $b[0] <=> $a[0]);
        foreach ($penggantian as [$start, $panjang, $isiBaru]) {
            $xml = substr($xml, 0, $start).$isiBaru.substr($xml, $start + $panjang);
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
