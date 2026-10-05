@php
    $colors = [
        'draft'          => 'bg-gray-100 text-gray-600',
        'pending_review' => 'bg-yellow-100 text-yellow-700',
        'published'      => 'bg-green-100 text-green-700',
        'rejected'       => 'bg-red-100 text-red-700',
    ];
    $labels = [
        'draft'          => 'Draft',
        'pending_review' => 'Pending Review',
        'published'      => 'Published',
        'rejected'       => 'Rejected',
    ];
    $colorClass = $colors[$status] ?? 'bg-gray-100 text-gray-600';
    $label = $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
@endphp
<span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $colorClass }}">{{ $label }}</span>
