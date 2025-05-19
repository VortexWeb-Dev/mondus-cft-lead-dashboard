<?php include_once 'includes/header.php'; ?>

<div class="container mx-auto mt-4 p-4">
    <!-- Header with Start/End Date Range -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-600">Mondus | CFT Leads Dashboard</h1>
        <div class="flex items-center space-x-2">
            <div class="flex flex-col items-center">
                <span class="text-sm text-center font-medium bg-gray-200 rounded-t-md w-36 px-2 py-1">Start</span>
                <input type="date" id="start-date" value="2023-01-24" class="bg-gray-400 text-center border border-gray-200 rounded-b-md w-36 px-2 py-1 text-sm">
            </div>
            <div class="flex flex-col items-center">
                <span class="text-sm text-center font-medium bg-gray-200 rounded-t-md w-36 px-2 py-1">End</span>
                <input type="date" id="end-date" value="2023-01-24" class="bg-gray-400 text-center border border-gray-200 rounded-b-md w-36 px-2 py-1 text-sm">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="leads-table" class="w-full text-sm border-collapse rounded-md overflow-hidden">
            <!-- Main Headers -->
            <thead>
                <tr class="bg-black text-white">
                    <th class="p-2 text-left" width="50px"></th>
                    <th class="p-2 text-left">Agent</th>
                    <th class="p-2 text-left">Branch</th>
                    <th class="p-2 text-center" colspan="6">PAID LEADS</th>
                    <th class="p-2 text-center" colspan="6">OTHER LEADS</th>
                    <th class="p-2 text-center" colspan="6">TOTAL LEADS<br />(ALL TIME)</th>
                    <th class="p-2 text-center" colspan="3">ZIWO</th>
                </tr>
                <!-- Filter Row -->
                <tr class="bg-gray-800 text-white">
                    <td colspan="3"></td>
                    <!-- Paid Leads Filter -->
                    <td colspan="6" class="p-1 text-center">
                        <div class="bg-gray-600 rounded flex items-center justify-between px-2 py-1 mx-6">
                            <select id="paid-leads-filter" class="bg-gray-600 text-white outline-none w-full">
                                <option value="all">ALL</option>
                                <option value="assigned">Assigned</option>
                                <option value="contacted">Contacted</option>
                                <option value="qualified">Qualified</option>
                                <option value="demo">Demo</option>
                                <option value="id">ID</option>
                                <option value="remaining">Remaining</option>
                            </select>
                        </div>
                    </td>
                    <!-- Other Leads Filter -->
                    <td colspan="6" class="p-1 text-center">
                        <div class="bg-gray-600 rounded flex items-center justify-between px-2 py-1 mx-6">
                            <select id="other-leads-filter" class="bg-gray-600 text-white outline-none w-full">
                                <option value="all">ALL</option>
                                <option value="assigned">Assigned</option>
                                <option value="contacted">Contacted</option>
                                <option value="qualified">Qualified</option>
                                <option value="demo">Demo</option>
                                <option value="id">ID</option>
                                <option value="remaining">Remaining</option>
                            </select>
                        </div>
                    </td>
                    <td colspan="9"></td>
                </tr>
                <!-- Column Headers -->
                <tr class="bg-gray-700 text-white text-xs">
                    <th class="border-r border-gray-600"></th>
                    <th class="border-r border-gray-600">Agent</th>
                    <th class="border-r border-gray-600">Branch</th>
                    <!-- Paid Leads Headers -->
                    <th class="border-r border-gray-600 p-1">Assigned</th>
                    <th class="border-r border-gray-600 p-1">Contacted</th>
                    <th class="border-r border-gray-600 p-1">Qualified</th>
                    <th class="border-r border-gray-600 p-1">Demo</th>
                    <th class="border-r border-gray-600 p-1">ID</th>
                    <th class="border-r border-gray-600 p-1">Remaining</th>
                    <!-- Other Leads Headers -->
                    <th class="border-r border-gray-600 p-1">Assigned</th>
                    <th class="border-r border-gray-600 p-1">Contacted</th>
                    <th class="border-r border-gray-600 p-1">Qualified</th>
                    <th class="border-r border-gray-600 p-1">Demo</th>
                    <th class="border-r border-gray-600 p-1">ID</th>
                    <th class="border-r border-gray-600 p-1">Remaining</th>
                    <!-- Total Leads Headers -->
                    <th class="border-r border-gray-600 p-1">Assigned</th>
                    <th class="border-r border-gray-600 p-1">Contacted</th>
                    <th class="border-r border-gray-600 p-1">Qualified</th>
                    <th class="border-r border-gray-600 p-1">Demo</th>
                    <th class="border-r border-gray-600 p-1">ID</th>
                    <th class="border-r border-gray-600 p-1">Remaining</th>
                    <!-- Ziwo Headers -->
                    <th class="border-r border-gray-600 p-1">Outbound</th>
                    <th class="border-r border-gray-600 p-1">Answered</th>
                    <th class="p-1">Paid</th>
                </tr>
            </thead>
            <tbody>
                <!-- Total Row -->
                <tr class="bg-gray-300">
                    <td class="border-r border-gray-400 p-2 font-bold">TOTAL</td>
                    <td class="border-r border-gray-400 p-2">
                        <div class="bg-gray-400 rounded flex items-center justify-between px-2 py-1">
                            <select id="agent-filter" class="bg-gray-400 text-black outline-none">
                                <option value="all">ALL</option>
                                <option value="asif">ASIF</option>
                                <option value="parth">PARTH</option>
                                <option value="rishi">RISHI</option>
                                <option value="tanya">TANYA</option>
                                <option value="ansh">ANSH</option>
                            </select>
                        </div>
                    </td>
                    <td class="border-r border-gray-400 p-2">
                        <div class="bg-gray-400 rounded flex items-center justify-between px-2 py-1">
                            <select id="branch-filter" class="bg-gray-400 text-black outline-none">
                                <option value="all">ALL</option>
                                <option value="rudra">RUDRA</option>
                                <option value="dehradun">DEHRADUN</option>
                                <option value="kb">KB</option>
                            </select>
                        </div>
                    </td>
                    <!-- Paid Leads -->
                    <td class="border-r border-gray-400 p-2 text-center paid-assigned">50</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-contacted">50</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-qualified">50</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-demo">50</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-id bg-green-200">50</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-remaining">50</td>
                    <!-- Other Leads -->
                    <td class="border-r border-gray-400 p-2 text-center other-assigned">50</td>
                    <td class="border-r border-gray-400 p-2 text-center other-contacted">50</td>
                    <td class="border-r border-gray-400 p-2 text-center other-qualified">50</td>
                    <td class="border-r border-gray-400 p-2 text-center other-demo">50</td>
                    <td class="border-r border-gray-400 p-2 text-center other-id bg-green-200">50</td>
                    <td class="border-r border-gray-400 p-2 text-center other-remaining">50</td>
                    <!-- Total Leads -->
                    <td class="border-r border-gray-400 p-2 text-center total-assigned">50</td>
                    <td class="border-r border-gray-400 p-2 text-center total-contacted">50</td>
                    <td class="border-r border-gray-400 p-2 text-center total-qualified">50</td>
                    <td class="border-r border-gray-400 p-2 text-center total-demo">50</td>
                    <td class="border-r border-gray-400 p-2 text-center total-id bg-green-200">50</td>
                    <td class="border-r border-gray-400 p-2 text-center total-remaining">50</td>
                    <!-- Ziwo -->
                    <td class="border-r border-gray-400 p-2 text-center ziwo-outbound">50</td>
                    <td class="border-r border-gray-400 p-2 text-center ziwo-answered">50</td>
                    <td class="p-2 text-center ziwo-paid">50</td>
                </tr>
                <!-- Agent Rows -->
                <tr class="bg-white agent-row" data-agent="asif" data-branch="rudra" data-date="2023-01-24">
                    <td class="border-r border-gray-300 p-2 font-medium">01</td>
                    <td class="border-r border-gray-300 p-2">ASIF</td>
                    <td class="border-r border-gray-300 p-2">RUDRA</td>
                    <!-- Paid Leads -->
                    <td class="border-r border-gray-300 p-2 text-center paid-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-remaining">50</td>
                    <!-- Other Leads -->
                    <td class="border-r border-gray-300 p-2 text-center other-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-remaining">50</td>
                    <!-- Total Leads -->
                    <td class="border-r border-gray-300 p-2 text-center total-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-remaining">50</td>
                    <!-- Ziwo -->
                    <td class="border-r border-gray-300 p-2 text-center ziwo-outbound">50</td>
                    <td class="border-r border-gray-300 p-2 text-center ziwo-answered">50</td>
                    <td class="p-2 text-center ziwo-paid">50</td>
                </tr>
                <tr class="bg-gray-100 agent-row" data-agent="parth" data-branch="rudra" data-date="2023-01-24">
                    <td class="border-r border-gray-300 p-2 font-medium">01</td>
                    <td class="border-r border-gray-300 p-2">PARTH</td>
                    <td class="border-r border-gray-300 p-2">RUDRA</td>
                    <!-- Paid Leads -->
                    <td class="border-r border-gray-300 p-2 text-center paid-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-remaining">50</td>
                    <!-- Other Leads -->
                    <td class="border-r border-gray-300 p-2 text-center other-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-remaining">50</td>
                    <!-- Total Leads -->
                    <td class="border-r border-gray-300 p-2 text-center total-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-remaining">50</td>
                    <!-- Ziwo -->
                    <td class="border-r border-gray-300 p-2 text-center ziwo-outbound">50</td>
                    <td class="border-r border-gray-300 p-2 text-center ziwo-answered">50</td>
                    <td class="p-2 text-center ziwo-paid">50</td>
                </tr>
                <tr class="bg-white agent-row" data-agent="rishi" data-branch="rudra" data-date="2023-01-24">
                    <td class="border-r border-gray-300 p-2 font-medium">01</td>
                    <td class="border-r border-gray-300 p-2">RISHI</td>
                    <td class="border-r border-gray-300 p-2">RUDRA</td>
                    <!-- Paid Leads -->
                    <td class="border-r border-gray-300 p-2 text-center paid-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-remaining">50</td>
                    <!-- Other Leads -->
                    <td class="border-r border-gray-300 p-2 text-center other-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-remaining">50</td>
                    <!-- Total Leads -->
                    <td class="border-r border-gray-300 p-2 text-center total-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-remaining">50</td>
                    <!-- Ziwo -->
                    <td class="border-r border-gray-300 p-2 text-center ziwo-outbound">50</td>
                    <td class="border-r border-gray-300 p-2 text-center ziwo-answered">50</td>
                    <td class="p-2 text-center ziwo-paid">50</td>
                </tr>
                <tr class="bg-gray-100 agent-row" data-agent="tanya" data-branch="dehradun" data-date="2023-01-24">
                    <td class="border-r border-gray-300 p-2 font-medium">01</td>
                    <td class="border-r border-gray-300 p-2">TANYA</td>
                    <td class="border-r border-gray-300 p-2">DEHRADUN</td>
                    <!-- Paid Leads -->
                    <td class="border-r border-gray-300 p-2 text-center paid-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-remaining">50</td>
                    <!-- Other Leads -->
                    <td class="border-r border-gray-300 p-2 text-center other-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-remaining">50</td>
                    <!-- Total Leads -->
                    <td class="border-r border-gray-300 p-2 text-center total-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-remaining">50</td>
                    <!-- Ziwo -->
                    <td class="border-r border-gray-300 p-2 text-center ziwo-outbound">50</td>
                    <td class="border-r border-gray-300 p-2 text-center ziwo-answered">50</td>
                    <td class="p-2 text-center ziwo-paid">50</td>
                </tr>
                <tr class="bg-white agent-row" data-agent="ansh" data-branch="kb" data-date="2023-01-24">
                    <td class="border-r border-gray-300 p-2 font-medium">01</td>
                    <td class="border-r border-gray-300 p-2">ANSH</td>
                    <td class="border-r border-gray-300 p-2">KB</td>
                    <!-- Paid Leads -->
                    <td class="border-r border-gray-300 p-2 text-center paid-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center paid-remaining">50</td>
                    <!-- Other Leads -->
                    <td class="border-r border-gray-300 p-2 text-center other-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center other-remaining">50</td>
                    <!-- Total Leads -->
                    <td class="border-r border-gray-300 p-2 text-center total-assigned">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-contacted">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-qualified">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-demo">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-id bg-green-200">50</td>
                    <td class="border-r border-gray-300 p-2 text-center total-remaining">50</td>
                    <!-- Ziwo -->
                    <td class="border-r border-gray-300 p-2 text-center ziwo-outbound">50</td>
                    <td class="border-r border-gray-300 p-2 text-center ziwo-answered">50</td>
                    <td class="p-2 text-center ziwo-paid">50</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="mt-4 text-center text-gray-600 mt-6">
        <p class="text-sm">© <?= date('Y'); ?> Mondus Properties. All rights reserved.</p>
        <p class="text-xs">Developed by <a href="http://vortexweb.org/" target="_blank" class="text-blue-500 hover:underline">VortexWeb</a></p>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>