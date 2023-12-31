<?php

namespace Admingate\Page\Repositories\Eloquent;

use Admingate\Base\Enums\BaseStatusEnum;
use Admingate\Page\Repositories\Interfaces\PageInterface;
use Admingate\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PageRepository extends RepositoriesAbstract implements PageInterface
{
    public function getDataSiteMap(): Collection
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->select(['id', 'name', 'updated_at'])
            ->with('slugable');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function whereIn(array $array, array $select = []): Collection
    {
        $pages = $this->model
            ->whereIn('id', $array)
            ->where('status', BaseStatusEnum::PUBLISHED);

        if (empty($select)) {
            $select = ['*'];
        }

        $data = $pages
            ->select($select)
            ->orderBy('created_at');

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getSearch(?string $query, int $limit = 10): Collection|LengthAwarePaginator
    {
        $pages = $this->model->where('status', BaseStatusEnum::PUBLISHED);
        foreach (explode(' ', $query) as $term) {
            $pages = $pages->where('name', 'LIKE', '%' . $term . '%');
        }

        $data = $pages
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getAllPages(bool $active = true): Collection
    {
        $data = $this->model;

        if ($active) {
            $data = $data->where('status', BaseStatusEnum::PUBLISHED);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
