<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>eCommerce Admin Backend – NextDigits</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out both;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 text-gray-800 font-sans min-h-screen">

    <div class="max-w-5xl mx-auto py-12 px-4">
        <!-- Header -->
        <div class="text-center mb-12 animate-fadeInUp">
            <h1 class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-blue-600 to-indigo-500 text-transparent bg-clip-text">
                🛍️ NextDigits – eCommerce Admin Panel
            </h1>
            <p class="text-gray-600 mt-3 text-lg">Crafted with Laravel 12 + Filament</p>
            <div class="mt-6">
                @auth
                    <a href="{{ url('/admin/dashboard') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full shadow transition duration-300 transform hover:scale-105">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ url('/admin/login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full shadow transition duration-300 transform hover:scale-105">
                        Login to Admin Panel
                    </a>
                @endauth
            </div>
        </div>

        <!-- Project Achievements -->
        <div class="space-y-10 animate-fadeInUp">
            <div class="bg-white rounded-xl shadow-lg p-6 transition hover:shadow-2xl hover:scale-[1.01]">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">✅ Project Features</h2>

                <div class="grid md:grid-cols-2 gap-6 text-gray-700">
                    <div>
                        <h3 class="text-lg font-bold mb-2">📁 Category Management</h3>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Create, edit, and delete categories</li>
                            <li>Slug generation, search & sort support</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">📦 Product Management</h3>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Assign category, stock, and price</li>
                            <li>Stock validation to prevent negatives</li>
                            <li>Display stock in product listing</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">🧾 Order System</h3>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Customer details with email</li>
                            <li>Add products via repeater field</li>
                            <li>Auto price fetch & quantity-based total</li>
                            <li>Stock check & reduction logic on save</li>
                            <li>Correct adjustment on order updates</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">📬 Email Notifications</h3>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Order confirmation email to customer</li>
                            <li>Includes product and total summary</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">📊 Dashboard</h3>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Charts for orders and revenue</li>
                            <li>Recent orders & top products section</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">🔧 Technical Stack</h3>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Laravel 12, PHP 8.2, Filament 3</li>
                            <li>Blade, Mailable, SQLite, Tailwind CSS</li>
                            <li>Excel export, conditional logic</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 text-center transition hover:shadow-2xl hover:scale-[1.01]">
                <h3 class="text-xl font-semibold mb-2 text-gray-800">📦 Project Delivery & Notes</h3>
                <p class="text-gray-600 text-sm">
                    The project is fully functional with a preloaded SQLite database. Hosted on GitHub with clear commit history and modular resource structure.
                </p>
            </div>

            <div class="text-center text-sm text-gray-500 pt-6">
                Built with ❤️ using Laravel & Filament — <strong>NextDigits</strong> 🚀
            </div>
        </div>
    </div>

</body>
</html>
