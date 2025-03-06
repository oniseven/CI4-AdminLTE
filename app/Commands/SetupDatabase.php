<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class SetupDatabase extends BaseCommand {
  protected $group = 'Custom';
  protected $name = 'setup:database';
  protected $description = "Run migrations and seeders.";

  public function run(array $params) {
    // Run migrations
    CLI::write("Running migrations...", 'blue');
    command('migrate');

    // Run seeders
    CLI::write("Running seeders...", 'blue');
    command('db:seed DatabaseSeeder');

    CLI::write("Setup completed successfully!", 'green');
  }
}