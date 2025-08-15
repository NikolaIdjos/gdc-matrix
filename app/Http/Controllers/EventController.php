<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\IndexEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Database\Event;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Handles fetching and displaying sports events along with
 * their related markets, selections, league, and teams.
 */
class EventController extends Controller
{
    /**
     * List events with optional filters like search, status, and start date.
     *
     * @param IndexEventRequest $request
     * @return JsonResponse
     */
    public function index(IndexEventRequest $request): JsonResponse
    {
        $events = Event::with(['markets.selections', 'league', 'teams'])
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->get('search').'%');
            })
            ->when($request->has('status_id'), function ($query) use ($request) {
                $query->where('status_id', $request->get('status_id'));
            })
            ->when($request->has('starts_after'), function ($query) use ($request) {
                $query->where('scheduled_at', '>', Carbon::parse($request->get('starts_after')));
            })
            ->paginate(10);

        return response()->json(EventResource::collection($events)->response()->getData(true));
    }

    /**
     * Show a single event with its related markets, selections, league, and teams.
     *
     * @param Event $event
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        $event->load(['markets.selections', 'league', 'teams']);

        return response()->json(new EventResource($event));
    }
}
