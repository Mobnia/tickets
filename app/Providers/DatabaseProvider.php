<?php declare(strict_types=1);

namespace App\Providers;


use App\Models\Event;
use App\Models\Location;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Relations\Relation;
/**
 * Class DatabaseProvider
 *
 * @package \App\Providers
 */
class DatabaseProvider extends AbstractServiceProvider
{

    public function register()
    {
        list($dbUser, $dbHost, $dbName, $dbPassword) = $this->getDatabaseDetails();

        $manager = new Manager($this->container);
        $manager->addConnection([
            'driver' => "mysql",
            'host' => $dbHost,
            'database' => $dbName,
            'username' => $dbUser,
            'password' => $dbPassword,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ], 'default');
        $manager->getDatabaseManager()->setDefaultConnection('default');

        // Register polymorph mappings from name to model
        $this->registerPolymorphicMappings();

        $manager->setAsGlobal();
        // TODO: Add event listener
        $manager->bootEloquent();

        // Share PDO connection
        $this->container->singleton(\PDO::class, function () use ($manager) {
            return $manager->getConnection()->getPdo();
        });
    }

    private function getDatabaseDetails(): array
    {
        $dbUser = getenv("DB_USER");
        $dbHost = getenv("DB_HOST");
        $dbName = getenv("DB_NAME");
        $dbPassword = getenv("DB_PASSWORD");
        return [$dbUser, $dbHost, $dbName, $dbPassword];
    }

    private function registerPolymorphicMappings(): void
    {
        Relation::morphMap([
            Event::MORPH_NAME => Event::class,
            Team::MORPH_NAME => Team::class,
            Location::MORPH_NAME => Location::class,
            Ticket::MORPH_NAME => Ticket::class,
            User::MORPH_NAME => User::class
        ]);
    }
}