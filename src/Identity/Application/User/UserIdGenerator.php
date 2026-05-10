<?php

declare(strict_types=1);

namespace Src\Identity\Application\User;

use Src\Identity\Domain\User\UserId;

interface UserIdGenerator
{
    public function generate(): UserId;
}
