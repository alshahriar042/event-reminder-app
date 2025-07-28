<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportEventRequest;
use App\Services\EventImportService;

class ImportEventController extends Controller
{
    protected $importService;

    public function __construct(EventImportService $importService)
    {
        $this->importService = $importService;
    }

    public function showImportForm()
    {
        return view('events.import');
    }

    public function import(ImportEventRequest $request)
    {
        $path = $request->file('csv_file')->getRealPath();
        $result = $this->importService->importFromCsv($path, auth()->id());

        return redirect()->route('events.index')
            ->with('success', "Import completed! {$result['imported']} events imported, {$result['skipped']} skipped.");
    }
}
