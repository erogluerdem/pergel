@php
    $variants = [
        'approved' => ['class' => 'status-approved', 'icon' => 'fa-circle-check', 'label' => 'Onaylandı'],
        'submitted' => ['class' => 'status-submitted', 'icon' => 'fa-paper-plane', 'label' => 'Gönderildi'],
        'rejected' => ['class' => 'status-rejected', 'icon' => 'fa-circle-xmark', 'label' => 'Reddedildi'],
        'draft' => ['class' => 'status-draft', 'icon' => 'fa-pen-to-square', 'label' => 'Taslak'],
    ];

    $normalizedStatus = strtolower($status ?? '');
    $variant = $variants[$normalizedStatus] ?? ['class' => 'status-default', 'icon' => 'fa-circle', 'label' => ucfirst($normalizedStatus ?: 'Bilinmiyor')];
@endphp

<span class="badge badge-status {{ $variant['class'] }}">
    <i class="fa-solid {{ $variant['icon'] }} me-1"></i>{{ $variant['label'] }}
</span>
