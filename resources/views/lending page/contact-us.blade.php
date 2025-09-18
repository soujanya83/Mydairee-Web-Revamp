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
                var workbook = XLSX.read(gk_fileData[filename], {
                    type: 'base64'
                });
                var firstSheetName = workbook.SheetNames[0];
                var worksheet = workbook.Sheets[firstSheetName];

                // Convert sheet to JSON to filter blank rows
                var jsonData = XLSX.utils.sheet_to_json(worksheet, {
                    header: 1,
                    blankrows: false,
                    defval: ''
                });
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
                csv = XLSX.utils.sheet_to_csv(csv, {
                    header: 1
                });
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
                <a href="{{ url('/')}}" class="flex items-center gap-2 font-semibold">

                    <img src="{{ asset('assets/img/MYDIAREE-new-logo.png') }}" alt="Lucid Logo"
                        class="img-responsive logo" width="180">
                </a>
                <!-- <nav class="hidden gap-6 md:flex">
                    <a href="#features" class="text-sm text-slate-600 hover:text-slate-900"
                        aria-label="Features">Features</a>
                    <a href="#benefits" class="text-sm text-slate-600 hover:text-slate-900"
                        aria-label="Benefits">Benefits</a>
                    <a href="#pricing" class="text-sm text-slate-600 hover:text-slate-900"
                        aria-label="Pricing">Pricing</a>
                    <a href="#faq" class="text-sm text-slate-600 hover:text-slate-900" aria-label="FAQ">FAQ</a>
                    <a href="#contact" class="text-sm text-slate-600 hover:text-slate-900"
                        aria-label="Contact">Contact</a>
                </nav> -->
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

        @if (session('success'))
        <div style="max-width:600px;margin:10px auto;padding:10px;border-radius:6px;background:#d4edda;color:#155724;border:1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div style="max-width:600px;margin:10px auto;padding:10px;border-radius:6px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
        @endif


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
                        <p class="flex items-center gap-2"><i data-lucide="phone" class="h-5 w-5"></i> +61 493 889 880</p>
                        <p class="flex items-center gap-2"><i data-lucide="globe" class="h-5 w-5"></i> MyDiaree.com.au
                        </p>
                    </div>
                </div>
                <form action="{{ route('contact-us') }}" method="post">
                    @csrf
                    <div style="max-width: 600px; margin: 2px auto; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                        <h3 style="font-size: 22px; font-weight: 600; margin-bottom: 20px;">Contact Us</h3>
                        <p style="color: #6c757d; margin-bottom: 16px;">Have questions or want to learn more? Send us a message and we'll respond as soon as possible.</p>

                        {{-- Full Name --}}
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name"
                            style="width: 100%; padding: 12px; margin-bottom: 6px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;" required />
                        @error('name')
                        <span style="color: red; font-size: 13px;">{{ $message }}</span>
                        @enderror

                        {{-- Email --}}
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address"
                            style="width: 100%; padding: 12px; margin-bottom: 6px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;" required />
                        @error('email')
                        <span style="color: red; font-size: 13px;">{{ $message }}</span>
                        @enderror

                        {{-- Phone --}}
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone Number"
                            style="width: 100%; padding: 12px; margin-bottom: 6px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;" required />
                        @error('phone')
                        <span style="color: red; font-size: 13px;">{{ $message }}</span>
                        @enderror

                        {{-- Message --}}
                        <textarea placeholder="Please enter your message" name="message" rows="8"
                            style="width: 100%; padding: 12px; margin-bottom: 6px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; font-family: Arial, sans-serif; color: #6c757d; resize: vertical;">{{ old('message') }}</textarea>
                        @error('message')
                        <span style="color: red; font-size: 13px;">{{ $message }}</span>
                        @enderror

                        {{-- Consent Checkbox --}}
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <input type="checkbox" id="consent" name="consent" {{ old('consent') ? 'checked' : '' }}
                                style="margin-right: 8px; width: 16px; height: 16px; cursor: pointer;" />
                            <label for="consent" style="font-size: 14px; color: #333; cursor: pointer;margin-block:4px;">
                                I agree to the processing of my personal data for contact purposes
                            </label>
                        </div>
                        @error('consent')
                        <span style="color: red; font-size: 13px;">{{ $message }}</span>
                        @enderror

                        {{-- Submit --}}
                        <div style="text-align: center;">
                            <button type="submit"
                                style="background-color: #49c5b6; color: #fff; font-size: 16px; font-weight: 600; padding: 12px 20px; border: none; border-radius: 6px; width: 100%; cursor: pointer;">
                                Send Message
                            </button>
                        </div>
                    </div>
                </form>



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