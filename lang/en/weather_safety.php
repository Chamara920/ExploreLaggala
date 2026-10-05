<?php

return [
    'page_title'               => 'Weather & Regional Safety Alerts — Explore Laggala',
    'badge_plan'               => 'Plan Your Trip',
    'hero_title'               => 'Weather Forecasts & Regional Safety Alerts',
    'hero_subtitle'            => 'Live multi-location forecasts from Open-Meteo, community ground weather reports, and vital landslide/hazard safety notices across Laggala.',

    // Tabs & Sections
    'tab_weather'              => '1. Regional Weather Data',
    'tab_safety'               => '2. Safety & Disaster Alerts',
    
    // Section 1: Weather Data
    'sec_weather_title'        => 'Regional Weather Information',
    'sec_weather_subtitle'     => 'International live satellite forecast combined with verified real ground observations',
    'meteo_title'              => 'Live Satellite & Forecast Data (Open-Meteo)',
    'select_station'           => 'Select Region / Station:',
    'station_coverage_note'    => 'Covering highland peaks, valleys, and foothills across Laggala',
    'rain_prob'                => 'Rain Probability',
    'wind_speed'               => 'Wind Speed',
    'humidity'                 => 'Humidity',
    'temperature'              => 'Temperature',
    'elevation'                => 'Elevation',
    'five_day_forecast'        => '5-Day Regional Forecast',

    // Sub-component 1B: Ground Observations
    'ground_obs_title'         => 'Real Ground Weather Conditions',
    'ground_obs_subtitle'      => 'Recent updates reported by locals and travelers (Visible for 48 hours)',
    'obs_notice_48h'           => 'Note: Only real observations reported within the last 48 hours are shown for maximum accuracy.',
    'no_obs_title'             => 'No active ground observations in the past 48 hours',
    'no_obs_subtitle'          => 'Be the first to report current ground weather in Laggala!',
    'btn_report_weather'       => 'Report Ground Weather',
    'observed'                 => 'Observed',
    'reported_by'              => 'Reported by',
    'btn_delete'               => 'Delete',

    // Conditions
    'cond_sunny'               => 'Sunny',
    'cond_partly_cloudy'       => 'Partly Cloudy',
    'cond_cloudy'              => 'Overcast / Cloudy',
    'cond_rain'                => 'Light Rain',
    'cond_heavy_rain'          => 'Heavy Rain / Downpour',
    'cond_mist'                => 'Mist',
    'cond_fog'                 => 'Dense Fog',
    'cond_windy'               => 'High Winds',

    // Section 2: Safety & Disaster Alerts
    'sec_safety_title'         => 'Natural Hazard & Safety Alerts',
    'sec_safety_subtitle'      => 'Critical advisories on landslides, rockfalls, dense mist, and stream flash floods in Laggala',
    'btn_post_alert'           => 'Post Safety Alert',
    'alert_immediate_notice'   => 'Community alerts appear immediately for public safety. Administrators review and may dismiss resolved notices.',
    'no_alerts_title'          => 'No active hazard alerts at this moment',
    'no_alerts_subtitle'       => 'Normal safety conditions reported across major tourist paths. Always exercise caution in mountain weather.',
    'emergency_hotlines_title' => 'Emergency Response Contacts',
    'instructions_title'       => 'Safety Instructions for Travelers',
    'expires_in'               => 'Valid / Active:',

    // Hazard types
    'hazard_landslide'         => 'Landslide Alert',
    'hazard_rockfall'          => 'Rockfall Danger',
    'hazard_flash_flood'       => 'Stream Flash Flood Warning',
    'hazard_high_wind'         => 'Severe Wind Gale',
    'hazard_dense_mist'        => 'Blinding Dense Mist',
    'hazard_road_closure'      => 'Road Blockage / Slippery Path',
    'hazard_other'             => 'General Natural Hazard',

    // Severity
    'severity_advisory'        => 'Advisory Notice',
    'severity_warning'         => 'Severe Warning',
    'severity_danger'          => 'Critical Danger',

    // Forms & Modals
    'modal_location_title'     => 'Add Regional Weather Station (Admin Only)',
    'modal_obs_title'          => 'Submit Ground Weather Observation',
    'modal_alert_title'        => 'Submit Natural Hazard / Safety Alert',
    'field_location_name'      => 'Location / Area Name *',
    'field_station'            => 'Select Weather Station',
    'field_hazard_type'        => 'Hazard Type *',
    'field_severity'           => 'Severity Level *',
    'field_condition'          => 'Observed Condition *',
    'field_temp'               => 'Temperature (°C)',
    'field_rainfall'           => 'Rainfall (mm)',
    'field_wind'               => 'Wind Condition',
    'field_description'        => 'Details & Description *',
    'field_instructions'       => 'Safety Instructions / Action to Take',
    'field_lat'                => 'Latitude',
    'field_lng'                => 'Longitude',
    'field_elevation'          => 'Elevation (meters)',
    'btn_submit'               => 'Submit Report',
    'btn_save_location'        => 'Save Weather Station',
    'btn_cancel'               => 'Cancel',

    // Guest Call to Action Card
    'guest_cta_title'          => 'Contribute Weather & Safety Updates',
    'guest_cta_desc'           => 'Are you in Laggala right now? Sign in or register as a Community User to post live ground conditions or safety alerts.',
    'btn_signin'               => 'Sign In',
    'btn_register'             => 'Create Account',

    // Flash notifications
    'msg_obs_saved'            => 'Thank you! Your ground weather observation has been published successfully.',
    'msg_alert_saved'          => 'Safety alert has been published to keep travelers and locals informed.',
    'msg_location_saved'       => 'New weather station location has been configured successfully.',
    'msg_item_deleted'         => 'Item has been removed successfully.',
    'msg_unauthorized'         => 'You do not have permission to perform this action.',
];
