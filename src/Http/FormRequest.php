<?php

namespace Overphp\LaravelSkeleton\Http;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Exception;
use Illuminate\Support\Arr;

class FormRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @param Factory $factory
     * @return Validator
     * @throws Exception
     */
    public function validator(Factory $factory): Validator
    {
        return $factory->make(
            $this->validationData(),
            $this->validationRules(),
            $this->validationMessages(),
            $this->validationAttributes()
        );
    }

    /**
     * validator rules
     *
     * @return array
     * @throws Exception
     */
    protected function validationRules(): array
    {
        $method = $this->route()->getActionMethod() . 'Rules';

        if (!method_exists($this, $method)) {
            throw new Exception(sprintf('Method %s not exist in Class %s', $method, get_called_class()));
        }

        return call_user_func([$this, $method]);
    }

    /**
     * validator messages
     *
     * @return array
     */
    protected function validationMessages(): array
    {
        $method = $this->route()->getActionMethod() . 'Messages';

        return method_exists($this, $method) ? call_user_func([$this, $method]) : $this->messages();
    }

    /**
     * validator attributes
     *
     * @return array
     */
    protected function validationAttributes(): array
    {
        $method = $this->route()->getActionMethod() . 'Attributes';

        return method_exists($this, $method) ? call_user_func([$this, $method]) : $this->attributes();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getValidatedData(): array
    {
        $keys = array_keys($this->validationRules());
        $keys = array_map(function ($key) {
            return Arr::first(explode('.', $key));
        }, $keys);

        return $this->only(array_unique($keys));
    }
}
