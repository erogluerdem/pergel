<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use SplFileObject;

class DataParser
{
    private array $errors = [];
    private array $data = [];

    /**
     * Parse Excel dosyasını (XLSX)
     */
    public function parseExcel(string $filePath): Collection
    {
        try {
            if (!file_exists($filePath)) {
                throw new Exception("Dosya bulunamadı: {$filePath}");
            }

            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            
            if ($ext === 'xlsx') {
                return $this->parseXLSX($filePath);
            } elseif ($ext === 'csv') {
                return $this->parseCSV($filePath);
            } else {
                throw new Exception("Desteklenmeyen dosya formatı: {$ext}");
            }
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return collect();
        }
    }

    /**
     * XLSX dosyasını parse et
     */
    private function parseXLSX(string $filePath): Collection
    {
        try {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = collect();
            $headers = null;
            $rowNum = 0;

            foreach ($worksheet->getRowIterator() as $row) {
                $rowNum++;
                $cellIterator = $row->getCellIterator();
                $rowData = [];

                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                // Boş satırları atla
                if (empty(array_filter($rowData))) {
                    continue;
                }

                // Header satırı
                if ($headers === null) {
                    $headers = array_map('trim', $rowData);
                    continue;
                }

                // Sütun sayısını kontrol et
                if (count($rowData) !== count($headers)) {
                    $this->errors[] = "Satır {$rowNum}: Sütun sayısı eşleşmiyor";
                    continue;
                }

                $data = array_combine($headers, array_map('trim', (array)$rowData));
                $rows->push($data);
            }

            return $rows;
        } catch (Exception $e) {
            $this->errors[] = "XLSX Parse Error: " . $e->getMessage();
            return collect();
        }
    }

    /**
     * CSV dosyasını parse et
     */
    public function parseCSV(string $filePath): Collection
    {
        try {
            $rows = collect();
            $file = new SplFileObject($filePath, 'r');
            $file->setFlags(SplFileObject::READ_CSV);

            $headers = null;
            $rowNum = 0;

            foreach ($file as $row) {
                $rowNum++;

                if ($row === [null] || empty(array_filter($row))) {
                    continue;
                }

                if ($headers === null) {
                    $headers = array_map('trim', $row);
                    continue;
                }

                if (count($row) !== count($headers)) {
                    $this->errors[] = "Satır {$rowNum}: Sütun sayısı eşleşmiyor";
                    continue;
                }

                $data = array_combine($headers, array_map('trim', $row));
                $rows->push($data);
            }

            return $rows;
        } catch (Exception $e) {
            $this->errors[] = "CSV Parse Error: " . $e->getMessage();
            return collect();
        }
    }

    /**
     * JSON dosyasını parse et
     */
    public function parseJSON(string $filePath): Collection
    {
        try {
            if (!file_exists($filePath)) {
                throw new Exception("Dosya bulunamadı: {$filePath}");
            }

            $json = file_get_contents($filePath);
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("JSON Parse Error: " . json_last_error_msg());
            }

            if (!is_array($data)) {
                throw new Exception("JSON geçerli bir dizi değil");
            }

            return collect($data);
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return collect();
        }
    }

    /**
     * Verileri Excel formatında dışa aktar
     */
    public function exportToCSV(Collection $data, string $fileName): string
    {
        try {
            if ($data->isEmpty()) {
                throw new Exception("Dışa aktarılacak veri bulunamadı");
            }

            $path = storage_path("app/exports/{$fileName}.csv");
            $dir = dirname($path);

            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $file = fopen($path, 'w');

            // Headers
            $headers = array_keys($data->first());
            fputcsv($file, $headers);

            // Data
            foreach ($data as $row) {
                fputcsv($file, array_values($row));
            }

            fclose($file);
            return $path;
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return '';
        }
    }

    /**
     * Doğrulama kurallarını uygula
     */
    public function validate(Collection $data, array $rules): array
    {
        $validated = [];
        $errors = [];

        foreach ($data as $idx => $row) {
            $rowNum = $idx + 1;
            $rowErrors = [];

            foreach ($rules as $field => $rule) {
                $value = $row[$field] ?? null;

                if (strpos($rule, 'required') !== false && empty($value)) {
                    $rowErrors[] = "{$field}: Zorunlu alan";
                }

                if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $rowErrors[] = "{$field}: Geçersiz email";
                }

                if (strpos($rule, 'numeric') !== false && !is_numeric($value)) {
                    $rowErrors[] = "{$field}: Sayı değeri bekleniyor";
                }
            }

            if (empty($rowErrors)) {
                $validated[] = $row;
            } else {
                $errors["Satır {$rowNum}"] = $rowErrors;
            }
        }

        return [
            'valid' => collect($validated),
            'errors' => $errors,
            'error_count' => count($errors),
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
