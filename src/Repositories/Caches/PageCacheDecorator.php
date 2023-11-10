<?php

namespace Admingate\Page\Repositories\Caches;

use Admingate\Page\Repositories\Interfaces\PageInterface;
use Admingate\Support\Repositories\Caches\CacheAbstractDecorator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PageCacheDecorator extends CacheAbstractDecorator implements PageInterface
{
    public function getDataSiteMap(): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function whereIn(array $array, array $select = []): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getSearch(?string $query, int $limit = 10): Collection|LengthAwarePaginator
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllPages(bool $active = true): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
