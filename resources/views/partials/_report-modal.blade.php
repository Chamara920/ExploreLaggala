<div x-data="{ reportOpen: false }">
    <button @click="reportOpen = true" class="inline-flex items-center gap-1.5 text-xs text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 px-3 py-1.5 rounded-lg transition-colors font-medium">
        <span>⚠️</span> වැරදි තොරතුරක්ද? Report කරන්න
    </button>

    <div x-show="reportOpen" x-cloak class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="reportOpen = false" class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 text-base">⚠️ වැරදි තොරතුරු Report කිරීම</h3>
                <button @click="reportOpen = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
            </div>

            @auth
                <form method="POST" action="{{ route('reports.store') }}">
                    @csrf
                    <input type="hidden" name="reportable_type" value="{{ get_class($model) }}">
                    <input type="hidden" name="reportable_id" value="{{ $model->id }}">

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">මෙම තොරතුරෙහි ඇති දෝෂය/අසත්‍යතාව පැහැදිලි කරන්න <span class="text-red-500">*</span></label>
                        <textarea name="reason" rows="4" required minlength="5" placeholder="උදා: මෙහි සඳහන් දුරකථන අංකය/ලිපිනය නිවැරදි නැත..."
                            class="w-full rounded-xl border-gray-300 shadow-sm text-sm focus:border-red-500 focus:ring-red-500"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" @click="reportOpen = false" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-xs font-medium">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-red-600 text-white text-xs font-bold hover:bg-red-700">Submit Report</button>
                    </div>
                </form>
            @else
                <div class="text-center py-4 text-sm text-gray-600">
                    Report කිරීමට කරුණාකර <a href="{{ route('login') }}" class="text-red-600 font-bold underline">Login</a> වන්න.
                </div>
            @endauth
        </div>
    </div>
</div>
