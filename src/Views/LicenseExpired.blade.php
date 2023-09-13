<!DOCTYPE html>
<html lang="en">
<head>
    <title>Validate License</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 flex items-center flex-col justify-center min-h-screen">
<div class="bg-white rounded-lg shadow-lg p-8 m-4 w-full max-w-md">
    <h1 class="text-2xl font-bold mb-4 text-red-600 text-center">Product Not Active or License Expired</h1>

    <form method="POST" action="/activate-product" class="space-y-4" id="userForm" autocomplete="off">
        <h2 class="text-md font-semibold mb-2 text-gray-700">Please provide the Product Key provide by the software owners.</h2>
        @csrf
        <div>
            <input id="key" name="key" type="text" class="bg-gray-50 border border-blue-600 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="License key" required>
        </div>
        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Validate</button>

        <div class="relative">
            <div class="flex items-center justify-center">
                <div class="flex-grow h-px bg-gray-200"></div>
                <span class="px-2 bg-white text-gray-400">OR</span>
                <div class="flex-grow h-px bg-gray-200"></div>
            </div>
        </div>
    </form>

    <button id="showCompanyForm" class="w-full py-2 px-4 bg-green-600 text-white rounded hover:bg-green-700 text-md mt-2">Activate with Company Code</button>

    <form method="POST" action="/activate-product" class="space-y-6 hidden" id="companyForm" autocomplete="off">
        <h2 class="text-lg font-semibold mb-2 text-gray-700">Activate with Company Code</h2>
        @csrf
        <div>
            <input id="secret" name="secret" type="password" class="bg-gray-50 border border-blue-600 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Company secret key" required>
        </div>
        <div>
            <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option selected disabled>Select license type</option>
                <option value="1">Trial</option>
                <option value="2">One Year Subscription</option>
                <option value="3">Purchase</option>
            </select>
        </div>
        <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Validate</button>
        <button type="button" id="hideCompanyForm" class="w-full py-2 px-4 bg-gray-600 text-white rounded hover:bg-gray-700 mt-4">Go Back</button>
    </form>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-6" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p class="text-center text-gray-500 text-xs mt-6">
        If you encounter any problems with your product key, please contact us at <a href="mailto:{{ env('COMPANY_MAIL') }}" class="text-blue-500 hover:text-blue-700 underline">{{ env('COMPANY_MAIL') }}</a> or call us at <a href="tel:{{ env('COMPANY_PHONE') }}" class="text-blue-500 hover:text-blue-700 underline">{{ env('COMPANY_PHONE') }}</a>.
    </p>
    <p class="text-xs text-center text-gray-400 mt-6">
        &copy; {{ date('Y') }} {{ env('COMPANY_NAME') }}. All rights reserved.
    </p>
</div>

<script>
    document.getElementById('showCompanyForm').addEventListener('click', function() {
        document.getElementById('showCompanyForm').classList.add('hidden');
        document.getElementById('userForm').classList.add('hidden');
        document.getElementById('companyForm').classList.remove('hidden');
    });

    document.getElementById('hideCompanyForm').addEventListener('click', function() {
        document.getElementById('companyForm').classList.add('hidden');
        document.getElementById('userForm').classList.remove('hidden');
        document.getElementById('showCompanyForm').classList.remove('hidden');
    });
</script>
</body>
</html>
