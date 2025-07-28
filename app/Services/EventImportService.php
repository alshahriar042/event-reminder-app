<?php
namespace App\Services;

use App\Models\Event;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;

class EventImportService
{
    public function importFromCsv(string $filePath, int $userId): array
    {
        $csv = Reader::createFromPath($filePath);
        $csv->setHeaderOffset(0);

        $imported = 0;
        $skipped = 0;

        foreach ($csv as $record) {
            try {
                if (empty($record['title']) || empty($record['start_time']) || empty($record['end_time'])) {
                    $skipped++;
                    continue;
                }

                $event = Event::create([
                    'title' => $record['title'],
                    'description' => $record['description'] ?? null,
                    'start_time' => $record['start_time'],
                    'end_time' => $record['end_time'],
                    'reminder_minutes_before' => $record['reminder_minutes_before'] ?? null,
                    'user_id' => $userId,
                ]);

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
                Log::error("Event import failed: " . $e->getMessage(), ['record' => $record]);
            }
        }

        return ['imported' => $imported, 'skipped' => $skipped];
    }
}
