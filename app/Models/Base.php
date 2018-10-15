<?php declare(strict_types=1);

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
/**
 * Class Base
 *
 * @package \App\Models
 */
class Base extends Model
{
    // No more `created_at` and `updated_at`
    public $timestamps = false;
}