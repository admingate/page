<?php

namespace Admingate\Page\Http\Controllers;

use Admingate\Page\Models\Page;
use Admingate\Page\Services\PageService;
use Admingate\Theme\Events\RenderingSingleEvent;
use Illuminate\Routing\Controller;
use SlugHelper;
use Theme;

class PublicController extends Controller
{
    public function getPage(string $slug, PageService $pageService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Page::class));

        if (! $slug) {
            abort(404);
        }

        $data = $pageService->handleFrontRoutes($slug);

        if (isset($data['slug']) && $data['slug'] !== $slug->key) {
            return redirect()->to(url(SlugHelper::getPrefix(Page::class) . '/' . $data['slug']));
        }

        event(new RenderingSingleEvent($slug));

        return Theme::scope($data['view'], $data['data'], $data['default_view'])->render();
    }
}
