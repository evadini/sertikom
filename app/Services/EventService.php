<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EventService
{
    public function createEvent(Request $request): Event
    {
        return DB::transaction(function () use ($request) {
            $bannerName = $this->handleFileUpload($request, 'banner', 'images/events', 'banner_');
            $orgPhotoName = $this->handleFileUpload($request, 'organiser_photo', 'images/organizers', 'organiser_');

            $event = Event::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'category_id' => $request->category_id,
                'location' => $request->location,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'time_start' => $request->time_start,
                'time_end' => $request->time_end,
                'description' => $request->description,
                'terms' => $request->terms,
                'organiser_description' => $request->organiser_description,
                'banner' => $bannerName,
                'organiser_photo' => $orgPhotoName,
                'status' => 'pending',
            ]);

            $this->syncTickets($event, $request->ticket_types);

            return $event;
        });
    }

    public function updateEvent(Request $request, Event $event): Event
    {
        return DB::transaction(function () use ($request, $event) {
            $bannerName = $request->hasFile('banner')
                ? $this->handleFileUpload($request, 'banner', 'images/events', 'banner_', $event->banner)
                : $event->banner;

            $orgPhotoName = $request->hasFile('organiser_photo')
                ? $this->handleFileUpload($request, 'organiser_photo', 'images/organizers', 'organiser_', $event->organiser_photo)
                : $event->organiser_photo;

            $isCriticalChanged = $event->name !== $request->name
                || $event->date_start !== $request->date_start
                || $event->date_end !== $request->date_end
                || $event->time_start !== $request->time_start
                || $event->time_end !== $request->time_end
                || $event->location !== $request->location;

            $event->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'location' => $request->location,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end,
                'time_start' => $request->time_start,
                'time_end' => $request->time_end,
                'description' => $request->description,
                'terms' => $request->terms,
                'organiser_description' => $request->organiser_description,
                'banner' => $bannerName,
                'organiser_photo' => $orgPhotoName,
                'status' => $isCriticalChanged ? 'pending' : $event->status,
            ]);

            $this->syncTickets($event, $request->ticket_types);

            return $event;
        });
    }

    public function deleteEvent(Event $event): void
    {
        if ($event->banner) {
            File::delete(public_path('images/events/' . $event->banner));
        }
        if ($event->organiser_photo) {
            File::delete(public_path('images/organizers/' . $event->organiser_photo));
        }

        $event->tickets()->delete();
        $event->delete();
    }

    public function syncTickets(Event $event, array $ticketTypes): void
    {
        $event->tickets()->whereNull('order_id')->delete();

        foreach ($ticketTypes as $t) {
            $event->tickets()->create([
                'user_id' => Auth::id(),
                'ticket_type' => $t['ticket_type'],
                'price' => $t['price'],
                'stock' => $t['stock'],
                'status' => 'Active',
                'purchase_date' => now(),
            ]);
        }
    }

    private function handleFileUpload(Request $request, string $fieldName, string $path, string $prefix, ?string $oldFile = null): ?string
    {
        if (!$request->hasFile($fieldName)) {
            return $oldFile;
        }

        if ($oldFile && File::exists(public_path($path . '/' . $oldFile))) {
            File::delete(public_path($path . '/' . $oldFile));
        }

        $file = $request->file($fieldName);
        $fileName = $prefix . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $fileName);

        return $fileName;
    }
}
