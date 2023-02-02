<?php

namespace App\Providers;

use App\Listeners\ActivateGiftCard;
use App\Listeners\ChangeOrderStatus1C;
use App\Listeners\ConfirmCashTransaction;
use App\Listeners\CreateYandexOrder;
use App\Listeners\FiscalizeTransaction;
use App\Listeners\SendPayment1C;
use App\Packages\Events\TransactionBinding;
use App\Packages\Events\TransactionCreated;
use App\Packages\Events\TransactionFiscalized;
use App\Packages\Events\TransactionStatusChanged;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TransactionCreated::class => [
            ConfirmCashTransaction::class,
        ],
        TransactionStatusChanged::class => [
            ActivateGiftCard::class,
            FiscalizeTransaction::class,
            ChangeOrderStatus1C::class,
        ],
        TransactionFiscalized::class => [
            SendPayment1C::class,
        ],
        TransactionBinding::class => [
            CreateYandexOrder::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
