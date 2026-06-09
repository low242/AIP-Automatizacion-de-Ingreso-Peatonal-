<?php

namespace App\Support;

use DateTimeInterface;

class SimpleXlsx
{
    public static function fromRows(array $headers, iterable $rows, string $sheetName = 'Registros'): string
    {
        $sheetRows = [array_values($headers)];

        foreach ($rows as $row) {
            $sheetRows[] = array_values($row);
        }

        return self::zip([
            '[Content_Types].xml' => self::contentTypes(),
            '_rels/.rels' => self::rootRels(),
            'docProps/app.xml' => self::appProps(),
            'docProps/core.xml' => self::coreProps(),
            'xl/workbook.xml' => self::workbook($sheetName),
            'xl/_rels/workbook.xml.rels' => self::workbookRels(),
            'xl/styles.xml' => self::styles(),
            'xl/worksheets/sheet1.xml' => self::sheet($sheetRows),
        ]);
    }

    private static function sheet(array $rows): string
    {
        $rowCount = max(1, count($rows));
        $columnCount = max(1, count($rows[0] ?? []));
        $lastCell = self::columnName($columnCount) . $rowCount;

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"';
        $xml .= ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
        $xml .= '<dimension ref="A1:' . $lastCell . '"/>';
        $xml .= '<sheetViews><sheetView workbookViewId="0"/></sheetViews>';
        $xml .= '<sheetFormatPr defaultRowHeight="15"/>';
        $xml .= self::columns($columnCount);
        $xml .= '<sheetData>';

        foreach ($rows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 1;
            $xml .= '<row r="' . $rowNumber . '">';

            for ($columnIndex = 1; $columnIndex <= $columnCount; $columnIndex++) {
                $cell = self::columnName($columnIndex) . $rowNumber;
                $value = $row[$columnIndex - 1] ?? '';
                $style = $rowNumber === 1 ? ' s="1"' : '';

                if ($value === null || $value === '') {
                    $xml .= '<c r="' . $cell . '"' . $style . '/>';
                    continue;
                }

                if ($value instanceof DateTimeInterface) {
                    $value = $value->format('Y-m-d H:i:s');
                }

                $xml .= '<c r="' . $cell . '" t="inlineStr"' . $style . '>';
                $xml .= '<is><t>' . self::escape((string) $value) . '</t></is>';
                $xml .= '</c>';
            }

            $xml .= '</row>';
        }

        $xml .= '</sheetData>';
        $xml .= '<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/>';
        $xml .= '</worksheet>';

        return $xml;
    }

    private static function columns(int $columnCount): string
    {
        $xml = '<cols>';

        for ($column = 1; $column <= $columnCount; $column++) {
            $width = $column <= 3 ? 16 : 24;
            $xml .= '<col min="' . $column . '" max="' . $column . '" width="' . $width . '" customWidth="1"/>';
        }

        return $xml . '</cols>';
    }

    private static function contentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            . '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '</Types>';
    }

    private static function rootRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'
            . '</Relationships>';
    }

    private static function workbook(string $sheetName): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            . ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="' . self::escape($sheetName) . '" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private static function workbookRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private static function styles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="1"><fill><patternFill patternType="none"/></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/></cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    private static function appProps(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties"'
            . ' xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            . '<Application>AIP</Application>'
            . '</Properties>';
    }

    private static function coreProps(): string
    {
        $createdAt = gmdate('Y-m-d\TH:i:s\Z');

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties"'
            . ' xmlns:dc="http://purl.org/dc/elements/1.1/"'
            . ' xmlns:dcterms="http://purl.org/dc/terms/"'
            . ' xmlns:dcmitype="http://purl.org/dc/dcmitype/"'
            . ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            . '<dc:creator>AIP</dc:creator>'
            . '<cp:lastModifiedBy>AIP</cp:lastModifiedBy>'
            . '<dcterms:created xsi:type="dcterms:W3CDTF">' . $createdAt . '</dcterms:created>'
            . '<dcterms:modified xsi:type="dcterms:W3CDTF">' . $createdAt . '</dcterms:modified>'
            . '</cp:coreProperties>';
    }

    private static function zip(array $files): string
    {
        $local = '';
        $central = '';
        $offset = 0;
        $dosTime = self::dosTime();
        $dosDate = self::dosDate();

        foreach ($files as $name => $content) {
            $name = str_replace('\\', '/', $name);
            $crc = hexdec(hash('crc32b', $content));
            $size = strlen($content);
            $nameLength = strlen($name);

            $localHeader = pack('VvvvvvVVVvv', 0x04034b50, 20, 0, 0, $dosTime, $dosDate, $crc, $size, $size, $nameLength, 0);
            $local .= $localHeader . $name . $content;

            $centralHeader = pack('VvvvvvvVVVvvvvvVV', 0x02014b50, 20, 20, 0, 0, $dosTime, $dosDate, $crc, $size, $size, $nameLength, 0, 0, 0, 0, 0, $offset);
            $central .= $centralHeader . $name;

            $offset += strlen($localHeader) + $nameLength + $size;
        }

        $centralSize = strlen($central);
        $centralOffset = strlen($local);
        $end = pack('VvvvvVVv', 0x06054b50, 0, 0, count($files), count($files), $centralSize, $centralOffset, 0);

        return $local . $central . $end;
    }

    private static function columnName(int $column): string
    {
        $name = '';

        while ($column > 0) {
            $column--;
            $name = chr(65 + ($column % 26)) . $name;
            $column = intdiv($column, 26);
        }

        return $name;
    }

    private static function dosTime(): int
    {
        return ((int) date('H') << 11) | ((int) date('i') << 5) | ((int) date('s') >> 1);
    }

    private static function dosDate(): int
    {
        return (((int) date('Y') - 1980) << 9) | ((int) date('m') << 5) | (int) date('d');
    }

    private static function escape(string $value): string
    {
        $value = preg_replace('/[^\P{C}\t\n\r]/u', '', $value) ?? $value;

        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT | ENT_SUBSTITUTE, 'UTF-8');
    }
}
