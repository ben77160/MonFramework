<?php

namespace Framework\Auth;

interface User
{

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return string[]
     */
    public function getRoles(): array;
}
