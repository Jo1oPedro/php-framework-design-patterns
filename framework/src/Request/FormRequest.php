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
        'required' => 'notOptional',
        'string' => 'stringType',
        'int' => 'intVal',
        'greaterThan' => 'greaterThan',
        'noWhitespace' => 'noWhitespace',
        'optional' => 'optional'
    ];

    public function __call(string $name, mixed $arguments)
    {
        return Container::getInstance()->get(Request::class)->{$name}($arguments);
    }

    public function validateRequest(Request $request)
    {
        $errorsMessages = [];

        foreach($this->rules() as $key => $rule) {
            $validator = $this->createValidator($rule);

            try {
                $fieldValue = $request->all()->$key;
                $validator->assert($fieldValue);
            } catch (NestedValidationException $exception) {
                $errorsMessages[$key] = $exception->getMessages($this->mapMessages());
                foreach($errorsMessages as $key => $errorsMessage) {
                    $errorsMessages[$key] = str_replace('"' . $fieldValue . '"' , $key, $errorsMessage);
                }
            }
        }

        if(count($errorsMessages)) {
            redirect($request->server["PATH_INFO"])
                ->with('errors', $errorsMessages)
                ->send();
        }
    }

    public abstract function rules(): array;
    public abstract function messages(): array;

    public function mapMessages(): array
    {
        $mappedMessages = [];
        foreach ($this->messages() as $key => $message) {
            $mappedMessages[$key] = $message;
            if(in_array($key, array_keys(self::RULE_MAP)) && $key !== self::RULE_MAP[$key]) {
                $mappedMessages[self::RULE_MAP[$key]] = $message;
                unset($this->messages()[$key], $mappedMessages[$key]);
            }
        }
        return $mappedMessages;
    }

    private function createValidator(string $rule): Validator
    {
        $individualRules = $this->getIndividualRules($rule);
        $this->orderRules($individualRules);

        $validator = new Validator();

        $optional = false;
        if($individualRules[0] === "optional") {
            $optional = true;
        }

        foreach($individualRules as $key => $individualRule) {
            if($optional && $individualRule === "optional") {
                continue;
            }

            $args = is_array($individualRule) ? array_slice($individualRule, 1) : [];
            $method = count($args) ? $individualRule[self::RULE] : $individualRule;

            if($optional) {
                $individualValidator = new Validator();
                $validator = $validator->optional(
                    call_user_func_array(
                        [$individualValidator, $method],
                        $args
                    )
                );
                continue;
            }

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
        if(
            !in_array("optional", $individualRules) &&
            !in_array("required", $individualRules)
        ) {
            array_unshift($individualRules, "required");
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

    public function orderRules(array &$rules): void
    {
        foreach($rules as $key => $rule) {
            if($rule === "optional") {
                array_unshift($rules, $rule);
                unset($rules[$key + 1]);
            }
        }
    }
}