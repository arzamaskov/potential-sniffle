<?php

declare(strict_types=1);

namespace Src\Identity\Application\User;

use RuntimeException;

final class LoginAlreadyTaken extends RuntimeException {}
