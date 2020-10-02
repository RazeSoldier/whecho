<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * 用于创建用户。目前只能从命令行创建新用户。
 * @package App\Console\Commands
 */
class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--name= : Username} {--passwd= : Password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to create new user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $validator = Validator::make($this->options(), [
            'name' => 'required|string|max:255',
            'passwd' => 'required|string',
        ]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return 1;
        }
        $data = $validator->getData();
        $this->createUser($data['name'], $data['passwd']);
        $this->info("{$data['name']} successful created");
        return 0;
    }

    private function createUser(string $username, string $password): void
    {
        User::create([
            'name' => $username,
            'password' => Hash::make($password),
        ]);
    }
}
