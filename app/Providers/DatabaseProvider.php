<?php declare(strict_types=1);

namespace App\Providers;


use App\Models\Event;
use App\Models\Location;
use App\Models\Team;
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
        $dbUser = getenv("DB_USER");
        $dbHost = getenv("DB_HOST");
        $dbName = getenv("DB_NAME");
        $dbPassword = getenv("DB_PASSWORD");

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
        Relation::morphMap([
            Event::MORPH_NAME => Event::class,
            Team::MORPH_NAME => Team::class,
            Location::MORPH_NAME => Location::class,
        ]);

        $manager->setAsGlobal();
        // TODO: Add event listener
        $manager->bootEloquent();

        // Share PDO connection
        $this->container->singleton(\PDO::class, function () use ($manager) {
            return $manager->getConnection()->getPdo();
        });
    }
}