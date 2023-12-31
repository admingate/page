<?php

namespace Admingate\Page\Http\Controllers;

use Admingate\Base\Events\BeforeEditContentEvent;
use Admingate\Base\Events\CreatedContentEvent;
use Admingate\Base\Events\DeletedContentEvent;
use Admingate\Base\Events\UpdatedContentEvent;
use Admingate\Base\Forms\FormBuilder;
use Admingate\Base\Http\Controllers\BaseController;
use Admingate\Base\Http\Responses\BaseHttpResponse;
use Admingate\Base\Traits\HasDeleteManyItemsTrait;
use Admingate\Page\Forms\PageForm;
use Admingate\Page\Http\Requests\PageRequest;
use Admingate\Page\Repositories\Interfaces\PageInterface;
use Admingate\Page\Tables\PageTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct(protected PageInterface $pageRepository)
    {
    }

    public function index(PageTable $dataTable)
    {
        page_title()->setTitle(trans('packages/page::pages.menu_name'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('packages/page::pages.create'));

        return $formBuilder->create(PageForm::class)->renderForm();
    }

    public function store(PageRequest $request, BaseHttpResponse $response)
    {
        $page = $this->pageRepository->createOrUpdate(array_merge($request->input(), [
            'user_id' => Auth::id(),
        ]));

        event(new CreatedContentEvent(PAGE_MODULE_SCREEN_NAME, $request, $page));

        return $response->setPreviousUrl(route('pages.index'))
            ->setNextUrl(route('pages.edit', $page->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $page = $this->pageRepository->findOrFail($id);

        page_title()->setTitle(trans('packages/page::pages.edit') . ' "' . $page->name . '"');

        event(new BeforeEditContentEvent($request, $page));

        return $formBuilder->create(PageForm::class, ['model' => $page])->renderForm();
    }

    public function update(int|string $id, PageRequest $request, BaseHttpResponse $response)
    {
        $page = $this->pageRepository->findOrFail($id);
        $page->fill($request->input());

        $page = $this->pageRepository->createOrUpdate($page);

        event(new UpdatedContentEvent(PAGE_MODULE_SCREEN_NAME, $request, $page));

        return $response
            ->setPreviousUrl(route('pages.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $page = $this->pageRepository->findOrFail($id);
            $this->pageRepository->delete($page);

            event(new DeletedContentEvent(PAGE_MODULE_SCREEN_NAME, $request, $page));

            return $response->setMessage(trans('packages/page::pages.deleted'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->pageRepository, PAGE_MODULE_SCREEN_NAME);
    }
}
