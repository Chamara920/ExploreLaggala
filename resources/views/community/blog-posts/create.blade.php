<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Create Blog Post
            </h2>

            <a
                href="{{ route('community.dashboard') }}"
                class="text-sm text-gray-600 dark:text-gray-300 hover:underline"
            >
                ← Back to Dashboard
            </a>

        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <form
                method="POST"
                action="{{ route('community.blog-posts.store') }}"
                enctype="multipart/form-data"
            >

                @csrf

                @include('community.blog-posts._form')

            </form>

        </div>

    </div>


    @push('scripts')
        @include('community.blog-posts._editor-script')
    @endpush

</x-app-layout>