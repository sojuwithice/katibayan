<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Storage;

class OcrController extends Controller
{
    public function process(Request $request)
    {
        // Check if this is for KK members only
        $role = $request->input('role', 'kk');
        
        if ($role !== 'kk') {
            return response()->json([
                'success' => false,
                'message' => 'OCR processing is only available for KK members.'
            ], 403);
        }

        // 1. Basic Validation Check
        if (!$request->hasFile('id_file')) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded with the expected key "id_file".'
            ], 400);
        }

        $file = $request->file('id_file');
        $documentText = '';
        $tempPath = null;
        
        // Define binary paths
        $pdftotextBinary = '/opt/homebrew/bin/pdftotext';
        $tesseractBinary = '/opt/homebrew/bin/tesseract'; 
        
        try {
            // Store file temporarily
            $tempPath = $file->store('ocr_temp', 'local'); 
            $fullPath = Storage::path($tempPath);
            
            $ext = strtolower($file->getClientOriginalExtension());
            
            // 2. OCR Processing Logic
            if ($ext === 'pdf') {
                $documentText = (new Pdf($pdftotextBinary))
                                      ->setPdf($fullPath)
                                      ->text();
            } else {
                // Tesseract for Images
                $documentText = (new TesseractOCR($fullPath))
                    ->executable($tesseractBinary)
                    ->lang('eng') // English for now, add 'fil' if needed
                    ->run();
            }

            Log::info('KK Member OCR Raw Text:', ['text' => substr($documentText, 0, 500)]);

            // 3. Parse OCR text specifically for KK member documents
            $extractedData = $this->parseKkDocument($documentText);

            // 4. Return Structured Response
            return response()->json([
                'success' => true,
                'message' => 'OCR processing complete for KK member.',
                'extracted_data' => $extractedData 
            ]);

        } catch (\Spatie\PdfToText\Exceptions\CouldNotExtractText $e) {
            Log::error('PDF Extraction Error: ' . $e->getMessage());
            $message = "Could not extract text from PDF. Ensure the PDF contains selectable text.";
            $statusCode = 422;
        } catch (\Exception $e) {
            Log::error('General OCR Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $message = "OCR processing failed: " . $e->getMessage();
            $statusCode = 500;
        } finally {
            // Clean up the temporary file
            if ($tempPath) {
                Storage::delete($tempPath);
            }
        }
        
        // Return error response if any exception occurred
        return response()->json([
            'success' => false, 
            'message' => $message ?? 'An unknown error occurred during OCR.'
        ], $statusCode ?? 500);
    }

    /**
     * Parse OCR text specifically for KK member documents
     * (Barangay Indigency or Valid ID with Address)
     */
    protected function parseKkDocument(string $text): array
    {
        $cleanText = preg_replace('/\s+/', ' ', trim($text));
        $lines = explode("\n", $text);
        
        $extracted = [
            'lastName' => '',
            'firstName' => '',
            'middleName' => '',
            'birthdate' => '',
            'address' => '',
            'purok' => ''
        ];

        // Common patterns in Philippine documents
        $patterns = [
            'lastName' => '/LAST\s*NAME\s*:?\s*([A-Z][A-Z\s-]+)/i',
            'firstName' => '/FIRST\s*NAME\s*:?\s*([A-Z][A-Z\s-]+)/i',
            'middleName' => '/MIDDLE\s*NAME\s*:?\s*([A-Z][A-Z\s-]+)/i',
            'fullName' => '/NAME\s*:?\s*([A-Z][A-Z\s-.,]+)/i',
            'birthdate' => '/(DATE\s*OF\s*BIRTH|BIRTHDATE)\s*:?\s*(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{4}|\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2})/i',
            'address' => '/(ADDRESS|TIRAHAN|RESIDENCE)\s*:?\s*([^,]+,[^,]+,[^,]+)/i',
            'purok' => '/(PUROK|ZONE|SITIO)\s*:?\s*([A-Z0-9\s-]+)/i'
        ];

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Try to extract last name
            if (empty($extracted['lastName']) && preg_match($patterns['lastName'], $line, $matches)) {
                $extracted['lastName'] = trim($matches[1]);
            }
            
            // Try to extract first name
            if (empty($extracted['firstName']) && preg_match($patterns['firstName'], $line, $matches)) {
                $extracted['firstName'] = trim($matches[1]);
            }
            
            // Try full name if individual names not found
            if ((empty($extracted['lastName']) || empty($extracted['firstName'])) && 
                preg_match($patterns['fullName'], $line, $matches)) {
                $fullName = trim($matches[1]);
                $nameParts = explode(',', $fullName);
                if (count($nameParts) >= 2) {
                    $extracted['lastName'] = trim($nameParts[0]);
                    $firstPart = trim($nameParts[1]);
                    $firstParts = explode(' ', $firstPart);
                    $extracted['firstName'] = $firstParts[0] ?? '';
                    $extracted['middleName'] = $firstParts[1] ?? '';
                }
            }
            
            // Birthdate extraction and formatting
            if (empty($extracted['birthdate']) && preg_match($patterns['birthdate'], $line, $matches)) {
                $dateStr = trim($matches[2]);
                // Try to convert to YYYY-MM-DD format
                try {
                    $date = \DateTime::createFromFormat('m/d/Y', $dateStr) ?: 
                            \DateTime::createFromFormat('Y-m-d', $dateStr) ?:
                            \DateTime::createFromFormat('d/m/Y', $dateStr);
                    if ($date) {
                        $extracted['birthdate'] = $date->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to parse date: ' . $dateStr);
                }
            }
            
            // Address extraction
            if (empty($extracted['address']) && preg_match($patterns['address'], $line, $matches)) {
                $extracted['address'] = trim($matches[2]);
            }
            
            // Purok extraction
            if (empty($extracted['purok']) && preg_match($patterns['purok'], $line, $matches)) {
                $extracted['purok'] = trim($matches[2]);
            }
        }

        // Fallback: If no structured data found, return first few lines as address
        if (empty($extracted['address']) && count($lines) > 2) {
            $extracted['address'] = trim($lines[0] . ', ' . $lines[1]);
        }

        return $extracted;
    }
}