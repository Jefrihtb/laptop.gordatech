<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Banner;
use App\Models\Lead;
use App\Models\Product;
use App\Models\RentalPackage;
use App\Models\ServiceCatalog;
use App\Models\ServiceTicket;
use App\Services\SqlExportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products' => Product::query()->count(),
            'best_sellers_week' => Product::query()->where('is_best_seller_week', true)->count(),
            'popular_types' => Product::query()->where('is_popular_type', true)->count(),
            'spareparts_count' => Product::query()->where('product_type', 'sparepart')->count(),
            'rental_packages' => RentalPackage::query()->count(),
            'active_service_tickets' => ServiceTicket::query()->whereNotIn('status', ['completed'])->count(),
            'total_leads' => Lead::query()->count(),
            'active_banners' => Banner::query()->where('is_active', true)->count(),
            'published_articles' => Article::query()->where('is_published', true)->count(),
            'services_count' => ServiceCatalog::query()->count(),
        ];

        $recentLeads = Lead::query()->latest()->take(8)->get();
        $topProducts = Product::query()->with('category')->orderByDesc('sold_count')->take(5)->get();
        $recentTickets = ServiceTicket::query()->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentLeads', 'topProducts', 'recentTickets'));
    }

    public function exportSql(SqlExportService $exporter): Response
    {
        $sqlContent = $exporter->generateMysqlDump();
        $exporter->saveDumpToFile();

        $filename = 'cyber_gaming_store_'.now()->format('Ymd_His').'.sql';

        return response($sqlContent, 200, [
            'Content-Type' => 'application/sql; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
