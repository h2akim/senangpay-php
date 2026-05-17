<?php

declare(strict_types=1);

namespace SenangPay\Services\Common;

use JsonMapper\JsonMapperFactory;
use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Filter\WithSanitizer;

class BaseResponse extends \Laravie\Codex\Response implements Filterable
{
    use WithSanitizer;

    public function throwIfError(?string $message = null): self
    {
        $this->abortIfRequestHasFailed($message);
        return $this;
    }

    public function mapTo(string $class): object
    {
        $data = $this->toArray();
        $mapper = (new JsonMapperFactory())->bestFit();
        return $mapper->mapToClass((object) $data, $class);
    }

    /**
     * @return array<int, object>
     */
    public function mapToList(string $class, ?string $key = null): array
    {
        $data = $this->toArray();
        if ($key !== null) {
            $data = $data[$key] ?? [];
        }

        $mapper = (new JsonMapperFactory())->bestFit();
        $results = [];
        foreach ($data as $item) {
            $results[] = $mapper->mapToClass((object) $item, $class);
        }

        return $results;
    }
}
