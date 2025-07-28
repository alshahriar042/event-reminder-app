<?php

namespace App\Http\Controllers;

use App\Models\Event;
use League\Csv\Reader;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ImportEventController extends Controller
{
    public function showImportForm()
    {
        return view('events.import');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $csv = Reader::createFromPath($request->file('csv_file')->getRealPath());
        $csv->setHeaderOffset(0); // Assume first row is header

        $imported = 0;
        $skipped = 0;

        foreach ($csv as $record) {
            try {
                // Validate required fields
                if (empty($record['title']) || empty($record['start_time']) || empty($record['end_time'])) {
                    $skipped++;
                    continue;
                }

                // Create event
                $event = Event::create([
                    'title' => $record['title'],
                    'description' => $record['description'] ?? null,
                    'start_time' => $record['start_time'],
                    'end_time' => $record['end_time'],
                    'reminder_minutes_before' => $record['reminder_minutes_before'] ?? null,
                    'user_id' => auth()->id(),
                ]);

                // Add participants
                if (!empty($record['participants'])) {
                    $emails = array_map('trim', explode(',', $record['participants']));
                    foreach ($emails as $email) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $event->participants()->create(['email' => $email]);
                        }
                    }
                }

                $imported++;
            } catch (\Exception $e) {
                $skipped++;
                Log::error("Failed to import event: " . $e->getMessage());
            }
        }

        return redirect()->route('events.index')
            ->with('success', "Import completed! $imported events imported, $skipped skipped.");
    }
}
