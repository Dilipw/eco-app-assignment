<x-filament::page>
    <!-- 🧭 Dashboard Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">📊 Admin Overview</h2>
        <p class="text-sm text-gray-500">Insight into your store’s latest performance</p>
    </div>

    <!-- 🔢 Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach ([['Total Products', $totalProducts, '📦', 'blue'], ['Total Orders', $totalOrders, '🧾', 'green'], ['Total Revenue', '₹' . number_format($totalRevenue, 2), '💰', 'rose']] as [$label, $value, $icon, $color])
            <x-filament::card
                class="p-6 rounded-2xl bg-gradient-to-br from-{{ $color }}-50 to-{{ $color }}-100 shadow hover:shadow-md transition">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">{{ $label }}</p>
                        <p class="text-4xl font-extrabold text-{{ $color }}-700 mt-2">{{ $value }}</p>
                    </div>
                    <div class="text-{{ $color }}-500 text-3xl">{{ $icon }}</div>
                </div>
            </x-filament::card>
        @endforeach
    </div>

    <!-- 📈 Revenue Growth -->
    <x-filament::card class="mb-8 p-6 rounded-2xl bg-white shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">📈 Revenue Growth</h3>
            <span class="text-2xl font-bold {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $revenueGrowth }}%
            </span>
        </div>
        <p class="text-sm text-gray-500 mt-2">Compared to last 30 days</p>
    </x-filament::card>
    <!-- 🏷️ Pie + Bar Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <x-filament::card class="p-6 rounded-2xl shadow bg-white">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">🏷️ Top Categories (Product Count)</h3>
            <canvas id="categoryPieChart" height="250"></canvas>
        </x-filament::card>

        <x-filament::card class="p-6 rounded-2xl shadow bg-white">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Top Selling Products (Bar Chart)</h3>
            <canvas id="topProductsChart" height="250"></canvas>
        </x-filament::card>
    </div>
    <!-- 📅 Sales Trend -->
    <x-filament::card class="mb-8 p-6 rounded-2xl bg-white shadow-sm hover:shadow-md transition">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📅 Sales Trend (Last 30 Days)</h3>
        <canvas id="salesChart" height="100"></canvas>
    </x-filament::card>



    <!-- 🔥 Top Selling Products -->
    <x-filament::section label="🔥 Top Selling Products" class="mb-8 p-6 rounded-2xl shadow bg-white">
        <ul class="space-y-3">
            @foreach ($topProducts as $product)
                <li class="flex justify-between items-center border-b pb-2">
                    <span class="text-primary-700 font-medium">{{ $product->name }}</span>
                    <span class="text-sm text-gray-600">Sold: {{ $product->total_quantity ?? 0 }}</span>
                </li>
            @endforeach
        </ul>
    </x-filament::section>

    <!-- ⚠️ Low Stock Alerts -->
    <x-filament::section label="⚠️ Low Stock Alerts" class="mb-8 p-6 rounded-2xl shadow bg-white">
        @forelse($lowStockProducts as $product)
            <div class="p-3 bg-red-50 border border-red-200 rounded-md mb-2 flex justify-between">
                <span class="text-red-600 font-medium">{{ $product->name }}</span>
                <span class="text-sm font-semibold text-red-500">Stock: {{ $product->stock }}</span>
            </div>
        @empty
            <div class="p-3 bg-green-50 border border-green-200 rounded-md text-green-600 font-medium">
                ✅ All products are well stocked.
            </div>
        @endforelse
    </x-filament::section>

    <!-- 🏷️ Top Categories List -->
    <x-filament::section label="🏷️ Top Categories" class="mb-8 p-6 rounded-2xl shadow bg-white">
        <ul class="space-y-2">
            @foreach ($topCategories as $category)
                <li class="flex justify-between items-center">
                    <span class="text-primary-700 font-medium">{{ $category->name }}</span>
                    <span class="text-sm text-gray-500">{{ $category->products_count }} Products</span>
                </li>
            @endforeach
        </ul>
    </x-filament::section>

    <!-- 📊 Chart.js CDN & Scripts -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Line Chart: Sales Trend
            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($salesChartData->pluck('date')) !!},
                    datasets: [{
                        label: 'Revenue (₹)',
                        data: {!! json_encode($salesChartData->pluck('total')) !!},
                        fill: true,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        tension: 0.4,
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: context => `₹${context.parsed.y}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '₹' + value
                            }
                        }
                    }
                }
            });

            // Pie Chart: Top Categories
            new Chart(document.getElementById('categoryPieChart'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($topCategories->pluck('name')) !!},
                    datasets: [{
                        data: {!! json_encode($topCategories->pluck('products_count')) !!},
                        backgroundColor: ['#60a5fa', '#f472b6', '#34d399', '#fbbf24', '#a78bfa', '#f87171'],
                    }]
                },
                options: {
                    animation: {
                        animateScale: true
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Bar Chart: Top Selling Products
            new Chart(document.getElementById('topProductsChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topProducts->pluck('name')) !!},
                    datasets: [{
                        label: 'Units Sold',
                        data: {!! json_encode($topProducts->pluck('total_quantity')) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    animation: {
                        duration: 800
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: context => `Sold: ${context.parsed.x}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Units Sold'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Product Name'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-filament::page>
