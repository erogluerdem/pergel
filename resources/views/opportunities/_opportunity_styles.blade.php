<style>
    /* Global Graphics Enhancement */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }

    /* Hardware acceleration for better performance */
    .modern-kanban-column,
    .modern-kanban-card,
    .stats-card {
        transform: translateZ(0);
        will-change: transform;
        backface-visibility: hidden;
    }

    /* Modern Statistics Cards */
    .stats-card {
        border-radius: 8px;
        padding: 14px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15), 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-primary-dark));
        border: 1px solid rgba(255,255,255,0.2);
        min-height: 85px;
        max-height: 85px;
        display: flex !important;
        align-items: center !important;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 70%, transparent 100%);
        border-radius: 50%;
        z-index: 1;
    }

    .stats-card::after {
        content: '';
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 80px;
        height: 80px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        z-index: 1;
    }

    .stats-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 60px rgba(0,0,0,0.2), 0 8px 25px rgba(0,0,0,0.15);
    }

    /* Use project theme variables when available, fall back to sensible defaults */
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--santiye-primary, var(--bs-primary, #4f46e5)) 0%, var(--santiye-accent, var(--bs-primary, #3b82f6)) 100%) !important;
        box-shadow: 0 8px 25px rgba(85, 110, 230, 0.3);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, var(--bs-success, #059669) 0%, var(--santiye-success, var(--bs-success, #10b981)) 100%) !important;
        box-shadow: 0 8px 25px rgba(52, 195, 143, 0.3);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, var(--bs-info, #0891b2) 0%, var(--santiye-info, var(--bs-info, #06b6d4)) 100%) !important;
        box-shadow: 0 8px 25px rgba(80, 165, 241, 0.3);
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, var(--bs-warning, #d97706) 0%, var(--santiye-warning, var(--bs-warning, #f59e0b)) 100%) !important;
        box-shadow: 0 8px 25px rgba(241, 180, 76, 0.3);
    }

    .stats-card .stats-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 1.5rem;
        opacity: 0.25;
        z-index: 2;
    }

    /* Small reusable stat icons used in detail/list views */
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .stat-icon.primary { background: rgba(59,130,246,0.10); color: var(--santiye-primary, var(--bs-primary, #556ee6)); }
    .stat-icon.success { background: rgba(52,195,143,0.10); color: var(--bs-success, #34c38f); }
    .stat-icon.info { background: rgba(80,165,241,0.10); color: var(--bs-info, #50a5f1); }
    .stat-icon.warning { background: rgba(241,180,76,0.10); color: var(--bs-warning, #f1b44c); }
    .stat-icon.danger { background: rgba(244,106,106,0.10); color: var(--bs-danger, #f46a6a); }

    /* smaller avatars used in list/kanban */
    .owner-avatar-small {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        color: white;
        background: linear-gradient(135deg, var(--santiye-primary, var(--bs-primary, #667eea)), var(--santiye-accent, #764ba2));
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }

    /* Progress circle (SVG) styling */
    .progress-circle { width: 80px; height: 80px; position: relative; display:inline-block; }
    .progress-circle svg { display:block; }
    .progress-circle .bg { stroke: var(--progress-bg, #e9ecef); }
    .progress-circle .progress { stroke: var(--santiye-primary, var(--bs-primary, #556ee6)); transition: stroke-dashoffset 0.8s ease; }
    .progress-percent { font-size: 18px; color: var(--santiye-primary, var(--bs-primary, #556ee6)); font-weight: 700; }

    /* muted icon helper */
    .icon-muted { color: var(--icon-muted, #dee2e6); }

    .stats-card .stats-content {
        position: relative;
        z-index: 3;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stats-card h3 {
        font-size: 1.1rem;
        font-weight: 900;
        margin-bottom: 2px;
        color: #ffffff !important;
        text-shadow: 2px 2px 6px rgba(0,0,0,0.8) !important;
        line-height: 1.1;
    }

    .stats-card p {
        font-size: 0.7rem;
        font-weight: 800;
        opacity: 1;
        color: #ffffff !important;
        text-shadow: 2px 2px 6px rgba(0,0,0,0.8) !important;
        line-height: 1;
        margin-bottom: 2px;
    }

    .stats-card small {
        color: #ffffff !important;
        font-weight: 700;
        text-shadow: 2px 2px 6px rgba(0,0,0,0.8) !important;
        font-size: 0.6rem;
        line-height: 0.9;
    }

    /* Filter Card - Force Visibility */
    .filter-card {
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border-radius: 12px;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 10 !important;
        background: white !important;
        margin-bottom: 20px !important;
    }

    .filter-card .card-body {
        display: block !important;
        visibility: visible !important;
        padding: 20px !important;
    }

    .filter-card .form-control, .filter-card .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        color: #374151;
        background-color: white;
    }

    .filter-card .form-control::placeholder {
        color: #9ca3af;
    }

    .filter-card .form-control:focus, .filter-card .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Modern Kanban Columns - Enhanced Graphics with Performance */
    .modern-kanban-column {
        background: var(--col-bg) !important;
        border-radius: 20px;
        min-height: 500px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15), 0 2px 16px rgba(0,0,0,0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: none;
        transform: translateZ(0);
        will-change: transform, box-shadow;
        contain: layout style paint;
        width: 100%;
    }

    /* Stage-specific column colors - Project Theme Compatible */
    /* Stage-specific column colors (use theme variables when available) */
    .modern-kanban-column[id="col-lead"] {
        background: linear-gradient(135deg, var(--stage-lead, var(--bs-secondary, #74788d)) 0%, var(--stage-lead-dark, var(--bs-secondary, #6c757d)) 100%) !important;
    }

    .modern-kanban-column[id="col-qualification"] {
        background: linear-gradient(135deg, var(--stage-qualification, var(--bs-primary, #556ee6)) 0%, var(--stage-qualification-dark, var(--bs-primary, #3d5af1)) 100%) !important;
    }

    .modern-kanban-column[id="col-proposal"] {
        background: linear-gradient(135deg, var(--stage-proposal, var(--bs-warning, #f1b44c)) 0%, var(--stage-proposal-dark, var(--bs-warning, #e09540)) 100%) !important;
    }

    .modern-kanban-column[id="col-negotiation"] {
        background: linear-gradient(135deg, var(--stage-negotiation, var(--bs-info, #50a5f1)) 0%, var(--stage-negotiation-dark, var(--bs-info, #2e86de)) 100%) !important;
    }

    .modern-kanban-column[id="col-won"] {
        background: linear-gradient(135deg, var(--stage-won, var(--bs-success, #34c38f)) 0%, var(--stage-won-dark, var(--bs-success, #1ea776)) 100%) !important;
    }

    .modern-kanban-column[id="col-lost"] {
        background: linear-gradient(135deg, var(--stage-lost, var(--bs-danger, #f46a6a)) 0%, var(--stage-lost-dark, var(--bs-danger, #e44a4a)) 100%) !important;
    }

    .modern-kanban-column::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0.8) 100%);
    }

    .modern-kanban-column.drag-over {
        transform: scale(1.03) translateY(-5px);
        box-shadow: 0 20px 60px rgba(59, 130, 246, 0.3), 0 8px 30px rgba(59, 130, 246, 0.2);
        border: 2px solid rgba(59, 130, 246, 0.6);
    }

    .modern-kanban-header {
        padding: 24px 20px;
        border-bottom: 2px solid rgba(255,255,255,0.4);
        background: rgba(0,0,0,0.15) !important;
        backdrop-filter: blur(10px);
        position: relative;
        border-radius: 20px 20px 0 0;
    }

    .modern-kanban-header h6 {
        color: white !important;
        text-shadow: 0 2px 6px rgba(0,0,0,0.5) !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        display: block !important;
        visibility: visible !important;
    }

    .stage-indicator {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #ffffff !important;
        margin-right: 12px;
        animation: pulse 2s infinite;
        box-shadow: 0 0 15px rgba(255,255,255,0.8), inset 0 0 5px rgba(0,0,0,0.1);
        border: 3px solid rgba(255,255,255,0.9);
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
            box-shadow: 0 0 10px rgba(255,255,255,0.5);
        }
        50% {
            opacity: 0.7;
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(255,255,255,0.8);
        }
    }

    .stage-stats {
        font-size: 13px !important;
        opacity: 1 !important;
        font-weight: 600 !important;
        display: flex !important;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
        color: rgba(255,255,255,1) !important;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5) !important;
        visibility: visible !important;
    }

    .stage-stats i {
        font-size: 11px;
        opacity: 0.8;
    }

    /* Modern Kanban Cards */
    .modern-kanban-list {
        padding: 16px;
        min-height: 300px;
        max-height: 70vh;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.3) transparent;
    }

    .modern-kanban-list::-webkit-scrollbar {
        width: 4px;
    }

    .modern-kanban-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .modern-kanban-list::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.3);
        border-radius: 2px;
    }

    .modern-kanban-card {
        background: #ffffff !important;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid #e5e7eb !important;
        position: relative;
        overflow: hidden;
        backdrop-filter: none;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        transform: translateZ(0);
        will-change: transform, box-shadow;
        contain: layout style paint;
    }

    .modern-kanban-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(135deg, var(--santiye-primary, var(--bs-primary, #667eea)) 0%, var(--santiye-accent, var(--bs-primary, #764ba2)) 50%, var(--santiye-accent-2, #f093fb) 100%);
        transition: all 0.4s ease;
        border-radius: 0 8px 8px 0;
    }

    .modern-kanban-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: rotate(45deg);
        transition: all 0.6s ease;
        opacity: 0;
    }

    .modern-kanban-card:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 8px 32px rgba(0,0,0,0.12) !important;
        border-color: #d1d5db !important;
    }

    .modern-kanban-card:hover::before {
        width: 8px;
        background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 50%, #45b7d1 100%);
    }

    .modern-kanban-card:hover::after {
        opacity: 1;
        right: 150%;
    }

    .modern-kanban-card.overdue {
        background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
        border: 2px solid rgba(239, 68, 68, 0.3);
        box-shadow: 0 8px 32px rgba(239, 68, 68, 0.2), 0 2px 8px rgba(239, 68, 68, 0.15);
    }

    .modern-kanban-card.overdue::before {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        width: 6px;
        animation: pulse-red 1.5s infinite;
    }

    .modern-kanban-card.upcoming {
        background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%);
        border: 2px solid rgba(245, 158, 11, 0.3);
        box-shadow: 0 8px 32px rgba(245, 158, 11, 0.2), 0 2px 8px rgba(245, 158, 11, 0.15);
    }

    .modern-kanban-card.upcoming::before {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        width: 6px;
        animation: pulse-orange 2s infinite;
    }

    @keyframes pulse-red {
        0%, 100% { opacity: 1; width: 6px; }
        50% { opacity: 0.7; width: 8px; }
    }

    @keyframes pulse-orange {
        0%, 100% { opacity: 1; width: 6px; }
        50% { opacity: 0.8; width: 7px; }
    }

    .card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .card-title {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        line-height: 1.5 !important;
        margin-bottom: 10px !important;
        text-shadow: none !important;
        display: block !important;
        visibility: visible !important;
    }

    .card-customer {
        font-size: 13px !important;
        color: #374151 !important;
        margin-top: 6px;
        display: flex;
        align-items: center;
        padding: 8px 14px !important;
        background: rgba(59, 130, 246, 0.08) !important;
        border-radius: 20px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(59, 130, 246, 0.2) !important;
        font-weight: 600 !important;
    }

    .card-customer i {
        color: #94a3b8;
        margin-right: 8px;
        font-size: 12px;
    }

    .probability-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 25px;
        text-align: center;
        min-width: 55px;
        position: relative;
        overflow: hidden;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: 2px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }

    .probability-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .probability-badge:hover::before {
        left: 100%;
    }

    .probability-high {
        background: linear-gradient(135deg, var(--bs-success, #10b981) 0%, var(--santiye-success, #34d399) 100%);
        color: white;
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
    }

    .probability-medium {
        background: linear-gradient(135deg, var(--bs-warning, #f59e0b) 0%, var(--santiye-warning, #fbbf24) 100%);
        color: white;
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
    }

    .probability-low {
        background: linear-gradient(135deg, var(--bs-danger, #ef4444) 0%, var(--santiye-danger, #f87171) 100%);
        color: white;
        box-shadow: 0 4px 20px rgba(239, 68, 68, 0.4);
    }

    .card-body-section {
        margin-bottom: 12px;
    }

    .value-section {
        padding: 16px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-radius: 12px;
        margin-bottom: 16px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
    }

    .value-section::before {
        content: '';
        position: absolute;
        top: 8px;
        right: 12px;
        font-size: 20px;
        opacity: 0.3;
        font-family: 'remixicon' !important;
        font-style: normal;
        font-weight: normal;
    }

    .value-section small {
        color: #6b7280;
        font-weight: 500;
    }

    .value-amount {
        font-size: 18px !important;
        font-weight: 800 !important;
        color: var(--bs-success, #059669) !important;
        text-shadow: none !important;
        display: block !important;
        visibility: visible !important;
    }

    .value-amount-small {
        font-size: 14px;
        font-weight: 600;
        color: #475569;
    }

    .card-meta {
        margin-bottom: 8px;
    }

    .owner-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .owner-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--santiye-primary, var(--bs-primary, #667eea)) 0%, var(--santiye-accent, #764ba2) 50%, var(--santiye-accent-2, #f093fb) 100%);
        color: white;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        border: 2px solid rgba(255,255,255,0.8);
    }

    .owner-name {
        font-size: 13px !important;
        color: #1e293b !important;
        font-weight: 600 !important;
        margin-left: 4px;
        display: block !important;
        visibility: visible !important;
    }

    .card-actions .btn-sm {
        padding: 8px 16px;
        font-size: 12px;
        border-radius: 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid;
        position: relative;
        overflow: hidden;
    }

    .card-actions .btn-outline-primary {
        background: linear-gradient(135deg, #556ee6, #3d5af1);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(85, 110, 230, 0.3);
    }

    .card-actions .btn-outline-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(85, 110, 230, 0.4);
    }

    .card-actions .btn-outline-success {
        background: linear-gradient(135deg, #34c38f, #1ea776);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(52, 195, 143, 0.3);
    }

    .card-actions .btn-outline-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(52, 195, 143, 0.4);
    }

    .date-info {
        text-align: right;
        font-size: 12px;
        color: #6b7280;
        background: rgba(100, 116, 139, 0.12);
        padding: 6px 10px;
        border-radius: 15px;
        border: 1px solid rgba(100, 116, 139, 0.2);
        backdrop-filter: blur(5px);
        font-weight: 500;
    }

    .date-info.text-danger {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.3);
        color: #dc2626 !important;
        animation: pulse-warning 2s infinite;
    }

    .date-info.text-warning {
        background: rgba(245, 158, 11, 0.15);
        border-color: rgba(245, 158, 11, 0.3);
        color: #d97706 !important;
    }

    @keyframes pulse-warning {
        0%, 100% { box-shadow: 0 0 5px rgba(239, 68, 68, 0.3); }
        50% { box-shadow: 0 0 15px rgba(239, 68, 68, 0.5); }
    }

    .card-actions {
        border-top: 1px solid #f1f5f9;
        padding-top: 12px;
        margin-top: 12px;
    }

    .card-actions .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 6px;
    }

    /* Kanban Empty State - Enhanced */
    .kanban-empty {
        text-align: center;
        padding: 40px 20px;
        color: rgba(255,255,255,0.95) !important;
        background: rgba(255,255,255,0.15) !important;
        border-radius: 16px;
        border: 2px dashed rgba(255,255,255,0.5) !important;
        margin: 20px 0;
        backdrop-filter: blur(5px);
    }

    .kanban-empty i {
        font-size: 56px;
        margin-bottom: 20px;
        display: block;
        opacity: 0.6;
        text-shadow: 0 0 20px rgba(255,255,255,0.3);
        animation: float 3s ease-in-out infinite;
        font-family: 'remixicon' !important;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .kanban-empty p {
        margin-bottom: 20px;
        font-size: 15px;
        font-weight: 600;
        opacity: 1;
        color: rgba(255,255,255,0.95);
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    .kanban-empty .btn {
        background: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.1) 100%);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 25px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .kanban-empty .btn:hover {
        background: linear-gradient(135deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0.2) 100%);
        border-color: rgba(255,255,255,0.6);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255,255,255,0.2);
    }

    /* Responsive Design */
    /* Kanban Board Background - 2-Row Layout Design */
    .kanban-board {
        background: #f8fafc !important;
        border-radius: 20px;
        padding: 25px 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        position: relative;
        margin: 0 !important;
        overflow: visible;
        border: 1px solid #e2e8f0;
        min-height: 900px;
    }

    /* Better column spacing */
    .kanban-board .row {
        margin: 0 -10px;
    }

    .kanban-board [class*="col-"] {
        padding: 0 10px;
    }

    .kanban-board::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="%23cbd5e1" opacity="0.1"/></svg>') repeat;
        background-size: 50px 50px;
        pointer-events: none;
        opacity: 0.3;
    }

    /* Wide screen optimizations for 2-row layout */
    @media (min-width: 1400px) {
        .kanban-board .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .modern-kanban-column {
            min-height: 450px;
        }

        .container-fluid {
            max-width: 1800px;
            padding: 0 30px;
        }
    }

    @media (max-width: 1399px) and (min-width: 1200px) {
        .kanban-board .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .kanban-board {
            padding: 20px 15px;
        }

        .modern-kanban-column {
            min-height: 420px;
        }
    }

    @media (max-width: 1200px) {
        .kanban-board .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .modern-kanban-column {
            min-height: 400px;
        }

        .modern-kanban-list {
            max-height: 50vh;
        }

        .kanban-board {
            padding: 20px 10px;
        }
    }

    @media (max-width: 991px) {
        .kanban-board .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        .modern-kanban-column {
            min-height: 350px;
        }

        .modern-kanban-list {
            max-height: 40vh;
        }

        .stats-card h3 {
            font-size: 1.8rem !important;
        }

        .kanban-board {
            padding: 15px;
        }

        .kanban-board .row {
            margin-bottom: 0;
        }
    }

    @media (max-width: 768px) {
        .kanban-board {
            padding: 15px 10px !important;
        }

        .kanban-board .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 15px;
        }

        .modern-kanban-column {
            min-height: 350px;
            border-radius: 16px;
        }

        .modern-kanban-header {
            padding: 20px 16px;
            border-radius: 16px 16px 0 0;
        }

        .modern-kanban-header h6 {
            font-size: 14px !important;
        }

        .stats-card {
            padding: 16px;
            margin-bottom: 12px;
            min-height: 120px !important;
            border-radius: 16px;
        }

        .stats-card h3 {
            font-size: 1.5rem !important;
        }

        .modern-kanban-card {
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 12px;
        }

        .card-title {
            font-size: 14px !important;
        }

        .value-amount {
            font-size: 16px !important;
        }

        .counter {
            font-size: 12px !important;
            padding: 4px 8px !important;
            min-width: 24px;
        }
    }

    /* Table improvements for list view */
    .table-responsive table.table tbody tr:hover {
        background: rgba(59, 130, 246, 0.05);
        transform: scale(1.001);
    }

    table.table td.text-end {
        white-space: nowrap;
    }

    table.table th, table.table td {
        vertical-align: middle;
        padding: 16px 12px;
    }

    table.table th {
        background: #f8fafc;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    /* Loading and transition effects */
    .fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
            filter: blur(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }
    }

    .slide-in {
        animation: slideIn 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    @keyframes slideIn {
        from {
            transform: translateX(-30px) scale(0.9);
            opacity: 0;
            filter: blur(3px);
        }
        to {
            transform: translateX(0) scale(1);
            opacity: 1;
            filter: blur(0);
        }
    }

    /* Page loading effect */
    .page-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        z-index: 9999;
        animation: loading 2s ease-in-out;
    }

    @keyframes loading {
        0% { width: 0%; }
        50% { width: 70%; }
        100% { width: 100%; opacity: 0; }
    }

    /* Card entrance animations with staggered delay */
    .modern-kanban-card:nth-child(1) { animation-delay: 0.1s; }
    .modern-kanban-card:nth-child(2) { animation-delay: 0.2s; }
    .modern-kanban-card:nth-child(3) { animation-delay: 0.3s; }
    .modern-kanban-card:nth-child(4) { animation-delay: 0.4s; }
    .modern-kanban-card:nth-child(5) { animation-delay: 0.5s; }
    .modern-kanban-card:nth-child(n+6) { animation-delay: 0.6s; }

    /* Statistics Cards - Force Visibility */
    .stats-card {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 10 !important;
        margin-bottom: 20px !important;
    }

    .stats-card .stats-content {
        position: relative;
        z-index: 3;
        display: block !important;
        visibility: visible !important;
    }

    .stats-card .stats-icon {
        display: block !important;
        visibility: visible !important;
        opacity: 0.3 !important;
    }

    .stats-card h3 {
        color: white !important;
        text-shadow: 0 2px 4px rgba(0,0,0,0.4) !important;
        font-weight: 700 !important;
        display: block !important;
        visibility: visible !important;
        font-size: 2.2rem !important;
    }

    .stats-card p {
        color: white !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3) !important;
        font-weight: 600 !important;
        opacity: 1 !important;
        display: block !important;
        visibility: visible !important;
        font-size: 1rem !important;
    }

    .stats-card small {
        color: rgba(255,255,255,0.95) !important;
        font-weight: 500 !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2) !important;
        display: block !important;
        visibility: visible !important;
    }

    /* Counter badges in kanban headers - Enhanced */
    .counter {
        background: #ffffff !important;
        color: #1e293b !important;
        font-weight: 800 !important;
        text-shadow: none !important;
        border: 2px solid rgba(255,255,255,0.9) !important;
        font-size: 13px !important;
        padding: 6px 12px !important;
        border-radius: 20px !important;
        display: inline-block !important;
        visibility: visible !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2) !important;
        min-width: 32px;
        text-align: center;
    }

    /* Force Page Elements Visibility - Enhanced Width for 2-Row Layout */
    .container-fluid {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        padding: 0 20px;
        max-width: 1800px !important;
        margin: 0 auto;
    }

    .row {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        margin: 0 -15px;
    }

    .col-md-3, .col-sm-6, .mb-3, .col-12 {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        padding: 0 15px;
    }

    /* Prevent horizontal scrolling issues - Enhanced */
    body {
        overflow-x: hidden;
    }

    .container-fluid {
        max-width: 100%;
        overflow-x: hidden;
        padding-left: 15px;
        padding-right: 15px;
    }

    /* Smooth kanban board scrolling */
    .kanban-board {
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: rgba(0,0,0,0.2) transparent;
    }

    .kanban-board::-webkit-scrollbar {
        height: 8px;
    }

    .kanban-board::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.05);
        border-radius: 4px;
    }

    .kanban-board::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.2);
        border-radius: 4px;
    }

    .kanban-board::-webkit-scrollbar-thumb:hover {
        background: rgba(0,0,0,0.3);
    }

    /* Header Section */
    .d-flex.justify-content-between.align-items-center.mb-4 {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        margin-bottom: 1.5rem !important;
    }

    .d-flex.justify-content-between.align-items-center.mb-4 h2 {
        color: #1e293b !important;
        display: block !important;
        visibility: visible !important;
    }

    .d-flex.justify-content-between.align-items-center.mb-4 p {
        color: #6b7280 !important;
        display: block !important;
        visibility: visible !important;
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    /* Additional styles for list view */
    .owner-avatar-small {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        font-size: 9px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        text-transform: uppercase;
    }

    .opportunity-row {
        transition: all 0.2s ease;
    }

    .opportunity-row:hover {
        background: rgba(59, 130, 246, 0.05) !important;
        transform: scale(1.001);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
    }

    #bulkActions {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border-top: 2px solid #e5e7eb;
    }

    /* Opportunity Detail Page Styles */
    .opportunity-detail-header {
        background: linear-gradient(135deg, var(--santiye-primary, var(--bs-primary, #667eea)) 0%, var(--santiye-accent, #764ba2) 100%);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 15px 15px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .opportunity-detail-header h1 {
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
    }

    .opportunity-detail-header .lead {
        font-size: 1.1rem;
        font-weight: 500;
        opacity: 0.95;
    }

    .stage-badge {
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    /* Stage badges - prefer theme variables */
    .stage-lead { background: linear-gradient(45deg, var(--stage-lead, #ffeaa7), var(--stage-lead-dark, #fab1a0)); color: var(--stage-lead-text, #2d3436); }
    .stage-qualification { background: linear-gradient(45deg, var(--stage-qualification, #a29bfe), var(--stage-qualification-dark, #6c5ce7)); color: var(--stage-qualification-text, white); }
    .stage-proposal { background: linear-gradient(45deg, var(--stage-proposal, #fd79a8), var(--stage-proposal-dark, #e84393)); color: var(--stage-proposal-text, white); }
    .stage-negotiation { background: linear-gradient(45deg, var(--stage-negotiation, #fdcb6e), var(--stage-negotiation-dark, #e17055)); color: var(--stage-negotiation-text, white); }
    .stage-won { background: linear-gradient(45deg, var(--stage-won, var(--bs-success, #00b894)), var(--stage-won-dark, #00cec9)); color: var(--stage-won-text, white); }
    .stage-lost { background: linear-gradient(45deg, var(--stage-lost, #e17055), var(--stage-lost-dark, var(--bs-danger, #d63031))); color: var(--stage-lost-text, white); }

    .stats-card {
        background: white;
        border: none;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stats-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        font-weight: bold;
    }

    .stats-card h3 {
        font-weight: 700;
        font-size: 1.8rem;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .stats-card .text-muted {
        font-weight: 600;
        font-size: 0.9rem;
        color: #718096 !important;
    }

    .progress-circle {
        width: 80px;
        height: 80px;
    }

    .progress-circle circle {
        fill: none;
        stroke-width: 6;
        stroke-linecap: round;
        transform: rotate(-90deg);
        transform-origin: 40px 40px;
    }

    .progress-circle .bg {
        stroke: #e9ecef;
    }

    .progress-circle .progress {
        stroke: #28a745;
        stroke-dasharray: 188.5;
        transition: stroke-dashoffset 1s ease-in-out;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -25px;
        top: 8px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: white;
        border: 3px solid #667eea;
        z-index: 1;
    }

    .timeline-item.completed::before {
        background: #28a745;
        border-color: #28a745;
    }

    .feasibility-card {
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .feasibility-card:hover {
        border-left-color: #764ba2;
        transform: translateX(5px);
    }

    .contact-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .contact-name {
        font-weight: 600;
        color: #2d3748;
        font-size: 1rem;
    }

    .contact-position {
        font-weight: 500;
        color: #718096;
        font-size: 0.85rem;
    }

    .action-buttons .btn {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.6rem 1.2rem;
        letter-spacing: 0.3px;
    }

    .btn i {
        font-size: 1.1rem;
        margin-right: 0.5rem;
    }

    .card-title {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.1rem;
    }

    .timeline-item h6 {
        font-weight: 600;
        color: #2d3748;
        font-size: 1rem;
    }

    .timeline-item small {
        font-weight: 500;
        color: #718096;
    }

    /* Icon improvements */
    .ri-rocket-line, .ri-edit-line, .ri-sticky-note-line,
    .ri-user-add-line, .ri-arrow-right-line,
    .ri-team-line, .ri-folder-line, .ri-pie-chart-line,
    .ri-add-line, .ri-phone-line, .ri-mail-line, .ri-upload-line {
        font-size: 1.1rem !important;
        font-weight: bold;
    }

    /* Button improvements */
    .btn-outline-primary:hover, .btn-outline-success:hover,
    .btn-outline-info:hover, .btn-outline-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-primary, .btn-success, .btn-info, .btn-warning {
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .dropdown-item {
        font-weight: 500;
        padding: 0.5rem 1rem;
    }

    .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    /* Progress text improvements */
    .progress-text {
        font-weight: 700;
        font-size: 1.2rem;
        color: #2d3748;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .opportunity-detail-header {
            padding: 1rem 0;
            margin-bottom: 1rem;
        }

        .opportunity-detail-header h1 {
            font-size: 1.8rem;
        }

        .stats-card {
            margin-bottom: 1rem;
        }

        .timeline {
            padding-left: 1.5rem;
        }

        .timeline::before {
            left: 10px;
        }

        .timeline-item::before {
            left: -20px;
        }

        .btn {
            font-size: 0.85rem;
        }

        .stats-icon {
            width: 45px;
            height: 45px;
            font-size: 1.5rem;
        }
    }

    /* -------------------------------------------------- */
    /* DARK MODE OVERRIDES FOR OPPORTUNITIES (KANBAN & LIST) */
    /* -------------------------------------------------- */
    html[data-bs-theme="dark"] .opp-main-wrapper-card .card-title,
    html[data-bs-theme="dark"] .opp-header-title {
        color: #f8fafc !important;
    }

    html[data-bs-theme="dark"] .opp-main-wrapper-card .card-title-desc,
    html[data-bs-theme="dark"] .opp-header-desc {
        color: #94a3b8 !important;
    }

    .filter-card {
        border-radius: 14px !important;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .filter-card,
    :root:not([data-bs-theme="dark"]) .filter-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04) !important;
        color: #1e293b !important;
    }

    html[data-bs-theme="dark"] .filter-card {
        background: rgba(30, 41, 59, 0.75) !important;
        backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        color: #f8fafc !important;
    }

    html[data-bs-theme="dark"] .filter-card .input-group-text {
        background-color: rgba(15, 23, 42, 0.7) !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        color: #94a3b8 !important;
    }

    html[data-bs-theme="dark"] .filter-card .form-control,
    html[data-bs-theme="dark"] .filter-card .form-select {
        background-color: rgba(15, 23, 42, 0.7) !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        color: #f8fafc !important;
    }

    html[data-bs-theme="dark"] .filter-card .form-select option {
        background-color: #1e293b !important;
        color: #f8fafc !important;
    }

    html[data-bs-theme="dark"] .filter-card .form-control::placeholder {
        color: #64748b !important;
    }

    /* Kanban Columns Styling */
    .modern-kanban-column {
        border-radius: 16px !important;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .modern-kanban-column,
    :root:not([data-bs-theme="dark"]) .modern-kanban-column {
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-column {
        background: rgba(15, 23, 42, 0.65) !important;
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
    }

    /* Kanban Cards Inside Columns */
    .modern-kanban-card {
        border-radius: 14px !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    html[data-bs-theme="light"] .modern-kanban-card,
    :root:not([data-bs-theme="dark"]) .modern-kanban-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        color: #1e293b !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card {
        background: rgba(30, 41, 59, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35) !important;
        color: #f8fafc !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card:hover {
        transform: translateY(-3px) scale(1.005);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5) !important;
        border-color: rgba(255, 255, 255, 0.25) !important;
    }

    html[data-bs-theme="light"] .modern-kanban-card .card-title,
    :root:not([data-bs-theme="dark"]) .modern-kanban-card .card-title {
        color: #0f172a !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card .card-title {
        color: #f8fafc !important;
    }

    html[data-bs-theme="light"] .modern-kanban-card .card-customer,
    :root:not([data-bs-theme="dark"]) .modern-kanban-card .card-customer {
        color: #1d4ed8 !important;
        background: rgba(59, 130, 246, 0.08) !important;
        border: 1px solid rgba(59, 130, 246, 0.2) !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card .card-customer {
        color: #93c5fd !important;
        background: rgba(59, 130, 246, 0.2) !important;
        border: 1px solid rgba(59, 130, 246, 0.3) !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card .card-customer i {
        color: #93c5fd !important;
    }

    html[data-bs-theme="light"] .modern-kanban-card .value-section,
    :root:not([data-bs-theme="dark"]) .modern-kanban-card .value-section {
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card .value-section {
        background: rgba(15, 23, 42, 0.75) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: none !important;
    }

    html[data-bs-theme="light"] .modern-kanban-card .value-section small,
    :root:not([data-bs-theme="dark"]) .modern-kanban-card .value-section small {
        color: #64748b !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card .value-section small {
        color: #94a3b8 !important;
    }

    html[data-bs-theme="light"] .modern-kanban-card .value-amount,
    :root:not([data-bs-theme="dark"]) .modern-kanban-card .value-amount {
        color: #059669 !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card .value-amount {
        color: #34d399 !important;
    }

    html[data-bs-theme="light"] .modern-kanban-card .value-amount-small,
    :root:not([data-bs-theme="dark"]) .modern-kanban-card .value-amount-small {
        color: #475569 !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card .value-amount-small {
        color: #cbd5e1 !important;
    }

    html[data-bs-theme="light"] .modern-kanban-card .owner-name,
    :root:not([data-bs-theme="dark"]) .modern-kanban-card .owner-name {
        color: #1e293b !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card .owner-name {
        color: #f8fafc !important;
    }

    html[data-bs-theme="light"] .modern-kanban-card .text-muted,
    :root:not([data-bs-theme="dark"]) .modern-kanban-card .text-muted {
        color: #64748b !important;
    }

    html[data-bs-theme="dark"] .modern-kanban-card .text-muted,
    html[data-bs-theme="dark"] .modern-kanban-card small,
    html[data-bs-theme="dark"] .modern-kanban-card p {
        color: #cbd5e1 !important;
    }

    html[data-bs-theme="dark"] .page-title-box-modern {
        background: rgba(30, 41, 59, 0.7) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #f8fafc !important;
    }

    html[data-bs-theme="dark"] .page-title-modern {
        color: #f8fafc !important;
    }

    html[data-bs-theme="dark"] .page-subtitle-modern {
        color: #94a3b8 !important;
    }

    html[data-bs-theme="dark"] .kpi-card-modern {
        background: rgba(30, 41, 59, 0.85) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #f8fafc !important;
    }

    html[data-bs-theme="dark"] .kpi-card-modern .kpi-title {
        color: #cbd5e1 !important;
    }

    html[data-bs-theme="dark"] .kpi-card-modern .kpi-value {
        color: #f8fafc !important;
    }
</style>
