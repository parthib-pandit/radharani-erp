<?php
namespace App\Support;

use OpenSpout\Reader\XLSX\Reader as XlsxReader;

// Reads the first sheet of a .csv or .xlsx upload into [headers, rows].
// OpenSpout streams the file, so large sheets don't blow the memory limit on shared hosting.
class SpreadsheetReader
{
    /** @return array{0: array<int, string>, 1: array<int, array<int, string>>} */
    public static function read(string $path, string $extension, int $limit = 1000): array
    {
        $rows = strtolower($extension) === 'xlsx'
            ? self::readXlsx($path, $limit + 1)
            : self::readCsv($path, $limit + 1);

        $rows = array_values(array_filter($rows, fn ($r) => count(array_filter($r, fn ($c) => trim((string) $c) !== '')) > 0));

        $headers = array_map(fn ($h) => trim((string) $h), array_shift($rows) ?? []);

        // Pad/trim every row to the header width so column indexes line up.
        $width = count($headers);
        $rows = array_map(fn ($r) => array_slice(array_pad(array_map(fn ($c) => trim((string) $c), $r), $width, ''), 0, $width), $rows);

        return [$headers, $rows];
    }

    private static function readCsv(string $path, int $limit): array
    {
        $rows = [];
        $handle = fopen($path, 'r');

        // Strip a UTF-8 BOM (Excel adds one when saving CSV).
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false && count($rows) < $limit) {
            $rows[] = $row;
        }
        fclose($handle);

        return $rows;
    }

    private static function readXlsx(string $path, int $limit): array
    {
        $rows = [];
        $reader = new XlsxReader();
        $reader->open($path);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rows[] = array_map(function ($cell) {
                    $value = $cell->getValue();
                    if ($value instanceof \DateTimeInterface) {
                        return $value->format('Y-m-d');
                    }
                    // Excel stores 4.2 as 4.2000000000000002; keep what staff typed.
                    return is_float($value) ? rtrim(rtrim(number_format($value, 6, '.', ''), '0'), '.') : (string) $value;
                }, $row->getCells());

                if (count($rows) >= $limit) {
                    break;
                }
            }
            break; // first sheet only
        }

        $reader->close();

        return $rows;
    }
}
