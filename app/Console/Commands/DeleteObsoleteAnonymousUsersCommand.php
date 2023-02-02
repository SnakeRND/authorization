<?php

namespace App\Console\Commands;

use App\Modules\Authorization\Models\User;
use App\Modules\Authorization\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Laravel\Telescope\Telescope;
use Symfony\Component\Console\Helper\ProgressBar;

class DeleteObsoleteAnonymousUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete_obsolete_anonymous_users:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаление устаревших анонимных пользователей';

    public function __construct(
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle(): int
    {
        $bar = $this->initializeProgressBar();

        $this->line('Obsolete anonymous users deleting...');

        $this->withoutTelescope(function () use ($bar) {
            $anonymousUsers = User::where('is_anonymous_user', true)->get();

            foreach ($anonymousUsers as $anonymousUser) {
                if ($anonymousUser->created_at < Carbon::now()->subDays(35)) {
                    $this->userRepository->delete($anonymousUser);
                }
            }
        });

        $bar->finish();

        $this->newLine();
        $this->info('Deleting finished!');

        return Command::SUCCESS;
    }

    private function initializeProgressBar(): ProgressBar
    {
        $bar = $this->output->createProgressBar();
        $bar->setFormat('debug');

        return $bar;
    }

    private function withoutTelescope(callable $callback): void
    {
        if (method_exists(Telescope::class, 'withoutRecording')) {
            Telescope::withoutRecording($callback);
        } else {
            call_user_func($callback);
        }
    }
}
