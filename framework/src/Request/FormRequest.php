<?php

namespace Cascata\Framework\Request;

use Cascata\Framework\GlobalContainer\Container;
use Cascata\Framework\Http\Request;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

abstract class FormRequest
{
    private const RULE = 0;
    private const RULE_MAP = [
        'string' => 'stringType',
        'int' => 'intVal'
    ];

    public function __call(string $name, mixed $arguments)
    {
        return Container::getInstance()->get(Request::class)->{$name}($arguments);
    }

    public function validateRequest(Request $request)
    {
        foreach($this->rules() as $key => $rule) {
            $validator = $this->createValidator($rule);

            try {
                $validator->assert($request->all()->$key);
            } catch (NestedValidationException $exception) {
                $errorsMessages = $exception->getMessages($this->messages());
                foreach($errorsMessages as $errorsKey => $errorsMessage) {
                    $errorsMessages[$errorsKey] = str_replace(
                        '"' . $request->all()->$key . '"',
                        '"' . $key . '"',
                        $errorsMessage);
                }

                redirect($request->server["PATH_INFO"])
                    ->with('errors', $errorsMessages)
                    ->send();
            }
        }
    }

    public abstract function rules(): array;
    public abstract function messages(): array;

    private function createValidator(string $rule): Validator
    {
        $individualRules = $this->getIndividualRules($rule);
        $validator = new Validator();
        foreach($individualRules as $individualRule) {
            $args = is_array($individualRule) ? array_slice($individualRule, 1) : [];
            $method = count($args) ? $individualRule[self::RULE] : $individualRule;

            $validator = call_user_func_array(
                [$validator, $method],
                $args
            );
        }
        return $validator;
    }

    private function getIndividualRules(string $rules): array
    {
        $individualRules = explode('|', $rules);
        if(!in_array("required", $individualRules)) {
            array_unshift($individualRules, "optional");
        }
        $individualRules = $this->mapRules($individualRules);
        return $individualRules;
    }

    private function mapRules(array $rules): array
    {
        foreach($rules as $key => $rule) {
            $rule = explode(":", $rule);

            if(count($rule) > 1) {
                foreach(array_slice($rule, 1) as $ruleKey => $value) {
                    $rules[$key] = [$rule[0] , $value];
                }
            }
            if($rule[self::RULE] === "required") {
                unset($rules[$key]);
                continue;
            }

            if(is_array($rules[$key])) {
                if(in_array($rules[$key][self::RULE], array_keys(self::RULE_MAP))) {
                    $rules[$key][self::RULE] = self::RULE_MAP[$rule[self::RULE]];
                }
                continue;
            }

            $rules[$key] = self::RULE_MAP[$rule[self::RULE]];
        }
        return $rules;
    }
}