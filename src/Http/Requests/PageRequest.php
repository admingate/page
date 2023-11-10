<?php

namespace Admingate\Page\Http\Requests;

use Admingate\Base\Enums\BaseStatusEnum;
use Admingate\Page\Supports\Template;
use Admingate\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PageRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:120',
            'description' => 'max:400',
            'template' => Rule::in(array_keys(Template::getPageTemplates())),
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
