<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Guard Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Today's Visitors</div>
                    <div class="text-3xl font-bold text-indigo-600">{{ $todayCount }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm">Currently Inside</div>
                    <div class="text-3xl font-bold text-green-600">{{ $activeCount }}</div>
                </div>
            </div>

            <!-- Quick Action -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <a href="{{ route('visitors.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    + Log New Visitor
                </a>
            </div>

            <!-- Active Visitors -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Active Visitors (Not Checked Out)</h3>
                        <!-- LIVE SEARCH INPUT -->
                        <input
                            type="text"
                            id="visitorSearch"
                            placeholder="Search visitor..."
                            class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 w-56"
                            onkeyup="filterVisitors()"
                        >
                    </div>

                    @if($activeVisitors->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="visitorTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Host</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="visitorTableBody">
                                    @foreach($activeVisitors as $visitor)
                                        <tr class="visitor-row">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $visitor->full_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $visitor->purpose }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $visitor->host_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $visitor->time_in->format('h:i A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form method="POST" action="{{ route('visitors.checkout', $visitor) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 font-semibold" onclick="return confirm('Check out this visitor?')">
                                                        Check Out
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- No results message -->
                            <div id="noResults" class="hidden text-center text-gray-400 py-6">
                                No visitors match your search.
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No active visitors.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Live Search Script -->
    <script>
        function filterVisitors() {
            const input = document.getElementById('visitorSearch').value.toLowerCase();
            const rows = document.querySelectorAll('.visitor-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(input)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
        }
    </script>
</x-app-layout>