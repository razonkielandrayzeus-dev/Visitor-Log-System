<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Visitor Logs') }}
        </h2>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');
        .vlms-wrap { font-family: 'DM Sans', sans-serif; background: #f4f4f2; min-height: 100vh; padding: 2rem 1.5rem; }
        .card { background: #fff; border: 1px solid #e2e1dc; border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem; }
        .card-header-row { display: flex; align-items: center; justify-content: space-between; padding: 1.2rem 1.6rem; border-bottom: 1px solid #f0efe9; }
        .card-title { font-size: .72rem; font-weight: 500; letter-spacing: .08em; text-transform: uppercase; color: #9c9b96; }

        /* Filters */
        .filter-card { background: #fff; border: 1px solid #e2e1dc; border-radius: 12px; padding: 1.2rem 1.4rem; margin-bottom: 1.5rem; }
        .filter-grid { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: .75rem; align-items: end; }
        .filter-label { font-size: .7rem; font-weight: 500; letter-spacing: .06em; text-transform: uppercase; color: #9c9b96; margin-bottom: .4rem; }
        .filter-input {
            width: 100%;
            background: #f4f4f2;
            border: 1px solid #e2e1dc;
            border-radius: 8px;
            padding: .5rem .85rem;
            font-family: 'DM Sans', sans-serif;
            font-size: .85rem;
            color: #1c1c1a;
            outline: none;
            transition: border-color .15s;
        }
        .filter-input:focus { border-color: #9c9b96; }
        .btn-filter {
            background: #1c1c1a;
            color: #f4f4f2;
            font-family: 'DM Sans', sans-serif;
            font-size: .82rem;
            font-weight: 500;
            padding: .55rem 1.2rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background .15s;
            white-space: nowrap;
        }
        .btn-filter:hover { background: #3d3d3a; }
        .btn-reset {
            font-size: .78rem;
            font-weight: 500;
            color: #6b6b65;
            text-decoration: none;
            border: 1px solid #e2e1dc;
            padding: .5rem .85rem;
            border-radius: 8px;
            transition: all .15s;
            white-space: nowrap;
        }
        .btn-reset:hover { background: #f4f4f2; color: #1c1c1a; }

        /* Table */
        .vlms-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .vlms-table thead th { text-align: left; font-size: .68rem; font-weight: 500; letter-spacing: .07em; text-transform: uppercase; color: #a3a29c; padding: .6rem 1.4rem; background: #fafaf8; border-bottom: 1px solid #e9e8e3; }
        .vlms-table tbody tr { transition: background .12s; }
        .vlms-table tbody tr:hover { background: #fafaf8; }
        .vlms-table tbody td { padding: .9rem 1.4rem; color: #3d3d3a; border-bottom: 1px solid #f0efe9; }
        .vlms-table tbody tr:last-child td { border-bottom: none; }
        .badge-active { display: inline-flex; align-items: center; gap: .3rem; background: #dcfce7; color: #166534; padding: .2rem .65rem; border-radius: 999px; font-size: .7rem; font-weight: 500; }
        .badge-done { display: inline-flex; align-items: center; gap: .3rem; background: #f1f0eb; color: #6b6b65; padding: .2rem .65rem; border-radius: 999px; font-size: .7rem; font-weight: 500; }
        .mono { font-family: 'DM Mono', monospace; font-size: .8rem; color: #9c9b96; }
        .empty-state { text-align: center; padding: 3.5rem 1rem; color: #b0afa9; font-size: .9rem; }

        /* Checkout button */
        .btn-checkout { display: inline-flex; align-items: center; gap: .35rem; font-family: 'DM Sans', sans-serif; font-size: .78rem; font-weight: 500; color: #166534; background: #dcfce7; border: none; padding: .35rem .8rem; border-radius: 7px; cursor: pointer; transition: background .15s; }
        .btn-checkout:hover { background: #bbf7d0; }

        @media (max-width: 768px) {
            .filter-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="vlms-wrap">
        <div style="max-width:1100px; margin:0 auto;">

            {{-- Filter Card --}}
            <div class="filter-card">
                <form method="GET" action="{{ route('guard.visitors') }}">
                    <div class="filter-grid">
                        <div>
                            <div class="filter-label">Search name</div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="filter-input" placeholder="Visitor name...">
                        </div>
                        <div>
                            <div class="filter-label">Date</div>
                            <input type="date" name="date" value="{{ request('date') }}"
                                class="filter-input">
                        </div>
                        <div>
                            <div class="filter-label">Purpose</div>
                            <input type="text" name="purpose" value="{{ request('purpose') }}"
                                class="filter-input" placeholder="Purpose...">
                        </div>
                        <div style="display:flex; gap:.5rem;">
                            <button type="submit" class="btn-filter">Search</button>
                            <a href="{{ route('guard.visitors') }}" class="btn-reset">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Visitors Table --}}
            <div class="card">
                <div class="card-header-row">
                    <span class="card-title">My visitor logs</span>
                    <span class="mono">{{ $visitors->total() }} records</span>
                </div>

                @if($visitors->count() > 0)
                <table class="vlms-table">
                    <thead>
                        <tr>
                            <th>Visitor name</th>
                            <th>Purpose</th>
                            <th>Visiting</th>
                            <th>Time in</th>
                            <th>Time out</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visitors as $visitor)
                        <tr>
                            <td style="font-weight:500; color:#1c1c1a;">{{ $visitor->full_name }}</td>
                            <td style="color:#6b6b65;">{{ $visitor->purpose }}</td>
                            <td style="color:#6b6b65;">{{ $visitor->host_name }}</td>
                            <td class="mono">{{ $visitor->time_in->format('M d · h:i A') }}</td>
                            <td class="mono">
                                {{ $visitor->time_out ? $visitor->time_out->format('h:i A') : '—' }}
                            </td>
                            <td class="mono">
                                {{ $visitor->duration() ?? 'Ongoing' }}
                            </td>
                            <td>
                                @if($visitor->isActive())
                                    <span class="badge-active">● Active</span>
                                @else
                                    <span class="badge-done">✓ Done</span>
                                @endif
                            </td>
                            <td>
                                @if($visitor->isActive())
                                    <form method="POST" action="{{ route('visitors.checkout', $visitor) }}">
                                        @csrf
                                        <button type="submit" class="btn-checkout"
                                            onclick="return confirm('Check out {{ $visitor->full_name }}?')">
                                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            Check out
                                        </button>
                                    </form>
                                @else
                                    <span class="mono">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding: 1rem 1.4rem;">
                    {{ $visitors->links() }}
                </div>

                @else
                    <div class="empty-state">
                        @if(request()->hasAny(['search', 'date', 'purpose']))
                            No visitors match your search.
                        @else
                            You have not logged any visitors yet.
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>