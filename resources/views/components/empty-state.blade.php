@props(['title' => 'Kayıt bulunamadı', 'description' => null, 'illustration' => 'empty', 'width' => 190])
{{-- <x-empty-state title="Tedarikçi bulunamadı" description="..." illustration="search"><a class="btn btn-primary">Ekle</a></x-empty-state> --}}
<div {{ $attributes->merge(['class' => 'pergel-empty']) }}>
    <x-illustration :name="$illustration" :width="$width" />
    <h5 class="pergel-empty-title">{{ $title }}</h5>
    @if($description)<p>{{ $description }}</p>@endif
    @if(!$slot->isEmpty())<div class="mt-2">{{ $slot }}</div>@endif
</div>
