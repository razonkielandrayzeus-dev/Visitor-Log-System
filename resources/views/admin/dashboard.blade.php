<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-200/60 p-6 transition-all hover:shadow-md hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="text-slate-500 text-sm font-medium">Today's Visitors</div>
                        <div class="p-2 bg-indigo-50 rounded-lg text-indigo-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 mt-4">{{ $todayVisitors }}</div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-200/60 p-6 transition-all hover:shadow-md hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="text-slate-500 text-sm font-medium">Currently Inside</div>
                        <div class="p-2 bg-emerald-50 rounded-lg text-emerald-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 mt-4">{{ $activeVisitors }}</div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-200/60 p-6 transition-all hover:shadow-md hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="text-slate-500 text-sm font-medium">Total Visitors</div>
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-800 mt-4">{{ $totalVisitors }}</div>
                </div>
            </div>

            <!-- Visitor Trend Chart -->
            <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-200/60 mb-8 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-6">Visitor Trend (Last 7 Days)</h3>
                <canvas id="visitorChart" height="100"></canvas>
            </div>

            <!-- Recent Visitors -->
            <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="p-6 bg-white border-b border-slate-100">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-semibold text-slate-800">Recent Visitors</h3>
                        <a href="{{ route('visitors.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">View All &rarr;</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Purpose</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Host</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Time In</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                @foreach($recentVisitors as $visitor)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">{{ $visitor->full_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $visitor->purpose }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $visitor->host_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $visitor->time_in->format('M d, Y h:i A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($visitor->isActive())
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-50 text-emerald-600 ring-1 ring-inset ring-emerald-500/10">Active</span>
                                            @else
                                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-50 text-slate-600 ring-1 ring-inset ring-slate-500/10">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('visitorChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! $chartLabels !!},
                datasets: [{
                    label: 'Visitors',
                    data: {!! $chartCounts !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</x-app-layout>