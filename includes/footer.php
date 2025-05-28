<script>
    new TomSelect('#agent-filter');
    new TomSelect('#branch-filter');
    new TomSelect('#paid-leads-filter');
    new TomSelect('#other-leads-filter');

    document.addEventListener('DOMContentLoaded', function() {
        let currentData = null;

        // Utility functions
        function showLoading() {
            document.getElementById('loading-indicator').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading-indicator').classList.add('hidden');
        }

        function showNotification(message, type = 'info') {
            // Simple notification without flashy colors
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 bg-white border border-gray-300 rounded-lg shadow-lg p-4 transition-all duration-300 transform translate-x-full`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 rounded-full ${type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500'}"></div>
                    <span class="text-sm text-gray-700">${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => notification.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => document.body.removeChild(notification), 300);
            }, 3000);
        }

        // Data fetching
        async function fetchData(page = 1) {
            showLoading();
            try {
                const startDate = document.getElementById('start-date').value;
                const endDate = document.getElementById('end-date').value;
                const response = await fetch(`data.php?page=${page}&start_date=${startDate}&end_date=${endDate}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                hideLoading();
                return data;
            } catch (error) {
                console.error('Error fetching data:', error);
                hideLoading();
                showNotification('Failed to load data', 'error');
                return null;
            }
        }

        // Table population
        function populateTable(data) {
            const tableBody = document.querySelector('#leads-table tbody');
            const totalRow = tableBody.querySelector('tr.bg-gray-100');

            // Clear existing agent rows
            const agentRows = tableBody.querySelectorAll('.agent-row');
            agentRows.forEach(row => row.remove());

            // Add agent rows
            data.agents.forEach((agent, index) => {
                const row = document.createElement('tr');
                row.className = `agent-row border-b border-gray-100 ${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'}`;
                row.setAttribute('data-agent', agent.name.split(' ')[0].toLowerCase());
                row.setAttribute('data-branch', agent.branch.toLowerCase());
                row.setAttribute('data-date', agent.date || '');

                row.innerHTML = `
                    <td class="px-4 py-3 border-r border-gray-200 text-center text-gray-600 font-medium">${index + 1}</td>
                    <td class="px-4 py-3 border-r border-gray-200 font-medium text-gray-900">${agent.name}</td>
                    <td class="px-4 py-3 border-r border-gray-200 text-gray-700">${agent.branch}</td>
                    
                    <!-- Paid Leads -->
                    <td class="px-3 py-3 text-center border-r border-gray-200 paid-assigned">${agent.paid.assigned}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 paid-contacted">${agent.paid.contacted}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 paid-qualified">${agent.paid.qualified}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 paid-demo">${agent.paid.demo}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 paid-id font-semibold">${agent.paid.id}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 paid-remaining">${agent.paid.remaining}</td>
                    
                    <!-- Other Leads -->
                    <td class="px-3 py-3 text-center border-r border-gray-200 other-assigned">${agent.other.assigned}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 other-contacted">${agent.other.contacted}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 other-qualified">${agent.other.qualified}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 other-demo">${agent.other.demo}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 other-id font-semibold">${agent.other.id}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 other-remaining">${agent.other.remaining}</td>
                    
                    <!-- Total Leads -->
                    <td class="px-3 py-3 text-center border-r border-gray-200 total-assigned">${agent.total.assigned}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 total-contacted">${agent.total.contacted}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 total-qualified">${agent.total.qualified}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 total-demo">${agent.total.demo}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 total-id font-semibold">${agent.total.id}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 total-remaining">${agent.total.remaining}</td>
                    
                    <!-- Ziwo -->
                    <td class="px-3 py-3 text-center border-r border-gray-200 ziwo-outbound">${agent.ziwo.outbound}</td>
                    <td class="px-3 py-3 text-center border-r border-gray-200 ziwo-answered">${agent.ziwo.answered}</td>
                    <td class="px-3 py-3 text-center ziwo-paid">${agent.ziwo.paid}</td>
                `;

                // Add fade-in effect
                row.style.opacity = '0';
                tableBody.appendChild(row);

                setTimeout(() => {
                    row.classList.add('fade-in');
                    row.style.opacity = '1';
                }, index * 50);
            });

            updateTotalsRow(data.totals);
            updateSummaryCards(data.totals);
        }

        // Update totals with smooth number transitions
        function updateTotalsRow(totals) {
            const totalRow = document.querySelector('#leads-table tbody tr.bg-gray-100');

            const animateNumber = (element, newValue) => {
                const currentValue = parseInt(element.textContent) || 0;
                const increment = (newValue - currentValue) / 10;
                let current = currentValue;

                const timer = setInterval(() => {
                    current += increment;
                    if ((increment > 0 && current >= newValue) || (increment < 0 && current <= newValue)) {
                        current = newValue;
                        clearInterval(timer);
                    }
                    element.textContent = Math.round(current);
                }, 30);
            };

            // Update all totals
            Object.keys(totals).forEach(category => {
                Object.keys(totals[category]).forEach(metric => {
                    const element = totalRow.querySelector(`.${category}-${metric}`);
                    if (element) {
                        animateNumber(element, totals[category][metric]);
                    }
                });
            });
        }

        // Update summary cards
        function updateSummaryCards(totals) {
            const animateNumber = (elementId, newValue) => {
                const element = document.getElementById(elementId);
                const currentValue = parseInt(element.textContent) || 0;
                const increment = (newValue - currentValue) / 10;
                let current = currentValue;

                const timer = setInterval(() => {
                    current += increment;
                    if ((increment > 0 && current >= newValue) || (increment < 0 && current <= newValue)) {
                        current = newValue;
                        clearInterval(timer);
                    }
                    element.textContent = Math.round(current);
                }, 30);
            };

            animateNumber('total-paid-leads', totals.paid.assigned);
            animateNumber('total-other-leads', totals.other.assigned);
            animateNumber('total-ids', totals.paid.id + totals.other.id);
            animateNumber('total-calls', totals.ziwo.outbound);
        }

        // Filter system
        function setupFilters() {
            const filters = {
                startDate: document.getElementById('start-date'),
                endDate: document.getElementById('end-date'),
                agent: document.getElementById('agent-filter'),
                branch: document.getElementById('branch-filter'),
                paidLeads: document.getElementById('paid-leads-filter'),
                otherLeads: document.getElementById('other-leads-filter')
            };

            const refreshBtn = document.getElementById('refresh-btn');

            function getSelectedValues(selectElement) {
                const selected = Array.from(selectElement.selectedOptions).map(option => option.value);
                return selected.includes('all') ? ['all'] : selected;
            }

            function applyFilters() {
                const selectedAgents = getSelectedValues(filters.agent);
                const selectedBranches = getSelectedValues(filters.branch);
                const selectedPaidMetrics = getSelectedValues(filters.paidLeads);
                const selectedOtherMetrics = getSelectedValues(filters.otherLeads);

                const rows = document.querySelectorAll('#leads-table tbody tr.agent-row');

                // Clear highlights
                document.querySelectorAll('.highlight-paid, .highlight-other').forEach(cell => {
                    cell.classList.remove('highlight-paid', 'highlight-other');
                });

                let visibleCount = 0;

                rows.forEach(row => {
                    const rowAgent = row.getAttribute('data-agent');
                    const rowBranch = row.getAttribute('data-branch');

                    const agentMatch = selectedAgents.includes('all') || selectedAgents.includes(rowAgent);

                    let branchMatch = selectedBranches.includes('all');
                    if (!branchMatch && rowBranch) {
                        const rowBranches = rowBranch.split(',').map(b => b.trim().toLowerCase());
                        branchMatch = selectedBranches.some(branch =>
                            branch === 'all' || rowBranches.includes(branch.toLowerCase())
                        );
                    }

                    const shouldShow = agentMatch && branchMatch;

                    if (shouldShow) {
                        row.style.display = '';
                        visibleCount++;

                        // Highlight filtered metrics
                        if (!selectedPaidMetrics.includes('all')) {
                            selectedPaidMetrics.forEach(metric => {
                                const cells = row.querySelectorAll(`.paid-${metric}`);
                                cells.forEach(cell => cell.classList.add('highlight-paid'));
                            });
                        }

                        if (!selectedOtherMetrics.includes('all')) {
                            selectedOtherMetrics.forEach(metric => {
                                const cells = row.querySelectorAll(`.other-${metric}`);
                                cells.forEach(cell => cell.classList.add('highlight-other'));
                            });
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('last-updated').textContent = new Date().toLocaleString();
                showNotification(`Showing ${visibleCount} agents`);
            }

            // Event listeners
            Object.values(filters).forEach(filter => {
                filter.addEventListener('change', applyFilters);

                if (filter.tagName === 'SELECT' && filter.hasAttribute('multiple')) {
                    filter.addEventListener('click', function(e) {
                        if (e.target.value === 'all') {
                            Array.from(this.options).forEach(option => {
                                option.selected = option.value === 'all';
                            });
                        } else {
                            const allOption = this.querySelector('option[value="all"]');
                            if (allOption) allOption.selected = false;
                        }
                    });
                }
            });

            // Refresh button
            refreshBtn.addEventListener('click', async function() {
                this.textContent = 'Loading...';
                this.disabled = true;
                await initDashboard();
                this.textContent = 'Refresh';
                this.disabled = false;
                showNotification('Dashboard refreshed');
            });
        }

        // Initialize dashboard
        async function initDashboard() {
            try {
                const data = await fetchData(1);
                if (data) {
                    currentData = data;
                    populateTable(data);
                    setupFilters();
                }
            } catch (error) {
                console.error('Error initializing dashboard:', error);
                showNotification('Failed to initialize dashboard', 'error');
            }
        }

        // Auto-refresh every 5 minutes
        setInterval(async () => {
            await initDashboard();
            showNotification('Auto-refreshed');
        }, 300000);

        // Initialize
        initDashboard();
    });
</script>
</body>

</html>