<?php

namespace App\Providers;

use App\Services\Interface\AuthInterface;
use App\Services\Interface\BookInterface;
use App\Services\Interface\BorrowInterface;
use App\Services\Interface\NotificationInterface;
use App\Services\Repository\AuthRepository;
use App\Services\Repository\BookRepository;
use App\Services\Repository\BorrowRepository;
use App\Services\Repository\NotificationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    //* Here we Bind all the repository with the interface *//

    public $bindings = [ 
        AuthInterface::class => AuthRepository::class,
        BookInterface::class => BookRepository::class,
        BorrowInterface::class => BorrowRepository::class,
        NotificationInterface::class => NotificationRepository::class,
    ];
}
