<?php include_once 'includes/header.php'; ?>

<div class="container mx-auto mt-4 p-4">
    <!-- Header with Start/End Date Range -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-600">Mondus | CFT Leads Dashboard</h1>
        <div class="flex items-center space-x-2">
            <div class="flex flex-col items-center">
                <span class="text-sm text-center font-medium bg-gray-200 rounded-t-md w-36 px-2 py-1">Start</span>
                <input type="date" id="start-date" value="<?= date('Y-m-d'); ?>" class="bg-gray-400 text-center border border-gray-200 rounded-b-md w-36 px-2 py-1 text-sm">
            </div>
            <div class="flex flex-col items-center">
                <span class="text-sm text-center font-medium bg-gray-200 rounded-t-md w-36 px-2 py-1">End</span>
                <input type="date" id="end-date" value="<?= date('Y-m-d'); ?>" class="bg-gray-400 text-center border border-gray-200 rounded-b-md w-36 px-2 py-1 text-sm">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="leads-table" class="w-full text-sm border-collapse rounded-md overflow-hidden">
            <!-- Main Headers -->
            <thead>
                <!-- Main Headers -->
                <tr class="bg-black text-white">
                    <th class="p-2 text-left group-agent" width="50px"></th>
                    <th class="p-2 text-left group-agent">Agent</th>
                    <th class="p-2 text-left group-branch">Branch</th>
                    <th class="p-2 text-center group-paid" colspan="6">PAID LEADS</th>
                    <th class="p-2 text-center group-other" colspan="6">OTHER LEADS</th>
                    <th class="p-2 text-center group-total" colspan="6">TOTAL LEADS<br />(ALL TIME)</th>
                    <th class="p-2 text-center group-ziwo" colspan="3">ZIWO</th>
                </tr>
                <!-- Filter Row -->
                <tr class="bg-gray-800 text-white">
                    <td colspan="3" class="group-agent group-branch"></td>
                    <!-- Paid Leads Filter -->
                    <td colspan="6" class="p-1 text-center group-paid">
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
                    <td colspan="6" class="p-1 text-center group-other">
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
                    <td colspan="9" class="group-total group-ziwo"></td>
                </tr>
                <!-- Column Headers -->
                <tr class="bg-gray-700 text-white text-xs">
                    <th class="border-r border-gray-600 group-agent"></th>
                    <th class="border-r border-gray-600 group-agent">Agent</th>
                    <th class="border-r border-gray-600 group-branch">Branch</th>
                    <!-- Paid Leads Headers -->
                    <th class="border-r border-gray-600 p-1 group-paid">Assigned</th>
                    <th class="border-r border-gray-600 p-1 group-paid">Contacted</th>
                    <th class="border-r border-gray-600 p-1 group-paid">Qualified</th>
                    <th class="border-r border-gray-600 p-1 group-paid">Demo</th>
                    <th class="border-r border-gray-600 p-1 group-paid">ID</th>
                    <th class="border-r border-gray-600 p-1 group-paid">Remaining</th>
                    <!-- Other Leads Headers -->
                    <th class="border-r border-gray-600 p-1 group-other">Assigned</th>
                    <th class="border-r border-gray-600 p-1 group-other">Contacted</th>
                    <th class="border-r border-gray-600 p-1 group-other">Qualified</th>
                    <th class="border-r border-gray-600 p-1 group-other">Demo</th>
                    <th class="border-r border-gray-600 p-1 group-other">ID</th>
                    <th class="border-r border-gray-600 p-1 group-other">Remaining</th>
                    <!-- Total Leads Headers -->
                    <th class="border-r border-gray-600 p-1 group-total">Assigned</th>
                    <th class="border-r border-gray-600 p-1 group-total">Contacted</th>
                    <th class="border-r border-gray-600 p-1 group-total">Qualified</th>
                    <th class="border-r border-gray-600 p-1 group-total">Demo</th>
                    <th class="border-r border-gray-600 p-1 group-total">ID</th>
                    <th class="border-r border-gray-600 p-1 group-total">Remaining</th>
                    <!-- Ziwo Headers -->
                    <th class="border-r border-gray-600 p-1 group-ziwo">Outbound</th>
                    <th class="border-r border-gray-600 p-1 group-ziwo">Answered</th>
                    <th class="p-1 group-ziwo">Paid</th>
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
                    <td class="border-r border-gray-400 p-2 text-center paid-assigned">0</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-contacted">0</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-qualified">0</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-demo">0</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-id bg-green-200">0</td>
                    <td class="border-r border-gray-400 p-2 text-center paid-remaining">0</td>
                    <!-- Other Leads -->
                    <td class="border-r border-gray-400 p-2 text-center other-assigned">0</td>
                    <td class="border-r border-gray-400 p-2 text-center other-contacted">0</td>
                    <td class="border-r border-gray-400 p-2 text-center other-qualified">0</td>
                    <td class="border-r border-gray-400 p-2 text-center other-demo">0</td>
                    <td class="border-r border-gray-400 p-2 text-center other-id bg-green-200">0</td>
                    <td class="border-r border-gray-400 p-2 text-center other-remaining">0</td>
                    <!-- Total Leads -->
                    <td class="border-r border-gray-400 p-2 text-center total-assigned">0</td>
                    <td class="border-r border-gray-400 p-2 text-center total-contacted">0</td>
                    <td class="border-r border-gray-400 p-2 text-center total-qualified">0</td>
                    <td class="border-r border-gray-400 p-2 text-center total-demo">0</td>
                    <td class="border-r border-gray-400 p-2 text-center total-id bg-green-200">0</td>
                    <td class="border-r border-gray-400 p-2 text-center total-remaining">0</td>
                    <!-- Ziwo -->
                    <td class="border-r border-gray-400 p-2 text-center ziwo-outbound">0</td>
                    <td class="border-r border-gray-400 p-2 text-center ziwo-answered">0</td>
                    <td class="p-2 text-center ziwo-paid">0</td>
                </tr>
                <!-- Agent Rows -->
                <tr class="bg-white agent-row" data-agent="asif" data-branch="rudra" data-date="2023-01-24">
                    <td class="border-r border-gray-300 p-2 font-medium text-center" colspan="24">Loading...</td>

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