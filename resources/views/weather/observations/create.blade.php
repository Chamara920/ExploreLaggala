@extends('layouts.app')

@section('content')

<div class="bg-gray-50 py-10">

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Report Current Weather
            </h1>

            <p class="mt-2 text-gray-600">
                Share the weather conditions you are currently observing.
                Your report will be reviewed before appearing publicly.
            </p>
        </div>

        @if(session('weather_observation_success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('weather_observation_success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
                <p class="font-semibold">
                    Please correct the following errors:
                </p>

                <ul class="mt-2 list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('weather.observations.store') }}"
            class="rounded-xl bg-white p-6 shadow-sm sm:p-8"
        >
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Location --}}
                <div class="md:col-span-2">
                    <label
                        for="location_name"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Location
                    </label>

                    <input
                        id="location_name"
                        name="location_name"
                        type="text"
                        value="{{ old('location_name') }}"
                        required
                        maxlength="255"
                        placeholder="Example: Riverston"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Latitude --}}
                <div>
                    <label
                        for="latitude"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Latitude
                    </label>

                    <input
                        id="latitude"
                        name="latitude"
                        type="number"
                        step="0.0000001"
                        value="{{ old('latitude') }}"
                        placeholder="Example: 7.5000000"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Longitude --}}
                <div>
                    <label
                        for="longitude"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Longitude
                    </label>

                    <input
                        id="longitude"
                        name="longitude"
                        type="number"
                        step="0.0000001"
                        value="{{ old('longitude') }}"
                        placeholder="Example: 80.5000000"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Condition --}}
                <div>
                    <label
                        for="condition"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Weather Condition
                    </label>

                    <select
                        id="condition"
                        name="condition"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Select condition</option>

                        <option value="sunny" @selected(old('condition') === 'sunny')>
                            ☀️ Sunny
                        </option>

                        <option value="partly_cloudy" @selected(old('condition') === 'partly_cloudy')>
                            ⛅ Partly Cloudy
                        </option>

                        <option value="cloudy" @selected(old('condition') === 'cloudy')>
                            ☁️ Cloudy
                        </option>

                        <option value="rain" @selected(old('condition') === 'rain')>
                            🌧️ Rain
                        </option>

                        <option value="heavy_rain" @selected(old('condition') === 'heavy_rain')>
                            ⛈️ Heavy Rain
                        </option>

                        <option value="mist" @selected(old('condition') === 'mist')>
                            🌫️ Mist
                        </option>

                        <option value="fog" @selected(old('condition') === 'fog')>
                            🌁 Fog
                        </option>

                        <option value="windy" @selected(old('condition') === 'windy')>
                            💨 Windy
                        </option>
                    </select>
                </div>

                {{-- Temperature --}}
                <div>
                    <label
                        for="temperature"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Temperature (°C)
                    </label>

                    <input
                        id="temperature"
                        name="temperature"
                        type="number"
                        step="0.01"
                        value="{{ old('temperature') }}"
                        placeholder="Example: 24.5"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Rainfall --}}
                <div>
                    <label
                        for="rainfall"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Rainfall (mm)
                    </label>

                    <input
                        id="rainfall"
                        name="rainfall"
                        type="number"
                        step="0.01"
                        min="0"
                        value="{{ old('rainfall') }}"
                        placeholder="Optional"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Wind --}}
                <div>
                    <label
                        for="wind_condition"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Wind Condition
                    </label>

                    <input
                        id="wind_condition"
                        name="wind_condition"
                        type="text"
                        maxlength="100"
                        value="{{ old('wind_condition') }}"
                        placeholder="Example: Light Wind"
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Observed At --}}
                <div>
                    <label
                        for="observed_at"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Observed At
                    </label>

                    <input
                        id="observed_at"
                        name="observed_at"
                        type="datetime-local"
                        value="{{ old('observed_at', now()->format('Y-m-d\TH:i')) }}"
                        required
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label
                        for="description"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        maxlength="2000"
                        placeholder="Describe the current ground weather condition..."
                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>
                </div>

            </div>

            {{-- Notice --}}
            <div class="mt-6 rounded-lg bg-amber-50 p-4 text-sm text-amber-800">
                Your weather observation will be submitted as
                <strong>Pending</strong> and will be reviewed before it is
                displayed publicly.
            </div>

            {{-- Buttons --}}
            <div class="mt-8 flex items-center justify-end gap-3">

                <a
                    href="{{ url()->previous() }}"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
                >
                    Submit Weather Report
                </button>

            </div>

        </form>

    </div>

</div>

@endsection