<?php

namespace Admingate\Page\Models;

use Admingate\ACL\Models\User;
use Admingate\Base\Casts\SafeContent;
use Admingate\Base\Enums\BaseStatusEnum;
use Admingate\Base\Models\BaseModel;
use Admingate\Revision\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends BaseModel
{
    use RevisionableTrait;

    protected $table = 'pages';

    protected bool $revisionEnabled = true;

    protected bool $revisionCleanup = true;

    protected int $historyLimit = 20;

    protected array $dontKeepRevisionOf = ['content'];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'content' => SafeContent::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    protected $fillable = [
        'name',
        'content',
        'image',
        'template',
        'description',
        'status',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
