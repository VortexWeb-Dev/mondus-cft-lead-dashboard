<?php include_once 'includes/header.php'; ?>

<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">CFT Leads Dashboard</h1>
                <p class="text-sm text-gray-600 mt-1">Real-time lead tracking and performance analytics</p>
            </div>

            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">From</label>
                    <input type="date" id="start-date" value="<?= date('Y-m-d'); ?>"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">To</label>
                    <input type="date" id="end-date" value="<?= date('Y-m-d'); ?>"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500 focus:border-gray-500">
                </div>
                <button id="refresh-btn" class="bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 transition-colors">
                    Refresh
                </button>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Agent</label>
                    <select id="agent-filter" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500" multiple>
                        <option value="all" selected>All Agents</option>
                        <option value="asif">Asif</option>
                        <option value="parth">Parth</option>
                        <option value="rishi">Rishi</option>
                        <option value="tanya">Tanya</option>
                        <option value="ansh">Ansh</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Branch</label>
                    <select id="branch-filter" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500" multiple>
                        <option value="all" selected>All Branches</option>
                        <option value="rudra">Rudra</option>
                        <option value="dehradun">Dehradun</option>
                        <option value="kb">KB</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Paid Leads</label>
                    <select id="paid-leads-filter" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500" multiple>
                        <option value="all" selected>All Metrics</option>
                        <option value="assigned">Assigned</option>
                        <option value="contacted">Contacted</option>
                        <option value="qualified">Qualified</option>
                        <option value="demo">Demo</option>
                        <option value="id">ID</option>
                        <option value="remaining">Remaining</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Other Leads</label>
                    <select id="other-leads-filter" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-500" multiple>
                        <option value="all" selected>All Metrics</option>
                        <option value="assigned">Assigned</option>
                        <option value="contacted">Contacted</option>
                        <option value="qualified">Qualified</option>
                        <option value="demo">Demo</option>
                        <option value="id">ID</option>
                        <option value="remaining">Remaining</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading-indicator" class="hidden fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3 shadow-lg">
                <div class="animate-spin rounded-full h-5 w-5 border-2 border-gray-300 border-t-gray-900"></div>
                <span class="text-gray-700 text-sm">Loading...</span>
            </div>
        </div>

        <!-- Main Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table id="leads-table" class="w-full text-sm">
                    <thead>
                        <!-- Main Headers -->
                        <tr class="bg-gray-900 text-white">
                            <th class="px-4 py-3 text-left border-r border-gray-700" width="50px">#</th>
                            <th class="px-4 py-3 text-left border-r border-gray-700">Agent</th>
                            <th class="px-4 py-3 text-left border-r border-gray-700">Branch</th>
                            <th class="px-4 py-3 text-center border-r border-gray-700" colspan="6">PAID LEADS</th>
                            <th class="px-4 py-3 text-center border-r border-gray-700" colspan="6">OTHER LEADS</th>
                            <th class="px-4 py-3 text-center border-r border-gray-700" colspan="6">TOTAL LEADS (ALL TIME)</th>
                            <th class="px-4 py-3 text-center" colspan="3">ZIWO</th>
                        </tr>

                        <!-- Column Headers -->
                        <tr class="bg-gray-800 text-white text-xs">
                            <th class="border-r border-gray-600"></th>
                            <th class="border-r border-gray-600 px-3 py-2">Agent</th>
                            <th class="border-r border-gray-600 px-3 py-2">Branch</th>

                            <!-- Paid Leads Headers -->
                            <th class="border-r border-gray-600 px-2 py-2">Assigned</th>
                            <th class="border-r border-gray-600 px-2 py-2">Contacted</th>
                            <th class="border-r border-gray-600 px-2 py-2">Qualified</th>
                            <th class="border-r border-gray-600 px-2 py-2">Demo</th>
                            <th class="border-r border-gray-600 px-2 py-2">ID</th>
                            <th class="border-r border-gray-600 px-2 py-2">Remaining</th>

                            <!-- Other Leads Headers -->
                            <th class="border-r border-gray-600 px-2 py-2">Assigned</th>
                            <th class="border-r border-gray-600 px-2 py-2">Contacted</th>
                            <th class="border-r border-gray-600 px-2 py-2">Qualified</th>
                            <th class="border-r border-gray-600 px-2 py-2">Demo</th>
                            <th class="border-r border-gray-600 px-2 py-2">ID</th>
                            <th class="border-r border-gray-600 px-2 py-2">Remaining</th>

                            <!-- Total Leads Headers -->
                            <th class="border-r border-gray-600 px-2 py-2">Assigned</th>
                            <th class="border-r border-gray-600 px-2 py-2">Contacted</th>
                            <th class="border-r border-gray-600 px-2 py-2">Qualified</th>
                            <th class="border-r border-gray-600 px-2 py-2">Demo</th>
                            <th class="border-r border-gray-600 px-2 py-2">ID</th>
                            <th class="border-r border-gray-600 px-2 py-2">Remaining</th>

                            <!-- Ziwo Headers -->
                            <th class="border-r border-gray-600 px-2 py-2">Outbound</th>
                            <th class="border-r border-gray-600 px-2 py-2">Answered</th>
                            <th class="px-2 py-2">Paid</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Total Row -->
                        <tr class="bg-gray-100 border-b border-gray-200 font-medium">
                            <td class="px-4 py-3 border-r border-gray-300">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-600 text-white text-xs rounded">Σ</span>
                            </td>
                            <td class="px-4 py-3 border-r border-gray-300 font-semibold">TOTAL</td>
                            <td class="px-4 py-3 border-r border-gray-300">All Branches</td>

                            <!-- Paid Leads Totals -->
                            <td class="px-3 py-3 text-center border-r border-gray-300 paid-assigned">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 paid-contacted">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 paid-qualified">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 paid-demo">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 paid-id font-semibold">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 paid-remaining">0</td>

                            <!-- Other Leads Totals -->
                            <td class="px-3 py-3 text-center border-r border-gray-300 other-assigned">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 other-contacted">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 other-qualified">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 other-demo">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 other-id font-semibold">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 other-remaining">0</td>

                            <!-- Total Leads Totals -->
                            <td class="px-3 py-3 text-center border-r border-gray-300 total-assigned">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 total-contacted">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 total-qualified">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 total-demo">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 total-id font-semibold">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 total-remaining">0</td>

                            <!-- Ziwo Totals -->
                            <td class="px-3 py-3 text-center border-r border-gray-300 ziwo-outbound">0</td>
                            <td class="px-3 py-3 text-center border-r border-gray-300 ziwo-answered">0</td>
                            <td class="px-3 py-3 text-center ziwo-paid">0</td>
                        </tr>

                        <!-- Agent Rows -->
                        <tr class="agent-row" data-agent="loading" data-branch="loading">
                            <td class="px-4 py-8 text-center text-gray-500" colspan="24">
                                <div class="flex items-center justify-center space-x-2">
                                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 border-t-gray-600"></div>
                                    <span>Loading agent data...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Paid Leads</p>
                        <p class="text-2xl font-semibold text-gray-900" id="total-paid-leads">0</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Other Leads</p>
                        <p class="text-2xl font-semibold text-gray-900" id="total-other-leads">0</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total IDs Created</p>
                        <p class="text-2xl font-semibold text-gray-900" id="total-ids">0</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Calls</p>
                        <p class="text-2xl font-semibold text-gray-900" id="total-calls">0</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 pt-8 border-t border-gray-200 text-center text-gray-500">
            <p class="text-sm">© <?= date('Y'); ?> Mondus Properties. All rights reserved.</p>
            <p class="text-xs mt-1">
                Developed by <a href="http://vortexweb.org/" target="_blank" class="text-gray-700 hover:text-gray-900">VortexWeb</a> |
                Last updated: <span id="last-updated"><?php date_default_timezone_set('Asia/Dubai');
                                                        echo date('Y-m-d H:i:s'); ?></span>
            </p>
        </div>
    </div>
</div>

<style>
    /* Minimal animations */
    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .slide-up {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            transform: translateY(10px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Table hover effects */
    .agent-row:hover {
        background-color: #f9fafb;
    }

    /* Highlight for filtered cells */
    .highlight-paid {
        background-color: #f3f4f6 !important;
        border-left: 3px solid #374151;
    }

    .highlight-other {
        background-color: #f9fafb !important;
        border-left: 3px solid #6b7280;
    }

    /* Custom scrollbar */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Multiple select styling */
    select[multiple] {
        min-height: 38px;
    }

    select[multiple] option:checked {
        background-color: #374151;
        color: white;
    }
</style>

<?php include_once 'includes/footer.php'; ?>