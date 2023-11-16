<?php

namespace Cascata\Framework\Http;

class Kernel
{
    public function handle(Request $request): Response
    {
        $content = '<h1>Hello world</h1>';

        return new Response($content);
    }
}