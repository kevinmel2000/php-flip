<?php

namespace Core\Transformer\Autobots;

use Core\Responses\Token as TokenResponse;
use Core\Contracts\Models\Token as TokenModelContract;

class Token extends Autobot
{
    protected $transformableClass = TokenModelContract::class;

    protected $responseClass = TokenResponse::class;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    protected function gatherResponseConstructorParameters($model): array
    {
        return [
            $this->transformBasicAttributes($model),
            $model,
            $model->getUser(),
        ];
    }

    protected function basicAttribute(): array
    {
        return $this->commonAttribute(
            ['id', 'created_at', 'updated_at'],
            [
                ['token', self::TYPE_STRING],
                ['expired_at', self::TYPE_DATETIME],
                ['user', function (TokenModelContract $model) {
                    return $this->user->transform(
                        $model->getUser()
                    );
                }],
            ]
        );
    }
}
