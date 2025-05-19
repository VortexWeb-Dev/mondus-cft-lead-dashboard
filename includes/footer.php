<script>
    // Filter logic for dropdowns and date range
    const agentFilter = document.getElementById('agent-filter');
    const branchFilter = document.getElementById('branch-filter');
    const paidLeadsFilter = document.getElementById('paid-leads-filter');
    const otherLeadsFilter = document.getElementById('other-leads-filter');
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const rows = document.querySelectorAll('.agent-row');

    // Function to filter rows
    function filterRows() {
        const agentValue = agentFilter.value;
        const branchValue = branchFilter.value;
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        rows.forEach(row => {
            const agent = row.getAttribute('data-agent');
            const branch = row.getAttribute('data-branch');
            const rowDate = new Date(row.getAttribute('data-date'));

            const agentMatch = agentValue === 'all' || agent === agentValue;
            const branchMatch = branchValue === 'all' || branch === branchValue;
            const dateMatch = rowDate >= startDate && rowDate <= endDate;

            if (agentMatch && branchMatch && dateMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateTotals();
    }

    // Function to update totals
    function updateTotals() {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');

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

        visibleRows.forEach(row => {
            totals.paid.assigned += parseInt(row.querySelector('.paid-assigned').textContent);
            totals.paid.contacted += parseInt(row.querySelector('.paid-contacted').textContent);
            totals.paid.qualified += parseInt(row.querySelector('.paid-qualified').textContent);
            totals.paid.demo += parseInt(row.querySelector('.paid-demo').textContent);
            totals.paid.id += parseInt(row.querySelector('.paid-id').textContent);
            totals.paid.remaining += parseInt(row.querySelector('.paid-remaining').textContent);

            totals.other.assigned += parseInt(row.querySelector('.other-assigned').textContent);
            totals.other.contacted += parseInt(row.querySelector('.other-contacted').textContent);
            totals.other.qualified += parseInt(row.querySelector('.other-qualified').textContent);
            totals.other.demo += parseInt(row.querySelector('.other-demo').textContent);
            totals.other.id += parseInt(row.querySelector('.other-id').textContent);
            totals.other.remaining += parseInt(row.querySelector('.other-remaining').textContent);

            totals.total.assigned += parseInt(row.querySelector('.total-assigned').textContent);
            totals.total.contacted += parseInt(row.querySelector('.total-contacted').textContent);
            totals.total.qualified += parseInt(row.querySelector('.total-qualified').textContent);
            totals.total.demo += parseInt(row.querySelector('.total-demo').textContent);
            totals.total.id += parseInt(row.querySelector('.total-id').textContent);
            totals.total.remaining += parseInt(row.querySelector('.total-remaining').textContent);

            totals.ziwo.outbound += parseInt(row.querySelector('.ziwo-outbound').textContent);
            totals.ziwo.answered += parseInt(row.querySelector('.ziwo-answered').textContent);
            totals.ziwo.paid += parseInt(row.querySelector('.ziwo-paid').textContent);
        });

        // Update total row
        document.querySelector('.paid-assigned').textContent = totals.paid.assigned;
        document.querySelector('.paid-contacted').textContent = totals.paid.contacted;
        document.querySelector('.paid-qualified').textContent = totals.paid.qualified;
        document.querySelector('.paid-demo').textContent = totals.paid.demo;
        document.querySelector('.paid-id').textContent = totals.paid.id;
        document.querySelector('.paid-remaining').textContent = totals.paid.remaining;

        document.querySelector('.other-assigned').textContent = totals.other.assigned;
        document.querySelector('.other-contacted').textContent = totals.other.contacted;
        document.querySelector('.other-qualified').textContent = totals.other.qualified;
        document.querySelector('.other-demo').textContent = totals.other.demo;
        document.querySelector('.other-id').textContent = totals.other.id;
        document.querySelector('.other-remaining').textContent = totals.other.remaining;

        document.querySelector('.total-assigned').textContent = totals.total.assigned;
        document.querySelector('.total-contacted').textContent = totals.total.contacted;
        document.querySelector('.total-qualified').textContent = totals.total.qualified;
        document.querySelector('.total-demo').textContent = totals.total.demo;
        document.querySelector('.total-id').textContent = totals.total.id;
        document.querySelector('.total-remaining').textContent = totals.total.remaining;

        document.querySelector('.ziwo-outbound').textContent = totals.ziwo.outbound;
        document.querySelector('.ziwo-answered').textContent = totals.ziwo.answered;
        document.querySelector('.ziwo-paid').textContent = totals.ziwo.paid;
    }

    // Function to filter columns based on Paid Leads and Other Leads dropdowns
    function filterColumns() {
        const paidFilter = paidLeadsFilter.value;
        const otherFilter = otherLeadsFilter.value;

        const paidColumns = ['assigned', 'contacted', 'qualified', 'demo', 'id', 'remainder'];
        const otherColumns = ['assigned', 'contacted', 'qualified', 'demo', 'id', 'remaining'];

        // Show/hide Paid Leads columns
        paidColumns.forEach(col => {
            const elements = document.querySelectorAll(`.paid-${col}`);
            elements.forEach(el => {
                el.style.display = (paidFilter === 'all' || paidFilter === col) ? '' : 'none';
            });
        });

        // Show/hide Other Leads columns
        otherColumns.forEach(col => {
            const elements = document.querySelectorAll(`.other-${col}`);
            elements.forEach(el => {
                el.style.display = (otherFilter === 'all' || otherFilter === col) ? '' : 'none';
            });
        });
    }

    // Event listeners for filters
    agentFilter.addEventListener('change', filterRows);
    branchFilter.addEventListener('change', filterRows);
    startDateInput.addEventListener('change', filterRows);
    endDateInput.addEventListener('change', filterRows);
    paidLeadsFilter.addEventListener('change', filterColumns);
    otherLeadsFilter.addEventListener('change', filterColumns);

    // Initial filter
    filterRows();
    filterColumns();
</script>
</body>

</html>