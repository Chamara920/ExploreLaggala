<?php

namespace App\Notifications;

use App\Models\WeatherObservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WeatherObservationReviewed extends Notification
{
    use Queueable;

    public function __construct(
        public WeatherObservation $observation,
        public string $reviewStatus,
    ) {}

    /**
     * Get the notification delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $location = $this->observation->location_name;

        if ($this->reviewStatus === 'approved') {
            return [
                'title' => 'Weather Observation Approved',
                'message' => "Your weather observation for {$location} has been approved and is now publicly visible.",
                'status' => 'approved',
                'observation_id' => $this->observation->id,
                'location_name' => $location,
                'url' => route('plan.weather.index'),
            ];
        }

        return [
            'title' => 'Weather Observation Rejected',
            'message' => "Your weather observation for {$location} was not approved for public display.",
            'status' => 'rejected',
            'observation_id' => $this->observation->id,
            'location_name' => $location,
            'url' => route('plan.weather.index'),
        ];
    }

    /**
     * Get the database notification type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'weather-observation-reviewed';
    }
}
