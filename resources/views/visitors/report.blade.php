<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Date Selection -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('visitors.report') }}" class="flex gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Select Date</label>
                        <input type="date" name="date" value="{{ $date }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Generate Report
                    </button>
                </form>
            </div>

            @if(isset($totalVisitors))
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Total Visitors</div>
                        <div class="text-3xl font-bold text-indigo-600">{{ $totalVisitors }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Active (Not Checked Out)</div>
                        <div class="text-3xl font-bold text-green-600">{{ $activeVisitors }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Completed Visits</div>
                        <div class="text-3xl font-bold text-blue-600">{{ $completedVisits }}</div>
                    </div>
                </div>

                <!-- Email Report -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                    <h3 class="text-lg font-semibold mb-4">Email This Report</h3>
                    <form method="POST" action="{{ route('visitors.send-report') }}" class="flex gap-4 items-end">
                        @csrf
                        <input type="hidden" name="date" value="{{ $date }}">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="admin@example.com">
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Send Report
                        </button>
                    </form>
                </div>

                <!-- Detailed Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Visitor Details for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h3>
                        
                        @if($visitors->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Host</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($visitors as $visitor)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $visitor->full_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $visitor->purpose }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $visitor->host_name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $visitor->time_in->format('h:i A') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                                    {{ $visitor->time_out ? $visitor->time_out->format('h:i A') : '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                                    {{ $visitor->duration() ?? 'Ongoing' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No visitors recorded for this date.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>