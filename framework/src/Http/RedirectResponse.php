<?php

namespace Cascata\Framework\Http;

class RedirectResponse extends Response
{
    public function __construct(string $url)
    {
        parent::__construct('', 302, ['location' => $url]);
    }

    public function send(): void
    {
        header(
            'Location:' . $this->getHeader('location'),
            true,
            $this->getStatus()
        );
        exit();
    }

    public function with(string $sessionName, mixed $sessionContent): RedirectResponse
    {
        session()->set($sessionName, $sessionContent);
        return $this;
    }

    public function withFlash(string $sessionName, string $sessionMessage): RedirectResponse
    {
        session()->setFlash($sessionName, $sessionMessage);
        return $this;
    }
}