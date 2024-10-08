<?php /** @formatter:off */
declare(strict_types=1);

namespace App\Abstract;

use App\Traits\Model\TraitModelValidator;use Carbon\Carbon;
use Hyperf\Database\Model\Concerns\CamelCase;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

/**
 * @ModelAbstract
 * @\App\Abstract\ModelAbstract
 */
abstract class AbstractModel extends Model
{

    use CamelCase, SoftDeletes, TraitModelValidator;

    public bool $timestamps = true;

    /**
     * @var string|null $dateFormat
     */
    protected ?string $dateFormat = 'U';

    public const string CREATED_AT = 'create_time';
    public const string UPDATED_AT = 'update_time';
    public const string DELETED_AT = 'delete_time';

    /**
     * @param mixed $value
     * @return string
     * @noinspection PhpUnused
     */
    public function getCreateTimeAttribute(mixed $value): string
    {
        return Carbon::createFromTimestamp($value)->toDateTimeString();
    }
}