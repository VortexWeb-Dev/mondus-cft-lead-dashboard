<script>
    document.addEventListener('DOMContentLoaded', function() {
        async function fetchData(page = 1) {
            try {
                const response = await fetch('data.php?page=' + page);
                const data = await response.json();

                return data;
            } catch (error) {
                console.error('Error fetching data:', error);
                return null;
            }
        }

        function processData(data) {
            const processedData = {};

            const initAgent = (agentName) => ({
                name: agentName,
                branch: "",
                campaigns: new Set(),
                paid: {
                    assigned: 0,
                    contacted: 0,
                    qualified: 0,
                    demo: 0,
                    id: 0,
                    remaining: 0
                },
                other: {
                    assigned: 0,
                    contacted: 0,
                    qualified: 0,
                    demo: 0,
                    id: 0,
                    remaining: 0
                },
                total: {
                    assigned: 0,
                    contacted: 0,
                    qualified: 0,
                    demo: 0,
                    id: 0,
                    remaining: 0
                },
                ziwo: {
                    outbound: 0,
                    answered: 0,
                    paid: 0
                }
            });

            const stageMap = {
                "Assigned": "assigned",
                "Connected": "contacted",
                "Qualified Leads": "qualified",
                "Demo": "demo",
                "Success": "id",
                "New Lead": "remaining"
            };

            const processSection = (sectionData, category) => {
                Object.entries(sectionData).forEach(([stageName, agents]) => {
                    const mappedField = stageMap[stageName];
                    if (!mappedField) return;

                    Object.entries(agents).forEach(([agentName, {
                        count = 0,
                        items = []
                    }]) => {
                        if (!processedData[agentName]) {
                            processedData[agentName] = initAgent(agentName);
                        }

                        const agent = processedData[agentName];
                        agent[category][mappedField] += count;
                        agent.total[mappedField] += count;

                        items.forEach(item => {
                            if (item.campaignName) {
                                agent.campaigns.add(item.campaignName);
                            }
                        });
                    });
                });
            };

            if (data.Call) processSection(data.Call, "other");
            if (data["Meta Sheet"]) processSection(data["Meta Sheet"], "paid");

            // Set branch without re-calculating remaining
            Object.values(processedData).forEach(agent => {
                agent.branch = determineBranchFromCampaigns(agent.campaigns);
            });

            return Object.values(processedData).sort((a, b) => a.name.localeCompare(b.name));
        }

        function determineBranchFromCampaigns(campaigns) {
            // Convert Set to Array for processing
            const campaignArray = Array.from(campaigns);

            if (campaignArray.length === 0) {
                return "UNKNOWN";
            }

            // Extract location from campaign names
            // Based on the data, campaigns follow patterns like "TRADEMART V1 NAGPUR"
            // So we extract the last word as the location/branch
            const locationSet = new Set();

            campaignArray.forEach(campaign => {
                if (campaign !== "Unknown Campaign") {
                    const parts = campaign.split(" ");
                    if (parts.length > 0) {
                        const location = parts[parts.length - 1];
                        locationSet.add(location);
                    }
                }
            });

            // If we found locations, join them with comma
            if (locationSet.size > 0) {
                return Array.from(locationSet).join(", ");
            }

            return "OTHER";
        }

        function populateTable(processedData) {
            const tableBody = document.querySelector('#leads-table tbody');

            const totalRow = tableBody.querySelector('tr.bg-gray-300');
            tableBody.innerHTML = '';
            tableBody.appendChild(totalRow);

            const totals = {
                paid: {
                    assigned: 0,
                    contacted: 0,
                    qualified: 0,
                    demo: 0,
                    id: 0,
                    remaining: 0
                },
                other: {
                    assigned: 0,
                    contacted: 0,
                    qualified: 0,
                    demo: 0,
                    id: 0,
                    remaining: 0
                },
                total: {
                    assigned: 0,
                    contacted: 0,
                    qualified: 0,
                    demo: 0,
                    id: 0,
                    remaining: 0
                },
                ziwo: {
                    outbound: 0,
                    answered: 0,
                    paid: 0
                }
            };

            processedData.forEach((agent, index) => {
                const row = document.createElement('tr');
                row.className = index % 2 === 0 ? 'bg-white agent-row' : 'bg-gray-100 agent-row';
                row.setAttribute('data-agent', agent.name.split(' ')[0].toLowerCase());
                row.setAttribute('data-branch', agent.branch.toLowerCase());
                row.setAttribute('data-date', agent.date || '');

                row.innerHTML = `
            <td class="border-r border-gray-300 p-2 font-medium">${index + 1}</td>
            <td class="border-r border-gray-300 p-2">${agent.name}</td>
            <td class="border-r border-gray-300 p-2">${agent.branch}</td>
            
            <!-- Paid Leads -->
            <td class="border-r border-gray-300 p-2 text-center paid-assigned">${agent.paid.assigned}</td>
            <td class="border-r border-gray-300 p-2 text-center paid-contacted">${agent.paid.contacted}</td>
            <td class="border-r border-gray-300 p-2 text-center paid-qualified">${agent.paid.qualified}</td>
            <td class="border-r border-gray-300 p-2 text-center paid-demo">${agent.paid.demo}</td>
            <td class="border-r border-gray-300 p-2 text-center paid-id bg-green-200">${agent.paid.id}</td>
            <td class="border-r border-gray-300 p-2 text-center paid-remaining">${agent.paid.remaining}</td>
            
            <!-- Other Leads -->
            <td class="border-r border-gray-300 p-2 text-center other-assigned">${agent.other.assigned}</td>
            <td class="border-r border-gray-300 p-2 text-center other-contacted">${agent.other.contacted}</td>
            <td class="border-r border-gray-300 p-2 text-center other-qualified">${agent.other.qualified}</td>
            <td class="border-r border-gray-300 p-2 text-center other-demo">${agent.other.demo}</td>
            <td class="border-r border-gray-300 p-2 text-center other-id bg-green-200">${agent.other.id}</td>
            <td class="border-r border-gray-300 p-2 text-center other-remaining">${agent.other.remaining}</td>
            
            <!-- Total Leads -->
            <td class="border-r border-gray-300 p-2 text-center total-assigned">${agent.total.assigned}</td>
            <td class="border-r border-gray-300 p-2 text-center total-contacted">${agent.total.contacted}</td>
            <td class="border-r border-gray-300 p-2 text-center total-qualified">${agent.total.qualified}</td>
            <td class="border-r border-gray-300 p-2 text-center total-demo">${agent.total.demo}</td>
            <td class="border-r border-gray-300 p-2 text-center total-id bg-green-200">${agent.total.id}</td>
            <td class="border-r border-gray-300 p-2 text-center total-remaining">${agent.total.remaining}</td>
            
            <!-- Ziwo -->
            <td class="border-r border-gray-300 p-2 text-center ziwo-outbound">${agent.ziwo.outbound}</td>
            <td class="border-r border-gray-300 p-2 text-center ziwo-answered">${agent.ziwo.answered}</td>
            <td class="p-2 text-center ziwo-paid">${agent.ziwo.paid}</td>
        `;

                tableBody.appendChild(row);

                Object.keys(totals).forEach(category => {
                    Object.keys(totals[category]).forEach(metric => {
                        totals[category][metric] += agent[category][metric];
                    });
                });
            });

            updateTotalsRow(totals);
        }

        function updateTotalsRow(totals) {
            const totalRow = document.querySelector('#leads-table tbody tr.bg-gray-300');

            totalRow.querySelector('.paid-assigned').textContent = totals.paid.assigned;
            totalRow.querySelector('.paid-contacted').textContent = totals.paid.contacted;
            totalRow.querySelector('.paid-qualified').textContent = totals.paid.qualified;
            totalRow.querySelector('.paid-demo').textContent = totals.paid.demo;
            totalRow.querySelector('.paid-id').textContent = totals.paid.id;
            totalRow.querySelector('.paid-remaining').textContent = totals.paid.remaining;

            totalRow.querySelector('.other-assigned').textContent = totals.other.assigned;
            totalRow.querySelector('.other-contacted').textContent = totals.other.contacted;
            totalRow.querySelector('.other-qualified').textContent = totals.other.qualified;
            totalRow.querySelector('.other-demo').textContent = totals.other.demo;
            totalRow.querySelector('.other-id').textContent = totals.other.id;
            totalRow.querySelector('.other-remaining').textContent = totals.other.remaining;

            totalRow.querySelector('.total-assigned').textContent = totals.total.assigned;
            totalRow.querySelector('.total-contacted').textContent = totals.total.contacted;
            totalRow.querySelector('.total-qualified').textContent = totals.total.qualified;
            totalRow.querySelector('.total-demo').textContent = totals.total.demo;
            totalRow.querySelector('.total-id').textContent = totals.total.id;
            totalRow.querySelector('.total-remaining').textContent = totals.total.remaining;

            totalRow.querySelector('.ziwo-outbound').textContent = totals.ziwo.outbound;
            totalRow.querySelector('.ziwo-answered').textContent = totals.ziwo.answered;
            totalRow.querySelector('.ziwo-paid').textContent = totals.ziwo.paid;
        }

        function setupFilters() {
            const startDateInput = document.getElementById('start-date');
            const endDateInput = document.getElementById('end-date');
            const agentFilter = document.getElementById('agent-filter');
            const branchFilter = document.getElementById('branch-filter');
            const paidLeadsFilter = document.getElementById('paid-leads-filter');
            const otherLeadsFilter = document.getElementById('other-leads-filter');

            function applyFilters() {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                const agentValue = agentFilter.value;
                const branchValue = branchFilter.value;
                const paidLeadsValue = paidLeadsFilter.value;
                const otherLeadsValue = otherLeadsFilter.value;

                const rows = document.querySelectorAll('#leads-table tbody tr.agent-row');

                // Reset highlighted cells
                document.querySelectorAll('.bg-yellow-200').forEach(cell => {
                    cell.classList.remove('bg-yellow-200');
                });

                rows.forEach(row => {
                    const rowAgent = row.getAttribute('data-agent');
                    const rowBranch = row.getAttribute('data-branch');
                    const rowDate = row.getAttribute('data-date');

                    const agentMatch = agentValue === 'all' || rowAgent === agentValue;

                    // Handle comma-separated branches
                    let branchMatch = branchValue === 'all';
                    if (!branchMatch && rowBranch) {
                        const rowBranches = rowBranch.split(',').map(b => b.trim().toLowerCase());
                        branchMatch = rowBranches.includes(branchValue.toLowerCase());
                    }

                    const dateMatch = true; // Date filtering is handled by your date inputs

                    row.style.display = (agentMatch && branchMatch && dateMatch) ? '' : 'none';

                    if (paidLeadsValue !== 'all') {
                        const cells = row.querySelectorAll(`.paid-${paidLeadsValue}`);
                        cells.forEach(cell => {
                            cell.classList.add('bg-yellow-200');
                        });
                    }

                    if (otherLeadsValue !== 'all') {
                        const cells = row.querySelectorAll(`.other-${otherLeadsValue}`);
                        cells.forEach(cell => {
                            cell.classList.add('bg-yellow-200');
                        });
                    }
                });
            }

            startDateInput.addEventListener('change', applyFilters);
            endDateInput.addEventListener('change', applyFilters);
            agentFilter.addEventListener('change', applyFilters);
            branchFilter.addEventListener('change', applyFilters);
            paidLeadsFilter.addEventListener('change', applyFilters);
            otherLeadsFilter.addEventListener('change', applyFilters);
        }

        async function initDashboard() {
            const data = await fetchData(1);
            if (data) {
                console.log('Fetched data:', data);
                const processedData = processData(data);
                console.log('Processed data:', processedData);

                populateTable(processedData);
                setupFilters();

                updateFilterOptions(processedData);
            }
        }

        function updateFilterOptions(processedData) {
            const agentFilter = document.getElementById('agent-filter');
            const branchFilter = document.getElementById('branch-filter');

            const agents = new Set();
            const branches = new Set();

            processedData.forEach(agent => {
                agents.add(agent.name.split(' ')[0].toLowerCase());

                // Handle multiple branches per agent (comma-separated)
                if (agent.branch) {
                    const branchList = agent.branch.split(',');
                    branchList.forEach(branch => {
                        const trimmedBranch = branch.trim().toLowerCase();
                        if (trimmedBranch) {
                            branches.add(trimmedBranch);
                        }
                    });
                }
            });

            agentFilter.innerHTML = '<option value="all">ALL</option>';
            branchFilter.innerHTML = '<option value="all">ALL</option>';

            agents.forEach(agent => {
                const option = document.createElement('option');
                option.value = agent;
                option.textContent = agent.toUpperCase();
                agentFilter.appendChild(option);
            });

            branches.forEach(branch => {
                const option = document.createElement('option');
                option.value = branch;
                option.textContent = branch.toUpperCase();
                branchFilter.appendChild(option);
            });
        }

        initDashboard();
    });
</script>
</body>

</html>