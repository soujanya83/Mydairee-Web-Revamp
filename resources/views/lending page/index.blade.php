<script type="text/javascript">
    var gk_isXlsx = false;
        var gk_xlsxFileLookup = {};
        var gk_fileData = {};
        function filledCell(cell) {
          return cell !== '' && cell != null;
        }
        function loadFileData(filename) {
        if (gk_isXlsx && gk_xlsxFileLookup[filename]) {
            try {
                var workbook = XLSX.read(gk_fileData[filename], { type: 'base64' });
                var firstSheetName = workbook.SheetNames[0];
                var worksheet = workbook.Sheets[firstSheetName];

                // Convert sheet to JSON to filter blank rows
                var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1, blankrows: false, defval: '' });
                // Filter out blank rows (rows where all cells are empty, null, or undefined)
                var filteredData = jsonData.filter(row => row.some(filledCell));

                // Heuristic to find the header row by ignoring rows with fewer filled cells than the next row
                var headerRowIndex = filteredData.findIndex((row, index) =>
                  row.filter(filledCell).length >= filteredData[index + 1]?.filter(filledCell).length
                );
                // Fallback
                if (headerRowIndex === -1 || headerRowIndex > 25) {
                  headerRowIndex = 0;
                }

                // Convert filtered JSON back to CSV
                var csv = XLSX.utils.aoa_to_sheet(filteredData.slice(headerRowIndex)); // Create a new sheet from filtered array of arrays
                csv = XLSX.utils.sheet_to_csv(csv, { header: 1 });
                return csv;
            } catch (e) {
                console.error(e);
                return "";
            }
        }
        return gk_fileData[filename] || "";
        }
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MyDiaree Landing</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Base styles */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #0f172a;
            /* slate-900 */
            background-color: #ffffff;
            /* white */
            margin: 0;
            min-height: 100vh;
        }

        body {
            user-select: none;
            /* Standard syntax */

        }

        a {
            text-decoration: none;
        }

        button {
            cursor: pointer;
        }

        i {
            display: inline-block;
        }

        /* Utility classes */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* max-w-7xl, px-4 */
        .container-narrow {
            max-width: 896px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* max-w-4xl */
        .flex {
            display: flex;
        }

        .flex-col {
            flex-direction: column;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .gap-2 {
            gap: 8px;
        }

        .gap-3 {
            gap: 12px;
        }

        .gap-4 {
            gap: 16px;
        }

        .gap-6 {
            gap: 24px;
        }

        .gap-8 {
            gap: 32px;
        }

        .text-xs {
            font-size: 0.75rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-base {
            font-size: 1rem;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .text-xl {
            font-size: 1.25rem;
        }

        .text-2xl {
            font-size: 1.5rem;
        }

        .text-3xl {
            font-size: 1.875rem;
        }

        .text-4xl {
            font-size: 2.25rem;
        }

        .text-5xl {
            font-size: 3rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }

        .tracking-tight {
            letter-spacing: -0.025em;
        }

        .text-slate-500 {
            color: #64748b;
        }

        .text-slate-600 {
            color: #475569;
        }

        .text-slate-700 {
            color: #334155;
        }

        .text-slate-900 {
            color: #0f172a;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        .text-blue-700 {
            color: #1d4ed8;
        }

        .bg-white {
            background-color: #ffffff;
        }

        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .bg-blue-50 {
            background-color: #eff6ff;
        }

        .bg-blue-600 {
            background-color: #2563eb;
        }

        .bg-white-80 {
            background-color: rgba(255, 255, 255, 0.8);
        }

        .bg-gradient-to-tr-sky-blue {
            background: linear-gradient(to top right, #38bdf8, #2563eb);
        }

        .bg-gradient-to-r-sky-blue {
            background: linear-gradient(to right, #f0f9ff, #e0f2fe);
        }

        .border {
            border: 1px solid #e5e7eb;
        }

        .border-y {
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        .border-blue-600 {
            border-color: #2563eb;
        }

        .rounded-xl {
            border-radius: 12px;
        }

        .rounded-2xl {
            border-radius: 16px;
        }

        .p-2 {
            padding: 8px;
        }

        .p-3 {
            padding: 12px;
        }

        .px-4 {
            padding-left: 16px;
            padding-right: 16px;
        }

        .px-6 {
            padding-left: 24px;
            padding-right: 24px;
        }

        .py-3 {
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .py-8 {
            padding-top: 32px;
            padding-bottom: 32px;
        }

        .py-10 {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .py-14 {
            padding-top: 56px;
            padding-bottom: 56px;
        }

        .py-16 {
            padding-top: 64px;
            padding-bottom: 64px;
        }

        .py-24 {
            padding-top: 60px;
            padding-bottom: 20px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mt-6 {
            margin-top: 24px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .mb-10 {
            margin-bottom: 40px;
        }

        .w-full {
            width: 100%;
        }

        .w-fit {
            width: fit-content;
        }

        .max-w-xl {
            max-width: 512px;
        }

        .max-w-2xl {
            max-width: 672px;
        }

        .max-w-md {
            max-width: 384px;
        }

        .h-7 {
            height: 28px;
        }

        .w-7 {
            width: 28px;
        }

        .h-4 {
            height: 16px;
        }

        .w-4 {
            width: 16px;
        }

        .h-5 {
            height: 20px;
        }

        .w-5 {
            width: 20px;
        }

        .h-6 {
            height: 24px;
        }

        .w-6 {
            width: 24px;
        }

        .shadow-sm {
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .transition {
            transition: all 0.2s ease;
        }

        .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .hover\:text-slate-900:hover {
            color: #0f172a;
        }

        .hover\:bg-gray-100:hover {
            background-color: #f3f4f6;
        }

        .hover\:bg-blue-700:hover {
            background-color: #3e756e;
        }

        /* Button styles */
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: #49c5b6;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #3e756e;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn-outline:hover {
            background-color: #f3f4f6;
        }

        .btn-ghost {
            background-color: transparent;
            color: #374151;
        }

        .btn-ghost:hover {
            background-color: #f3f4f6;
        }

        .btn-lg {
            padding: 12px 24px;
            font-size: 1rem;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.75rem;
        }

        .group:hover .group-hover\:translate-x-0\.5 {
            transform: translateX(2px);
        }

        /* Badge styles */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-secondary {
            background-color: #e5e7eb;
            color: #374151;
        }

        .badge-primary {
            background-color: #49c5b6;
            color: #ffffff;
        }

        /* Card styles */
        .card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .card-header {
            padding: 16px;
        }

        .card-content {
            padding: 16px;
        }

        /* Input styles */
        input {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            width: 100%;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #49c5b6;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Accordion styles */
        .accordion {
            width: 100%;
        }

        .accordion-item {
            border-bottom: 1px solid #e5e7eb;
        }

        .accordion-trigger {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
        }

        .accordion-trigger::after {
            content: '\25BC';
            /* Down arrow */
            font-size: 0.75rem;
            transition: transform 0.2s ease;
        }

        .accordion-trigger.active::after {
            transform: rotate(180deg);
        }

        .accordion-content {
            display: none;
            padding: 0 16px 16px;
            font-size: 0.875rem;
            color: #475569;
        }

        .accordion-content.active {
            display: block;
        }

        /* Header */
        header {
            position: sticky;
            top: 0;
            z-index: 40;
            width: 100%;
            border-bottom: 1px solid #e5e7eb;
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
        }

        /* Hero gradient */
        #home {
            position: relative;
            overflow: hidden;
        }

        #home::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -10;
            background: radial-gradient(60% 50% at 50% 0%, rgba(37, 99, 235, 0.08), transparent 60%);
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 24px;
        }

        /* Responsive */
        @media (min-width: 640px) {
            .sm\:flex-row {
                flex-direction: row;
            }
        }

        @media (min-width: 768px) {
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }

            .md\:flex-row {
                flex-direction: row;
            }

            .md\:text-left {
                text-align: left;
            }

            .md\:hidden {
                display: none;
            }

            .md\:flex {
                display: flex;
            }

            .md\:px-6 {
                padding-left: 24px;
                padding-right: 24px;
            }

            .md\:block {
                display: block;
            }

            .md\:text-3xl {
                font-size: 1.875rem;
            }

            .md\:text-5xl {
                font-size: 3rem;
            }
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-3 {
                grid-template-columns: repeat(3, 1fr);
            }

            .lg\:py-24 {
                padding-top: 60px;
                padding-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="min-h-screen bg-white text-slate-900">
        <!-- Nav -->
        <header class="sticky top-0 z-40 w-full border-b bg-white-80 backdrop-blur">
            <div class="container flex items-center justify-between px-4 py-3 md:px-6">
                <a href="#home" class="flex items-center gap-2 font-semibold">

                    <img src="{{ asset('assets/img/MYDIAREE-new-logo.png') }}" alt="Lucid Logo"
                        class="img-responsive logo" width="180">
                </a>
                <nav class="hidden gap-6 md:flex">
                    <a href="#features" class="text-sm text-slate-600 hover:text-slate-900"
                        aria-label="Features">Features</a>
                    <a href="#benefits" class="text-sm text-slate-600 hover:text-slate-900"
                        aria-label="Benefits">Benefits</a>
                    <a href="#pricing" class="text-sm text-slate-600 hover:text-slate-900"
                        aria-label="Pricing">Pricing</a>
                    <a href="#faq" class="text-sm text-slate-600 hover:text-slate-900" aria-label="FAQ">FAQ</a>
                    <a href="#contact" class="text-sm text-slate-600 hover:text-slate-900"
                        aria-label="Contact">Contact</a>
                </nav>
                <div class="hidden items-center gap-3 md:flex">
                    <a href="{{ route('login') }}"
                        style="background-color:#49c5b6; color:white; border:none; padding:7px 16px; border-radius:6px; text-decoration:none; display:inline-block;"
                        onmouseover="this.style.backgroundColor='#3e756e';"
                        onmouseout="this.style.backgroundColor='#49c5b6';">

                        Login
                    </a>
                </div>

                {{-- <button class="btn btn-outline md:hidden text-sm" aria-label="Get started">Get started</button>
                --}}
            </div>
        </header>

        <!-- Hero -->
        <section id="home" class="relative overflow-hidden">
            <div class="container grid gap-10 px-4 py-16 md:grid-cols-2 md:items-center md:px-6 lg:py-24">
                <div class="max-w-xl">
                    <span class="badge badge-secondary mb-4 w-fit">Built for Australian Childcare-Montessori and EYLF
                        Aligned</span>
                    <h1 class="mb-4 text-4xl font-bold tracking-tight md:text-5xl">AI enabled Daily diary & family<span
                            style="color:#49c5b6"> communications platform</span>
                    </h1>
                    <p class="mb-6 text-lg text-slate-600">
                        Spend less time on paperwork and more time with children. MyDiaree helps early educators capture
                        and securely share the day's moments while ensuring compliance.
                    </p>
                    {{-- <div class="flex flex-col gap-3 sm:flex-row">
                        <button class="btn btn-primary btn-lg group">
                            Start free trial
                            <i data-lucide="chevron-right"
                                class="ml-1 h-5 w-5 transition group-hover:translate-x-0.5"></i>
                        </button>
                        <button class="btn btn-outline btn-lg">Book a demo</button>
                    </div> --}}
                    <div class="mt-6 flex items-center gap-4 text-xs text-slate-500">
                        <div class="flex items-center gap-1"><i data-lucide="shield-check" class="h-4 w-4"></i>&nbsp;AUS
                            data
                            hosting</div>
                        <div class="flex items-center gap-1"><i data-lucide="credit-card"
                                class="h-4 w-4"></i>&nbsp;Cancel
                            anytime</div>
                    </div>
                </div>
                <div class="relative">
                    <div class="rounded-2xl border bg-white p-2 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=1600&auto=format&fit=crop"
                            alt="MyDiaree interface mockup" class="h-full w-full rounded-xl object-cover" />
                    </div>
                    <div
                        class="pointer-events-none absolute -bottom-6 -left-6 hidden rotate-2 rounded-2xl border bg-white-80 p-3 shadow-sm backdrop-blur md:block">
                        <p class="text-xs text-slate-600">"So much faster than our old app—parents love it."</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="container px-4 py-16 md:px-6">


            <div class="mb-10 max-w-2xl">
                <h2 class="text-2xl font-semibold md:text-3xl">Purpose-Built for Australian Childcare Centres</h2>
                <p class="mt-2 text-slate-600">Everything you need for daily communication, documentation, and insights
                    in one simple platform.</p>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div class="card transition hover:shadow-md">
                    <div class="card-header flex flex-row items-center gap-3">
                        <div class="rounded-xl bg-blue-50 p-2 text-blue-700"><i data-lucide="calendar"
                                style="color:#49c5b6"></i></div>
                        <h3 class="text-base font-semibold">Daily Diary, Simplified</h3>
                    </div>
                    <div class="card-content">
                        <p class="text-sm text-slate-600">Easily capture and automatically share meals, sleep times,
                            learning moments, photos, and notes with parents in seconds.</p>
                    </div>
                </div>
                <div class="card transition hover:shadow-md">
                    <div class="card-header flex flex-row items-center gap-3">
                        <div class="rounded-xl bg-blue-50 p-2 text-blue-700"><i data-lucide="users"
                                style="color:#49c5b6"></i>
                        </div>
                        <h3 class="text-base font-semibold">Family-first Messaging</h3>
                    </div>
                    <div class="card-content">
                        <p class="text-sm text-slate-600">Send secure one-to-one and broadcast messages with read
                            receipts and media attachments</p>
                    </div>
                </div>
                <div class="card transition hover:shadow-md">
                    <div class="card-header flex flex-row items-center gap-3">
                        <div class="rounded-xl bg-blue-50 p-2 text-blue-700"><i data-lucide="bar-chart-3"
                                style="color:#49c5b6"></i></div>
                        <h3 class="text-base font-semibold">Montessori Aligned</h3>
                    </div>
                    <div class="card-content">
                        <p class="text-sm text-slate-600">Montessori and EYLF-aligned observations
                            in one click.</p>
                    </div>
                </div>
                <div class="card transition hover:shadow-md">
                    <div class="card-header flex flex-row items-center gap-3">
                        <div class="rounded-xl bg-blue-50 p-2 text-blue-700"><i data-lucide="plug-zap"
                                style="color:#49c5b6"></i></div>
                        <h3 class="text-base font-semibold">Plug‑and‑Play Integrations (Coming Soon)</h3>
                    </div>
                    <div class="card-content">
                        <p class="text-sm text-slate-600">Integrate with Xero and more (API-ready). CSV in/out for
                            quick migrations.</p>
                    </div>
                </div>
                <div class="card transition hover:shadow-md">
                    <div class="card-header flex flex-row items-center gap-3">
                        <div class="rounded-xl bg-blue-50 p-2 text-blue-700"><i data-lucide="shield-check"
                                style="color:#49c5b6"></i></div>
                        <h3 class="text-base font-semibold">AUS‑Hosted & Secure</h3>
                    </div>
                    <div class="card-content">
                        <p class="text-sm text-slate-600">Your data remains securely on Australian Servers, with
                            built-in redundancies, IP restrictions. and role-based access control.</p>
                    </div>
                </div>
                <div class="card transition hover:shadow-md">
                    <div class="card-header flex flex-row items-center gap-3">
                        <div class="rounded-xl bg-blue-50 p-2 text-blue-700"><i data-lucide="sparkles"
                                style="color:#49c5b6"></i></div>
                        <h3 class="text-base font-semibold">AI Assist</h3>
                    </div>
                    <div class="card-content" id="benefits">
                        <p class="text-sm text-slate-600">Leverage AI to automatically refinet learning stories,
                            translate communications for parents, and suggest next activities.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefit strip -->
        <section class="border-y bg-slate-50">


            <div class="container grid gap-6 px-4 py-14 md:grid-cols-3 md:px-6">
                <h2 class="text-2xl font-semibold md:text-3xl">Benefits</h2>
                <div>
                    <h3 class="mb-1 text-lg font-semibold">Minutes, not hours</h3>
                    <p class="text-slate-600">Templates and auto‑fill speed up documentation.</p>
                </div>
                <div>
                    <h3 class="mb-1 text-lg font-semibold">Happier families</h3>
                    <p class="text-slate-600">Beautiful timelines and instant notifications.</p>
                </div>
                <div>
                    <h3 class="mb-1 text-lg font-semibold">Compliance, covered</h3>
                    <p class="text-slate-600">Export what regulators need in a click.</p>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="container px-4 py-16 md:px-6">
            <div class="mb-10 text-center">
                <h2 class="text-2xl font-semibold md:text-3xl">Simple pricing</h2>
                <p class="mt-2 text-slate-600">Try it free, then pick a plan that fits your centre. No setup fees.</p>
            </div>
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold">Starter</h3>
                        </div>
                        <div class="mt-2 text-4xl font-bold">$0 <span class="text-base font-medium text-slate-500">per
                                centre / 14‑day trial</span></div>
                    </div>
                    <div class="card-content">
                        <ul class="mb-6 space-y-2 text-sm text-slate-700">
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Up to 30 enrolled children</li>
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Core diary & media</li>
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Parent app & web portal</li>
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Email support</li>
                        </ul>
                        <button class="btn btn-primary w-full">Start free trial</button>
                    </div>
                </div>
                <div class="card border-blue-600 shadow-lg">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold">Growth</h3>
                            <span class="badge badge-primary">Most popular</span>
                        </div>
                        <div class="mt-2 text-4xl font-bold">$89 <span class="text-base font-medium text-slate-500">per
                                centre / month</span></div>
                    </div>
                    <div class="card-content">
                        <ul class="mb-6 space-y-2 text-sm text-slate-700">
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Unlimited children & rooms</li>
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Observations + EYLF tags</li>
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Bulk messaging & templates</li>
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Priority support</li>
                        </ul>
                        <button class="btn btn-primary w-full">Choose Growth</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold">Multi‑Centre</h3>
                        </div>
                        <div class="mt-2 text-4xl font-bold">Custom <span
                                class="text-base font-medium text-slate-500">volume pricing</span></div>
                    </div>
                    <div class="card-content">
                        <ul class="mb-6 space-y-2 text-sm text-slate-700">
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Multi‑site analytics</li>
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> SSO & SCIM (optional)</li>
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Migration & onboarding</li>
                            <li class="flex items-start gap-2"><i data-lucide="check"
                                    class="mt-0.5 h-4 w-4 text-blue-600"></i> Success manager</li>
                        </ul>
                        <button class="btn btn-primary w-full">Talk to sales</button>
                    </div>
                </div>
            </div>
            <p class="mt-4 text-center text-xs text-slate-500">All prices in AUD. GST may apply.</p>
        </section>

        <!-- CTA strip -->
        {{-- <section class="border-y bg-gradient-to-r-sky-blue">
            <div
                class="container flex flex-col items-center justify-between gap-4 px-4 py-10 text-center md:flex-row md:text-left md:px-6">
                <div>
                    <h3 class="text-xl font-semibold">Ready to modernise your daily diaries?</h3>
                    <p class="text-slate-600">Start your 14‑day free trial—no credit card required.</p>
                </div>
                <div class="flex w-full max-w-md items-center gap-2 md:w-auto">
                    <input type="email" placeholder="Work email" aria-label="Work email" />
                    <button class="btn btn-primary">Get started</button>
                </div>
            </div>
        </section> --}}

        <!-- FAQ -->
        <section id="faq" class="container-narrow px-4 py-16 md:px-6">
            <h2 class="text-2xl font-semibold md:text-3xl">Frequently asked questions</h2>
            <div class="accordion">
                <div class="accordion-item">
                    <button class="accordion-trigger">Is MyDiaree compliant with EYLF and Australian privacy
                        laws?</button>
                    <div class="accordion-content">
                        <p>Yes. Observations can be tagged to EYLF outcomes and we host data in Australian regions with
                            strict access controls. We also support exporting records for audits.</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-trigger">How easy is it to migrate from our current app?</button>
                    <div class="accordion-content">
                        <p>Very. Import CSVs for children, rooms and guardians. Our team can assist with bulk media and
                            historical notes for multi‑centre groups.</p>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-trigger">Do parents need to download an app?</button>
                    <div class="accordion-content">
                        <p>Parents can use our iOS/Android app or the secure web portal—whichever they prefer.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section id="contact" class="border-t bg-white">
            <div class="container grid gap-8 px-4 py-14 md:grid-cols-2 md:px-6">
                <div>
                    <h2 class="text-2xl font-semibold md:text-3xl">Talk to our team</h2>
                    <p class="mt-2 max-w-md text-slate-600">Questions about pricing, integrations or rollout? We’ll get
                        back within one business day.</p>
                    <div class="mt-6 space-y-3 text-slate-700">
                        <p class="flex items-center gap-2"><i data-lucide="mail" class="h-5 w-5"></i>
                            support@mydiaree.com.au</p>
                        <p class="flex items-center gap-2"><i data-lucide="phone" class="h-5 w-5"></i> +61 (0)3 0000
                            0000</p>
                        <p class="flex items-center gap-2"><i data-lucide="globe" class="h-5 w-5"></i> MyDiaree.com.au
                        </p>
                    </div>
                </div>

                <div
                    style="max-width: 600px; margin: 2px auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                    <h3 style="font-size: 22px; font-weight: 600; margin-bottom: 20px;">Contact Us</h3>
                    <p style="color: #6c757d; margin-bottom: 16px;">Have questions or want to learn more? Send us a
                        message and we'll
                        respond as soon as possible.</p>

                    <input type="text" placeholder="Full Name"
                        style="width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;" />

                    <input type="email" placeholder="Email Address"
                        style="width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;" />

                    <input type="text" placeholder="Phone Number"
                        style="width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;" />

                    <textarea placeholder="Please enter your message"
                        style="width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; font-family: Arial, sans-serif; color: #6c757d; resize: vertical;"></textarea>


                    <!-- Checkbox inline -->
                    <div style="display: flex; align-items: center; margin-bottom: 16px;">
                        <input type="checkbox" id="consent" required
                            style="margin-right: 8px; width: 16px; height: 16px; cursor: pointer;" />
                        <label for="consent" style="font-size: 14px; color: #333; cursor: pointer;">
                            I agree to the processing of my personal data for contact purposes
                        </label>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit"
                            style="background-color: #49c5b6; color: #fff; font-size: 16px; font-weight: 600; padding: 12px 20px; border: none; border-radius: 6px; width: 100%; cursor: pointer;">
                            Send Message
                        </button>
                    </div>
                </div>


                {{-- <div
                    style="max-width: 400px; margin: 2px auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                    <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 20px;"> Contact Us</h3>
                    <p class="text-muted mb-4">Have questions or want to learn more? Send us a message and we'll respond
                        as soon as possible.</p>

                    <input type="text" placeholder="Full Name"
                        style="width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;" />

                    <input type="email" placeholder="Email Address"
                        style="width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;" />
                    <input type="email" placeholder="Phone Number"
                        style="width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;" />
                    <textarea placeholder="Please enter your message"
                        style="width: 100%; padding: 12px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;">
                    </textarea>


                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="consent" required>
                        <label class="form-check-label" for="consent">I agree to the processing of my personal data for
                            contact purposes</label>

                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send"></i> Send Message
                        </button>
                    </div>
                </div> --}}


            </div>
        </section>
        <hr>
        <!-- Footer -->
        <footer class="border-t bg-slate-50">
            <div
                class="container flex flex-col items-center justify-between px-4 py-8 text-sm text-slate-600 md:flex-row md:px-6">
                <p style="margin-left: 50%">© {{ date('Y') }} MyDiaree</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-slate-900">Privacy</a>
                    <a href="#" class="hover:text-slate-900">Terms</a>
                    <a href="#" class="hover:text-slate-900">Security</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Initialize Lucide icons
    lucide.createIcons();

    // Accordion toggle (single open at a time)
    document.querySelectorAll('.accordion-trigger').forEach(trigger => {
      trigger.addEventListener('click', () => {
        const content = trigger.nextElementSibling;
        const isActive = content.classList.contains('active');

        // Close all accordion items
        document.querySelectorAll('.accordion-content').forEach(c => {
          c.classList.remove('active');
          c.previousElementSibling.classList.remove('active');
        });

        // Toggle current item
        if (!isActive) {
          content.classList.add('active');
          trigger.classList.add('active');
        }
      });
    });
    </script>
</body>

</html>
