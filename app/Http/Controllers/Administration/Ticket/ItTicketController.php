<?php

namespace App\Http\Controllers\Administration\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Ticket\StoreItTicketRequest;
use App\Http\Requests\Administration\Ticket\UpdateItTicketRequest;
use App\Http\Requests\Administration\Ticket\UpdateItTicketStatusRequest;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Models\Ticket\ItTicket;
use App\Models\User;
use App\Notifications\Administration\Tickets\ItTicket\ItTicketCommentNotification;
use App\Repositories\Administration\Ticket\ItTicketRepository;
use App\Services\Administration\Ticket\ItTicketService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ItTicketController extends Controller
{
    protected $repository;
    protected $service;

    public function __construct(ItTicketRepository $repository, ItTicketService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get auth user and interactions once
        $authUser = auth()->user();
        $userIds = $this->getUserInteractionIds($authUser);

        // Get data for view
        $ticketSolvers = $this->repository->getTicketSolvers($userIds);
        $users = $this->repository->getFilterUsers($userIds);
        $itTickets = $this->repository->getTicketsQuery($request)->get();

        return view('administration.ticket.it_ticket.index', compact('itTickets', 'ticketSolvers', 'users'));
    }

    /**
     * Display a listing of the resource for the authenticated user.
     */
    public function my(Request $request)
    {
        // Get auth user and interactions once
        $authUser = auth()->user();
        $userIds = $this->getUserInteractionIds($authUser);

        // Get data for view
        $ticketSolvers = $this->repository->getTicketSolvers($userIds);
        $itTickets = $this->repository->getTicketsQuery($request, $authUser)->get();

        return view('administration.ticket.it_ticket.my', compact('itTickets', 'ticketSolvers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administration.ticket.it_ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItTicketRequest $request)
    {
        try {
            $itTicket = $this->service->createTicket(
                $request->validated(),
                auth()->user()
            );

            toast('Ticket Created Successfully.', 'success');
            return redirect()->route('administration.ticket.it_ticket.show', ['it_ticket' => $itTicket]);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItTicket $itTicket)
    {
        // Load itTicket with its relations
        $itTicket = ItTicket::with([
                'creator.roles',
                'creator.employee',
                'solver.roles',
                'solver.employee',
                'comments' => function ($comment) {
                    $comment->with([
                        'commenter.roles',
                        'commenter.employee',
                    ])->orderByDesc('created_at');
                }
            ])->whereId($itTicket->id)->firstOrFail();

        $this->service->updateSeenBy($itTicket, auth()->user());
        return view('administration.ticket.it_ticket.show', compact('itTicket'));
    }

    /**
     * Update status to Running
     */
    public function markAsRunning(ItTicket $itTicket)
    {
        try {
            $this->service->markAsRunning($itTicket, auth()->user());

            toast('Ticket Mark As Running.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update status to Solved or Canceled
     */
    public function updateStatus(UpdateItTicketStatusRequest $request, ItTicket $itTicket)
    {
        try {
            $this->service->updateStatus($itTicket, $request->validated(), auth()->user());

            toast('Ticket Mark As '. $request->status, 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItTicket $itTicket)
    {
        abort_if($itTicket->status !== 'Pending', 403, 'You Cannot Edit This IT Ticket.');
        return view('administration.ticket.it_ticket.edit', compact('itTicket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItTicketRequest $request, ItTicket $itTicket)
    {
        try {
            $this->service->updateTicket($itTicket, $request->validated());

            toast('Ticket Updated Successfully.', 'success');
            return redirect()->route('administration.ticket.it_ticket.show', ['it_ticket' => $itTicket]);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store IT-Ticket Comment
     */
    public function storeComment(CommentStoreRequest $request, ItTicket $itTicket)
    {
        try {
            DB::transaction(function () use ($request, $itTicket) {
                // Create the comment
                $itTicket->comments()->create([
                    'comment' => $request->comment
                ]);

                // Send notifications to relevant users
                $this->notifyTicketComment($itTicket, auth()->user());
            }, 3);

            toast('Comment Submitted Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItTicket $itTicket)
    {
        try {
            $itTicket->delete();

            toast('IT Ticket deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Get user interaction IDs with caching
     */
    private function getUserInteractionIds($user)
    {
        $cacheKey = 'user_interactions_' . $user->id;

        return Cache::remember($cacheKey, now()->addMinutes(10), function() use ($user) {
            return $user->user_interactions->pluck('id')->toArray();
        });
    }

    /**
     * Handle exceptions consistently
     */
    private function handleException(Exception $e)
    {
        return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
    }

    
    /**
     * Send notifications to relevant users about a new comment
     *
     * @param ItTicket $itTicket The ticket that received a comment
     * @param User $commenter The user who made the comment
     * @return void
     */
    private function notifyTicketComment(ItTicket $itTicket, User $commenter): void
    {
        // Get users who should receive notifications
        $notifiableUserIds = [];

        // Add ticket creator if not the commenter
        if ($itTicket->creator_id != $commenter->id) {
            $notifiableUserIds[] = $itTicket->creator_id;
        }

        // Add ticket solver if assigned and not the commenter
        if ($itTicket->solved_by && $itTicket->solved_by != $commenter->id) {
            $notifiableUserIds[] = $itTicket->solved_by;
        }

        // Add users with specific permissions
        $permissionUsers = User::whereStatus('Active')
            ->get()
            ->filter(function ($user) use ($commenter) {
                return $user->id != $commenter->id &&
                       $user->hasAnyPermission(['IT Ticket Everything', 'IT Ticket Update']);
            })
            ->pluck('id')
            ->toArray();

        // Merge and remove duplicates
        $notifiableUserIds = array_unique(array_merge($notifiableUserIds, $permissionUsers));

        // Get the user models and send notifications
        User::whereIn('id', $notifiableUserIds)->get()
            ->each(function ($user) use ($itTicket, $commenter) {
                $user->notify(new ItTicketCommentNotification($itTicket, $commenter));
            });
    }
}


