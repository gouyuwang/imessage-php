<?php

namespace Gouyuwang\IMessage\Consoles;


use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateClientIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imessage:id {--f|force : Skip confirmation when overwriting an existing id.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate imessage client id';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $id = Str::random(32);

        if (file_exists($path = $this->envPath()) === false) {
            return $this->displayId($id);
        }

        if (Str::contains(file_get_contents($path), 'IMESSAGE_CLIENT_ID') === false) {
            // update existing entry
            file_put_contents($path, PHP_EOL . "IMESSAGE_CLIENT_ID=$id", FILE_APPEND);
        } else {
            if ($this->isConfirmed() === false) {
                $this->comment('No changes were made to your client id.');

                return;
            }

            // create new entry
            file_put_contents($path, str_replace(
                'IMESSAGE_CLIENT_ID=' . $this->laravel['config']['imessage.credentials._id'],
                'IMESSAGE_CLIENT_ID=' . $id, file_get_contents($path)
            ));
        }

        $this->displayId($id);
    }

    /**
     * Check if the modification is confirmed.
     *
     * @return bool
     */
    protected function isConfirmed()
    {
        return $this->option('force') ? true : $this->confirm(
            'IMessage id exists. Are you sure you want to override the it?'
        );
    }

    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        return $this->laravel->basePath('.env');
    }

    /**
     * Display the key.
     *
     * @param string $id
     *
     * @return void
     */
    protected function displayId(string $id)
    {
        $this->laravel['config']['imessage.credentials._id'] = $id;

        $this->info("IMessage client id: [$id]");
    }
}
