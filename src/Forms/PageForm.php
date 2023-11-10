<?php

namespace Admingate\Page\Forms;

use Admingate\Base\Enums\BaseStatusEnum;
use Admingate\Base\Forms\FormAbstract;
use Admingate\Page\Http\Requests\PageRequest;
use Admingate\Page\Models\Page;

class PageForm extends FormAbstract
{
    protected $template = 'core/base::forms.form-tabs';

    public function buildForm(): void
    {
        $this
            ->setupModel(new Page())
            ->setValidatorClass(PageRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ])
            ->add('content', 'editor', [
                'label' => trans('core/base::forms.content'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.description_placeholder'),
                    'with-short-code' => true,
                ],
            ])
            ->add('status', 'customSelect', [
                'label' => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => BaseStatusEnum::labels(),
            ])
            ->add('template', 'customSelect', [
                'label' => trans('core/base::forms.template'),
                'label_attr' => ['class' => 'control-label required'],
                'choices' => get_page_templates(),
            ])
            ->add('image', 'mediaImage', [
                'label' => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->setBreakFieldPoint('status');
    }
}
