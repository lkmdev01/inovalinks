<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkVisit;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class StatisticsController extends Controller
{
    /**
     * Display dashboard statistics.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $profile = $user->profile;
        
        if (!$profile) {
            return Inertia::render('Statistics/Index', [
                'totalVisits' => 0,
                'recentVisits' => [],
                'topLinks' => [],
                'deviceStats' => [],
                'dailyStats' => [],
                'hasData' => false,
            ]);
        }

        // Período de filtro (padrão: último mês)
        $period = $request->input('period', '30days');
        $startDate = $this->getStartDate($period);
        
        // Total de visitas
        $totalVisits = $profile->visits()->count();
        
        // Visitas recentes 
        $recentVisits = $profile->visits()
            ->with('link')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($visit) {
                return [
                    'id' => $visit->id,
                    'link_title' => $visit->link->title,
                    'link_url' => $visit->link->url,
                    'date' => $visit->created_at->format('d/m/Y H:i'),
                    'device' => $visit->device_type,
                    'country' => $visit->country,
                    'city' => $visit->city,
                ];
            });
        
        // Top Links
        $topLinks = $profile->links()
            ->where('is_active', true)
            ->withCount(['visits' => function($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }])
            ->orderByDesc('visits_count')
            ->take(5)
            ->get()
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'title' => $link->title,
                    'url' => $link->url,
                    'visits' => $link->visits_count,
                ];
            });
        
        // Estatísticas por dispositivo
        $deviceStats = $profile->visits()
            ->where('created_at', '>=', $startDate)
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->get()
            ->map(function ($stat) {
                return [
                    'device' => $stat->device_type ?: 'desconhecido',
                    'count' => $stat->count,
                ];
            });
        
        // Estatísticas diárias
        $dailyStats = $this->getDailyStats($profile->id, $startDate);
        
        return Inertia::render('Statistics/Index', [
            'totalVisits' => $totalVisits,
            'recentVisits' => $recentVisits,
            'topLinks' => $topLinks,
            'deviceStats' => $deviceStats,
            'dailyStats' => $dailyStats,
            'period' => $period,
            'hasData' => $totalVisits > 0,
        ]);
    }
    
    /**
     * Display detailed statistics for a specific link.
     */
    public function linkDetails(Link $link): Response
    {
        $this->authorize('view', $link);
        
        $totalVisits = $link->visits()->count();
        
        // Visitas recentes
        $recentVisits = $link->visits()
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($visit) {
                return [
                    'id' => $visit->id,
                    'date' => $visit->created_at->format('d/m/Y H:i'),
                    'device' => $visit->device_type,
                    'country' => $visit->country,
                    'city' => $visit->city,
                ];
            });
            
        // Estatísticas por dispositivo
        $deviceStats = $link->visits()
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->get()
            ->map(function ($stat) {
                return [
                    'device' => $stat->device_type ?: 'desconhecido',
                    'count' => $stat->count,
                ];
            });
            
        // Estatísticas por localização
        $locationStats = $link->visits()
            ->whereNotNull('country')
            ->select('country', DB::raw('count(*) as count'))
            ->groupBy('country')
            ->orderByDesc('count')
            ->take(10)
            ->get();
            
        // Estatísticas diárias dos últimos 30 dias
        $dailyStats = $this->getLinkDailyStats($link->id);
        
        return Inertia::render('Statistics/LinkDetails', [
            'link' => $link,
            'totalVisits' => $totalVisits,
            'recentVisits' => $recentVisits,
            'deviceStats' => $deviceStats,
            'locationStats' => $locationStats,
            'dailyStats' => $dailyStats,
            'hasData' => $totalVisits > 0,
        ]);
    }
    
    /**
     * Get the start date based on the selected period.
     */
    private function getStartDate(string $period): Carbon
    {
        return match($period) {
            '7days' => Carbon::now()->subDays(7),
            '14days' => Carbon::now()->subDays(14),
            '30days' => Carbon::now()->subDays(30),
            '90days' => Carbon::now()->subDays(90),
            '1year' => Carbon::now()->subYear(),
            'all' => Carbon::now()->subYears(10),
            default => Carbon::now()->subDays(30),
        };
    }
    
    /**
     * Get daily statistics for a profile.
     */
    private function getDailyStats(int $profileId, Carbon $startDate): array
    {
        $stats = LinkVisit::where('profile_id', $profileId)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Preencher dias faltantes com zero
        $result = [];
        $currentDate = clone $startDate;
        $endDate = Carbon::now();
        
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $found = $stats->firstWhere('date', $dateString);
            
            $result[] = [
                'date' => $currentDate->format('d/m'),
                'count' => $found ? $found->count : 0,
            ];
            
            $currentDate->addDay();
        }
        
        return $result;
    }
    
    /**
     * Get daily statistics for a specific link.
     */
    private function getLinkDailyStats(int $linkId): array
    {
        $startDate = Carbon::now()->subDays(30);
        
        $stats = LinkVisit::where('link_id', $linkId)
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Preencher dias faltantes com zero
        $result = [];
        $currentDate = clone $startDate;
        $endDate = Carbon::now();
        
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $found = $stats->firstWhere('date', $dateString);
            
            $result[] = [
                'date' => $currentDate->format('d/m'),
                'count' => $found ? $found->count : 0,
            ];
            
            $currentDate->addDay();
        }
        
        return $result;
    }
}
