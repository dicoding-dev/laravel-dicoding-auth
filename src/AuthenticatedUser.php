<?php

namespace DicodingDev\LaravelDicodingAuth;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\Contracts\User;

class AuthenticatedUser implements Authenticatable
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $name,
        public readonly string $nickname,
        public readonly string $avatar,
        public readonly bool $isVerified,
        public readonly string $token,
        public readonly Carbon $tokenExpiredAt,
    ) {
    }

    public static function fromOauthUser(User $user): self
    {
        return new self(
            id: $user->getId(),
            email: $user->getEmail(),
            name: $user->getName(),
            nickname: $user->getNickname(),
            avatar: $user->getAvatar(),
            isVerified: $user->isVerified ?? false,
            token: $user->token,
            tokenExpiredAt: Carbon::now()->addSeconds($user->expiresIn ?? 0),
        );
    }

    public static function cacheKey(int $userId): string
    {
        return "user:$userId";
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): int
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return null;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // We don't implement remember feature for now
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
