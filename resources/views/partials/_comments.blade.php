<div class="mt-10 bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-bold text-lg text-gray-900 mb-6 flex items-center gap-2">
        <span>💬</span> අදහස් &amp; ප්‍රතිචාර ({{ $model->comments->count() }})
    </h3>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 text-xs text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Comments List --}}
    @if ($model->comments->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach ($model->comments as $comment)
                <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xs text-gray-900">{{ $comment->user?->name }}</span>
                            <span class="text-[11px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        @auth
                            @if (auth()->id() === $comment->user_id || auth()->user()->hasAnyRole(['admin','super_admin']))
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Comment එක මකා දමන්නද?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[11px] text-red-500 hover:underline font-medium">Delete</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed">{!! nl2br(e($comment->content)) !!}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-xs text-gray-400 italic mb-6">තවම අදහස් දක්වා නොමැත. පළමු අදහස දක්වන්න!</p>
    @endif

    {{-- Comment Form --}}
    @auth
        <form method="POST" action="{{ route('comments.store') }}">
            @csrf
            <input type="hidden" name="commentable_type" value="{{ get_class($model) }}">
            <input type="hidden" name="commentable_id" value="{{ $model->id }}">

            <div class="mb-3">
                <textarea name="content" rows="3" required minlength="2" placeholder="ඔබේ අදහස ඇතුළත් කරන්න..."
                    class="w-full rounded-xl border-gray-300 shadow-sm text-sm focus:border-green-600 focus:ring-green-600"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 text-xs font-bold transition-colors">
                    Post Comment
                </button>
            </div>
        </form>
    @else
        <div class="p-4 bg-green-50 rounded-xl text-center text-xs text-green-800">
            අදහස් දැක්වීමට කරුණාකර <a href="{{ route('login') }}" class="font-bold underline">Login</a> වන්න.
        </div>
    @endauth
</div>
