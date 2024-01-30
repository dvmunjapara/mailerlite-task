<?php

namespace App\Controller;

use App\Model\Subscriber;
use App\Service\SubscriberService;

class SubscriberController extends Controller
{
    private SubscriberService $service;

    public function __construct()
    {
        $this->service = new SubscriberService(new Subscriber());
    }

    /**
     * @param array<mixed>|null $request
     * @return void
     */
    public function index(?array $request = null): void
    {
        $response = $this->service->listSubscribers($request);
        $total_subscribers = $this->service->getSubscribersCount();

        $this->json(['data' => $response,
            'meta' => [
                'total' => $total_subscribers,
                'page' => $request['page'] ?? 1,
                'limit' => $request['limit'] ?? 10,
                'total_pages' => ceil($total_subscribers / ($request['limit'] ?? 10))
            ]
        ]);
    }

    /**
     * @param array<string> $request
     * @return void
     */
    public function show(array $request): void
    {
        try {
            $response = $this->service->getSubscriberByEmail($request['email']);
            $this->json(['data' => $response]);
        } catch (\Throwable $e) {
            $this->json(['message' => $e->getMessage()], 404);
        }

    }

    /**
     * @param array<mixed> $request
     * @return void
     */
    public function store(array $request = []): void
    {
        try {
            $this->service->validateSubscriber($request);

            $response = $this->service->storeSubscriber($request);
            $this->json(['message' => 'Subscriber created successfully', 'data' => $response]);
        } catch (\Throwable $e) {
            $this->json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
