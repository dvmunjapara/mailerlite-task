<?php

namespace App\Request;

use App\Enums\SubscriberStatus;

class Validator {

    /**
     * @var array<string>
     */
    protected array $errors = [];

    public function validateName(?string $name): void {

        if (empty($name)) {
            $this->errors[] = 'Name is required';
        } elseif (!ctype_alpha($name)) {
            $this->errors[] = 'Please enter valid name';
        }
    }

    public function validateLastName(?string $lastName): void {
        if (empty($lastName)) {
            $this->errors[] = 'Last name is required';
        } elseif (!ctype_alpha($lastName)) {
            $this->errors[] = 'Please enter valid last name';
        }
    }

    public function validateEmail(?string $email): void {
        if (empty($email)) {
            $this->errors[] = 'Email is required';
        } else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Please enter valid email';
        }
    }

    public function validateStatus(?int $status): void {

        if (!isset($status)) {
            $this->errors[] = 'Status is required';
        } elseif (!SubscriberStatus::tryFrom($status)) {
            $this->errors[] = 'Please select correct status';
        }
    }

    public function hasErrors(): bool {
        return !empty($this->errors);
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array {
        return $this->errors;
    }
}

