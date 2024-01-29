<?php

namespace App\Service;

use App\Cache\Cache;
use App\DTO\SubscriberDTO;
use App\Enums\SubscriberStatus;
use App\Exceptions\SubscriberExistsException;
use App\Exceptions\SubscriberNotFoundException;
use App\Exceptions\ValidationException;
use App\Model\Subscriber;
use App\Request\Validator;

class SubscriberService
{
    private readonly Cache $cache;

    public function __construct(private readonly Subscriber $subscriber)
    {
        $this->cache = new Cache();
    }

    /**
     * @param array<mixed> $request
     * @return array<mixed>
     */
    public function listSubscribers(?array $request = null): array
    {
        $page = $request['page'] ?? 1;
        $limit = $request['limit'] ?? 10;
        $from = ($page - 1) * $limit;

        return $this->subscriber->getSubscribers($from, $limit);
    }

    /**
     * @param array<mixed> $request
     * @return array<mixed>
     * @throws \Throwable
     */
    public function storeSubscriber(array $request): array
    {
        try {

            $response = $this->subscriber->store(new SubscriberDTO(
                $request['email'],
                $request['name'],
                $request['last_name'],
                SubscriberStatus::from($request['status'])
            ));

            $this->cache->set("subscriber:{$response['email']}", $response);

            return $response;

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * @param $email
     * @return array<mixed>
     * @throws SubscriberNotFoundException
     */
    public function getSubscriberByEmail(string $email): array
    {
        if ($this->cache->exists("subscriber:$email")) {
            return $this->cache->get("subscriber:$email");
        }

        $subscriber = $this->subscriber->getSubscriberByEmail($email);

        if ($subscriber) {
            $this->cache->set("subscriber:$email", $subscriber);
            return $subscriber;
        }

        throw new SubscriberNotFoundException();
    }

    public function getSubscribersCount(): int
    {
        return $this->subscriber->getSubscribersCount();
    }

    /**
     * @param array<mixed> $request
     * @return bool
     * @throws SubscriberExistsException
     * @throws ValidationException
     */
    public function validateSubscriber(array $request): bool
    {
        $validator = new Validator();

        $validator->validateName($request['name'] ?? "");
        $validator->validateLastName($request['last_name'] ?? "");
        $validator->validateEmail($request['email'] ?? "");
        $validator->validateStatus($request['status'] ?? null);

        if ($validator->hasErrors()) {
            $errors = $validator->getErrors();

            throw new ValidationException($errors[0]);
        }

        $subscriber_exists =
            $this->cache->exists("subscriber:{$request['email']}") ||
            $this->subscriber->getSubscriberCountByEmail($request['email']);

        if ($subscriber_exists) {
            throw new SubscriberExistsException();
        }

        return true;

    }
}
