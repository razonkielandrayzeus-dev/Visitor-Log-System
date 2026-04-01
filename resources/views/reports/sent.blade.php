<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sent Reports') }}
        </h2>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');
        .vlms-wrap { font-family: 'DM Sans', sans-serif; background: #f4f4f2; min-height: 100vh; padding: 2rem 1.5rem; }
        .card { background: #fff; border: 1px solid #e2e1dc; border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem; }
        .card-header-row { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 1.6rem; border-bottom: 1px solid #f0efe9; }
        .card-title { font-size: .72rem; font-weight: 500; letter-spacing: .08em; text-transform: uppercase; color: #9c9b96; }
        .vlms-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .vlms-table thead th { text-align: left; font-size: .68rem; font-weight: 500; letter-spacing: .07em; text-transform: uppercase; color: #a3a29c; padding: .6rem 1.4rem; background: #fafaf8; border-bottom: 1px solid #e9e8e3; }
        .vlms-table tbody tr:hover { background: #fafaf8; }
        .vlms-table tbody td { padding: .9rem 1.4rem; color: #3d3d3a; border-bottom: 1px solid #f0efe9; }
        .vlms-table tbody tr:last-child td { border-bottom: none; }
        .badge-sent { display: inline-flex; align-items: center; gap: .3rem; background: #dcfce7; color: #166534; padding: .2rem .65rem; border-radius: 999px; font-size: .7rem; font-weight: 500; }
        .badge-failed { display: inline-flex; align-items: center; gap: .3rem; background: #fee2e2; color: #991b1b; padding: .2rem .65rem; border-radius: 999px; font-size: .7rem; font-weight: 500; }
        .mono { font-family: 'DM Mono', monospace; font-size: .8rem; color: #9c9b96; }
        .empty-state { text-align: center; padding: 3.5rem 1rem; color: #b0afa9; font-size: .9rem; }
    </style>

    <div class="vlms-wrap">
        <div style="max-width:1100px; margin:0 auto;">
            <div class="card">
                <div class="card-header-row">
                    <span class="card-title">Sent reports history</span>
                    <span class="mono">{{ $reports->total() }} total</span>
                </div>

                @if($reports->count() > 0)
                <table class="vlms-table">
                    <thead>
                        <tr>
                            <th>Sent at</th>
                            <th>Sent by</th>
                            <th>Recipient</th>
                            <th>Report date</th>
                            <th>Total</th>
                            <th>Completed</th>
                            <th>Active</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td class="mono">{{ $report->created_at->format('M d, Y h:i A') }}</td>
                            <td style="font-weight:500; color:#1c1c1a;">
                                {{ $report->sender->name }}
                                <span style="font-size:.7rem; background:#f1f0eb; color:#6b6b65; padding:.1rem .5rem; border-radius:999px; margin-left:.3rem;">
                                    {{ ucfirst($report->sender->role) }}
                                </span>
                            </td>
                            <td style="color:#6b6b65;">{{ $report->recipient_email }}</td>
                            <td class="mono">{{ $report->report_date->format('M d, Y') }}</td>
                            <td class="mono">{{ $report->total_visitors }}</td>
                            <td class="mono">{{ $report->completed_visits }}</td>
                            <td class="mono">{{ $report->active_visitors }}</td>
                            <td>
                                @if($report->status === 'sent')
                                    <span class="badge-sent">✓ Sent</span>
                                @else
                                    <span class="badge-failed">✕ Failed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding: 1rem 1.4rem;">
                    {{ $reports->links() }}
                </div>

                @else
                    <div class="empty-state">No reports have been sent yet.</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>