<?php declare(strict_types=1);

namespace App\Providers;


use App\Models\Board;
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
            Board::MORPH_NAME => Board::class,
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