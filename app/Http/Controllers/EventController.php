<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\IndexEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Database\Event;
use App\Services\ApiCache;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Handles fetching and displaying sports events along with
 * their related markets, selections, league, and teams.
 *
 * Uses ApiCache to store event listings and reduce database queries.
 */
class EventController extends Controller
{
    protected ApiCache $cache;

    /**
     * Inject ApiCache service.
     */
    public function __construct(ApiCache $cache)
    {
        $this->cache = $cache;
    }
    /**
     * List events with optional filters like search, status, and start date.
     *
     * Cached for 5 minutes using ApiCache.
     */
    public function index(IndexEventRequest $request): JsonResponse
    {
        $cacheKey = $this->cache->generateCacheKey(
            'api_cache_events_',
            json_encode(
                [
                    'search' => $request->get('search', ''),
                    'status_id' => $request->get('status_id', ''),
                    'starts_after' => $request->get('starts_after', ''),
                    'page' => $request->get('page', 1),
                ]
            )
        );

        $eventsData = $this->cache->remember(
            $cacheKey,
            300,
            function () use ($request): array {
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
                    ->orderBy('scheduled_at', 'asc')
                    ->paginate(10);

                return EventResource::collection($events)->response()->getData(true);
            }
        );

        return response()->json($eventsData);
    }

    /**
     * Show a single event with its related markets, selections, league, and teams.
     */
    public function show(Event $event): JsonResponse
    {
        $event->load(['markets.selections', 'league', 'teams']);

        return response()->json(new EventResource($event));
    }
}
