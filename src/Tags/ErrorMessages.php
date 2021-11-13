<?php

namespace MarcoRieser\ErrorMessages\Tags;

use Illuminate\Support\ViewErrorBag;
use Statamic\Tags\Tags;

class ErrorMessages extends Tags
{
    protected ?ViewErrorBag $errorBag;

    /**
     * The {{ error_messages }} tag.
     */
    public function index(): bool|array
    {
        $this->initializeErrorBag();

        if (!$this->isPair) {
            return (bool)$this->getMessages();
        }

        if (!(bool)$this->getMessages()) {
            return [];
        }

        return $this->groupErrorsByField();
    }

    /**
     * The {{ error_messages:key }} tag.
     */
    public function wildcard($key): bool|array
    {
        $this->initializeErrorBag();

        if (!$this->isPair) {
            return (bool)$this->getMessagesByKey($key);
        }

        if (!(bool)$this->getMessagesByKey($key)) {
            return [];
        }

        return ['errors' => collect($this->getMessagesByKey($key))->map(static fn($error) => ['error' => $error])->toArray()];
    }

    protected function initializeErrorBag(): void
    {
        $this->errorBag = $this->params->get('error_bag') ?? session('errors');
    }

    protected function getMessages(): array
    {
        return $this->errorBag ? $this->errorBag->getMessages() : [];
    }

    protected function getMessagesByKey(string $key): array
    {
        return $this->errorBag ? $this->errorBag->get($key) : [];
    }

    protected function groupErrorsByField(): array
    {
        $fields = [];

        foreach ($this->getMessages() as $key => $field) {
            $errors = [];

            foreach ($field as $error) {
                $errors[] = ['error' => $error];
            }

            $fields[] = [
                'key' => $key,
                'errors' => $errors,
            ];
        }
        return ['fields' => $fields];
    }
}
