<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CFT Leads Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <style>
        /* Custom styles for group separation */
        .group-agent,
        .group-branch {
            border-right: 2px double #4b5563;
            /* Tailwind gray-600, double border for stronger separation */
        }

        .group-paid {
            background-color: #1f2937;
            /* Tailwind gray-800 with slight variation */
            border-right: 2px double #4b5563;
        }

        .group-other {
            background-color: #374151;
            /* Tailwind gray-700 with slight variation */
            border-right: 2px double #4b5563;
        }

        .group-total {
            background-color: #4b5563;
            /* Tailwind gray-600 for distinction */
            border-right: 2px double #4b5563;
        }

        .group-ziwo {
            background-color: #6b7280;
            /* Tailwind gray-500 for distinction */
        }

        /* Ensure the last column in each group has a thicker border */
        th.group-paid:last-of-type,
        th.group-other:last-of-type,
        th.group-total:last-of-type,
        th.group-ziwo:last-of-type {
            border-right: 3px solid #1f2937;
            /* Stronger border to mark group end */
        }

        /* Apply similar separation to tbody cells */
        tbody td.group-agent,
        tbody td.group-branch {
            border-right: 2px double #d1d5db;
            /* Tailwind gray-300 for data rows */
        }

        tbody td.group-paid {
            border-right: 2px double #d1d5db;
        }

        tbody td.group-other {
            border-right: 2px double #d1d5db;
        }

        tbody td.group-total {
            border-right: 2px double #d1d5db;
        }

        tbody td.group-ziwo {
            border-right: 2px double #d1d5db;
        }

        /* Last column in each group in tbody */
        tbody td.group-paid:last-of-type,
        tbody td.group-other:last-of-type,
        tbody td.group-total:last-of-type,
        tbody td.group-ziwo:last-of-type {
            border-right: 3px solid #9ca3af;
            /* Tailwind gray-400 for stronger separation */
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 font-sans">