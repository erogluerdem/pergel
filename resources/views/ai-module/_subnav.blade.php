<!-- AI Module Sub-Navigation Ribbon -->
<div class="ai-subnav-container">
    <a href="{{ route('ai-module.index') }}" class="ai-subnav-link {{ request()->routeIs('ai-module.index') ? 'active' : '' }}">
        <i class="ri-dashboard-3-line"></i>
        <span>Komuta Merkezi</span>
    </a>
    <a href="{{ route('ai-module.proactive-assistant') }}" class="ai-subnav-link {{ request()->routeIs('ai-module.proactive-assistant') ? 'active' : '' }}">
        <i class="ri-robot-2-line"></i>
        <span>Proaktif AI Asistan</span>
    </a>
    <a href="{{ route('ai-module.risk-analysis') }}" class="ai-subnav-link {{ request()->routeIs('ai-module.risk-analysis') ? 'active' : '' }}">
        <i class="ri-shield-flash-line"></i>
        <span>Risk & Sapma Analizi</span>
    </a>
    <a href="{{ route('ai-module.document-processing') }}" class="ai-subnav-link {{ request()->routeIs('ai-module.document-processing') ? 'active' : '' }}">
        <i class="ri-file-text-line"></i>
        <span>Doküman & OCR</span>
    </a>
    <a href="{{ route('ai-module.site-data-analysis') }}" class="ai-subnav-link {{ request()->routeIs('ai-module.site-data-analysis') ? 'active' : '' }}">
        <i class="ri-sensor-line"></i>
        <span>Saha & Sensör</span>
    </a>
    <a href="{{ route('ai-module.compliance-report') }}" class="ai-subnav-link {{ request()->routeIs('ai-module.compliance-report') ? 'active' : '' }}">
        <i class="ri-scales-3-line"></i>
        <span>Mevzuat & Uyum</span>
    </a>
    <a href="{{ route('ai-module.predictive-analytics') }}" class="ai-subnav-link {{ request()->routeIs('ai-module.predictive-analytics') ? 'active' : '' }}">
        <i class="ri-line-chart-line"></i>
        <span>Öngörücü Analitik</span>
    </a>
</div>
