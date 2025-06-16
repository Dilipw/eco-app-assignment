<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.dashboard';

    // Dashboard stats
    public $totalProducts;
    public $totalOrders;
    public $totalRevenue;

    // Charts & Reports
    public $salesChartData;
    public $revenueGrowth;

    // Listings
    public $lowStockProducts;
    public $topCategories;
    public $topProducts;
    public $latestOrders;

    public function mount(): void
    {
        // Basic Stats
        $this->totalProducts = Product::count();
        $this->totalOrders = Order::count();
        $this->totalRevenue = Order::sum('total_amount');

        // Low Stock Alerts
        $this->lowStockProducts = Product::where('stock', '<', 5)->get();

        // Top Categories by product count
        $this->topCategories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        // Latest 5 Orders
        $this->latestOrders = Order::latest()->take(5)->get();

        // Revenue Growth
        $this->calculateRevenueGrowth();

        // Sales Chart (last 30 days)
        $this->prepareSalesChart();

        // Top Selling Products
        $this->topProducts = Product::withSum('orderItems as total_quantity', 'quantity')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        // Top Categories by product count (used in pie chart)
        $this->topCategories = Category::withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        // Top Products by quantity sold (used in pie chart)
        $this->topProducts = Product::withSum('orderItems as total_quantity', 'quantity')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();
    }

    protected function calculateRevenueGrowth(): void
    {
        $revenueThisMonth = Order::whereBetween('created_at', [now()->subDays(30), now()])
            ->sum('total_amount');

        $revenueLastMonth = Order::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
            ->sum('total_amount');

        $this->revenueGrowth = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 2)
            : ($revenueThisMonth > 0 ? 100 : 0);
    }

    protected function prepareSalesChart(): void
    {
        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->salesChartData = $salesData->map(function ($item) {
            return [
                'date' => Carbon::parse($item->date)->format('M d'),
                'total' => $item->total,
            ];
        });
    }
}
