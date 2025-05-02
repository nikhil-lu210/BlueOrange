<?php

namespace App\Repositories\Administration\Ticket;

use App\Models\Ticket\ItTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ItTicketRepository
{
    /**
     * Get users who can solve tickets with permission caching
     */
    public function getTicketSolvers($userIds): Collection
    {
        // Load users with employees in a single query
        $users = User::with(['employee', 'permissions'])
            ->whereIn('id', $userIds)
            ->whereStatus('Active')
            ->get();

        // Filter users with required permissions
        return $users->filter(function ($user) {
            return $user->hasAnyPermission(['IT Ticket Everything', 'IT Ticket Update']);
        });
    }

    /**
     * Get users for filtering with eager loading
     */
    public function getFilterUsers($userIds): Collection
    {
        return User::with(['roles', 'media', 'employee'])
            ->whereIn('id', $userIds)
            ->whereStatus('Active')
            ->get(['id', 'name']);
    }

    /**
     * Get tickets query with optimized eager loading
     */
    public function getTicketsQuery(Request $request, ?User $forUser = null)
    {
        $query = ItTicket::with([
            'creator' => function($query) {
                $query->with(['employee', 'roles', 'media']);
            },
            'solver' => function($query) {
                $query->with(['employee', 'roles', 'media']);
            }
        ])->orderByDesc('created_at');

        // Apply filters
        $this->applyUserFilter($query, $forUser);
        $this->applyRequestFilters($query, $request);

        return $query;
    }

    /**
     * Apply user-specific filters
     */
    private function applyUserFilter($query, ?User $forUser)
    {
        if ($forUser) {
            $query->where(function ($query) use ($forUser) {
                $query->where('creator_id', $forUser->id)
                    ->orWhere('solved_by', $forUser->id);
            });
        }
    }

    /**
     * Apply request-based filters
     */
    private function applyRequestFilters($query, Request $request)
    {
        // Apply solver filter
        if ($request->filled('solved_by')) {
            $query->where('solved_by', $request->solved_by);
        }

        // Apply creator filter
        if ($request->filled('creator_id')) {
            $query->where('creator_id', $request->creator_id);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply date filter
        if ($request->filled('ticket_month_year')) {
            $monthYear = Carbon::parse($request->ticket_month_year);
            $query->whereYear('created_at', $monthYear->year)
                  ->whereMonth('created_at', $monthYear->month);
        } elseif (!$request->has('filter_tickets')) {
            // Default to current month
            $query->whereYear('created_at', Carbon::now()->year)
                  ->whereMonth('created_at', Carbon::now()->month);
        }
    }
}

