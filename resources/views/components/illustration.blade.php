@props(['name' => 'empty', 'width' => 220])
{{--
  Pergel çizgisel ilüstrasyon seti — tema renklerini (CSS değişkenleri) kullanır,
  açık/koyu modda otomatik uyum sağlar.
  Kullanım: <x-illustration name="empty|search|site|notfound|error|forbidden|maintenance" :width="200" />
--}}
<svg class="pergel-illustration" viewBox="0 0 320 220" width="{{ $width }}" role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
<ellipse class="il-bg" cx="160" cy="198" rx="118" ry="11"/>
@switch($name)

@case('search')
  <rect class="il-soft" x="84" y="34" width="132" height="150" rx="14"/>
  <rect class="il-fill" x="74" y="24" width="132" height="150" rx="12"/>
  <path class="il-stroke" d="M94 56h60M94 76h92M94 96h80M94 116h50"/>
  <circle class="il-fill" cx="204" cy="120" r="34"/>
  <circle class="il-acc" cx="204" cy="120" r="34"/>
  <path class="il-acc" d="M228 146l32 32" stroke-width="7"/>
  <path class="il-acc" d="M190 120h28" opacity=".6"/>
  <circle class="il-dot" cx="262" cy="44" r="4"/><circle class="il-dot" cx="52" cy="140" r="3"/>
@break

@case('site')
  <path class="il-stroke" d="M40 190h240"/>
  <rect class="il-soft" x="70" y="104" width="86" height="86" rx="4"/>
  <path class="il-fill" d="M62 190v-84h86v84"/>
  <path class="il-stroke" d="M62 134h86M62 162h86M91 106v84M120 106v84"/>
  <path class="il-acc" d="M226 190V46M214 190h24M226 46h-70l-40 0M226 46l-22-12M226 46l22-12M226 46h50"/>
  <path class="il-acc" d="M176 46v34"/><rect class="il-fill" x="168" y="80" width="16" height="14" rx="3"/>
  <path class="il-stroke" d="M214 120l24-24M214 150l24-24M214 100l24 0"/>
  <circle class="il-dot" cx="262" cy="86" r="4"/><circle class="il-dot" cx="50" cy="70" r="3"/>
@break

@case('notfound')
  <text class="il-text" x="160" y="86" text-anchor="middle" font-size="52">404</text>
  <path class="il-stroke" d="M60 190h200"/>
  <path class="il-fill" d="M96 190v-62h48v62"/><path class="il-stroke" d="M96 150h48M120 130v60"/>
  <path class="il-acc" d="M206 190v-82M196 190h20M206 108h-34M206 108l-14-9M206 108l16-9M206 108h36"/>
  <path class="il-acc" d="M178 108v22"/><circle class="il-fill" cx="178" cy="136" r="7"/>
  <path class="il-stroke" d="M232 190v-30h22v30" opacity=".7"/>
  <circle class="il-dot" cx="272" cy="120" r="3"/><circle class="il-dot" cx="56" cy="110" r="3"/>
@break

@case('error')
  <circle class="il-soft" cx="160" cy="104" r="64"/>
  <path class="il-fill" d="M160 52l56 96H104z"/>
  <path class="il-acc" d="M160 52l56 96H104z"/>
  <path class="il-acc" d="M160 86v32" stroke-width="5"/><circle class="il-dot" cx="160" cy="133" r="4.5"/>
  <circle class="il-stroke" cx="86" cy="150" r="20" stroke-dasharray="7 5"/><circle class="il-stroke" cx="86" cy="150" r="9"/>
  <circle class="il-stroke" cx="244" cy="70" r="14" stroke-dasharray="5 4"/><circle class="il-stroke" cx="244" cy="70" r="6"/>
  <path class="il-stroke" d="M64 56l10 10M74 56L64 66" opacity=".7"/>
@break

@case('forbidden')
  <path class="il-soft" d="M160 30l68 22v50c0 40-30 66-68 82-38-16-68-42-68-82V52z"/>
  <path class="il-fill" d="M154 24l68 22v50c0 40-30 66-68 82-38-16-68-42-68-82V46z"/>
  <rect class="il-fill" x="126" y="92" width="56" height="44" rx="8"/>
  <path class="il-acc" d="M138 92V80a16 16 0 0 1 32 0v12"/>
  <circle class="il-dot" cx="154" cy="112" r="5"/><path class="il-acc" d="M154 117v9"/>
  <circle class="il-dot" cx="258" cy="60" r="3.5"/><circle class="il-dot" cx="50" cy="120" r="3"/>
@break

@case('maintenance')
  <path class="il-soft" d="M96 130a64 64 0 0 1 128 0z"/>
  <path class="il-fill" d="M90 134a64 64 0 0 1 128 0z" transform="translate(6 -4)"/>
  <path class="il-acc" d="M110 130a50 50 0 0 1 100 0"/>
  <path class="il-acc" d="M160 70v34M134 80l8 26M186 80l-8 26"/>
  <rect class="il-fill" x="78" y="134" width="164" height="16" rx="8"/>
  <path class="il-stroke" d="M214 176l36-36" stroke-width="7"/><circle class="il-fill" cx="256" cy="134" r="14"/>
  <path class="il-acc" d="M250 128l12 12" opacity=".8"/>
  <circle class="il-stroke" cx="70" cy="64" r="12" stroke-dasharray="5 4"/><circle class="il-dot" cx="260" cy="50" r="3.5"/>
@break

@default {{-- empty --}}
  <rect class="il-soft" x="62" y="54" width="196" height="124" rx="16"/>
  <path class="il-fill" d="M96 96h128l22 30v46a6 6 0 0 1-6 6H80a6 6 0 0 1-6-6v-46z"/>
  <path class="il-stroke" d="M74 126h56c0 12 12 20 30 20s30-8 30-20h56"/>
  <path class="il-acc" d="M160 78V54M126 84l-12-20M194 84l12-20"/>
  <circle class="il-dot" cx="94" cy="60" r="4"/><circle class="il-dot" cx="232" cy="48" r="3"/><circle class="il-dot" cx="56" cy="150" r="3"/>
@endswitch
</svg>
