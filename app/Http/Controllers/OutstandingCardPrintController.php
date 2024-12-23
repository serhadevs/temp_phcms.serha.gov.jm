<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use ZipArchive;

class OutstandingCardPrintController extends Controller
{
    public function index(){
        // Retrieve data from the table
        $data = User::all();

        // Define the file paths
        $filePath = storage_path('app/public/data.txt');
        $zipPath = storage_path('app/public/data.zip');

        // Open the file for writing
        $file = fopen($filePath, 'w');

        // Write data to the file
        foreach ($data as $item) {
            fwrite($file, $item->toJson() . PHP_EOL);
        }

        // Close the file
        fclose($file);

        // Create a zip archive
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($filePath, 'data.txt');
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create zip file'], 500);
        }

        // Delete the original text file
        unlink($filePath);

        return response()->download($zipPath);
    }
}
