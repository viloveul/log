<?php

namespace Viloveul\Log;

use DateTimeInterface;

trait FormatMessageTrait
{
    /**
     * @param  $message
     * @param  array      $context
     * @return mixed
     */
    public function format($message, array $context = []): string
    {
        if (false === strpos($message, '{')) {
            return $message;
        }
        $replacements = [];
        foreach ($context as $key => $val) {
            $placeholder = '{' . $key . '}';
            if (strpos($message, $placeholder) === false) {
                continue;
            }
            if (null === $val || is_scalar($val) || (is_object($val) && method_exists($val, "__toString"))) {
                $replacements[$placeholder] = $val;
            } elseif ($val instanceof DateTimeInterface) {
                $replacements[$placeholder] = $val->format("Y-m-d\TH:i:s.uP");
            } elseif (is_object($val)) {
                $class = get_class($val);
                $replacements[$placeholder] = '[object ' . ('c' === $class[0] && 0 === strpos($class, "class@anonymous\0") ? get_parent_class($class) . '@anonymous' : $class) . ']';
            } elseif (is_array($val)) {
                $replacements[$placeholder] = 'array' . var_export($val, true);
            } else {
                $replacements[$placeholder] = '[' . gettype($val) . ']';
            }
        }
        $message = strtr($message, $replacements);
        return $message;
    }
}
