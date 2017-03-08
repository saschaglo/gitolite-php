<?php

namespace Gitolite;

/**
 * Gitolite User Class
 *
 * Project:   gitolite-php
 * File:      src/Gitolite/User.php
 *
 * Copyright (C) 2012 Rafael Goulart
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by  the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * @author  Rafael Goulart <rafaelgou@gmail.com>
 * @license GNU Lesser General Public License
 * @link    https://github.com/rafaelgou/gitolite-php
 * @see     CHANGELOG
 */
class User
{
    /**
     * @var string
     */
    protected $username = null;

    /**
     * @var string
     */
    protected $email = null;

    /**
     * @var array
     */
    protected $keys = [];


    /**
     * Set Username
     *
     * @param string $username The user name
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = (string) $username;

        return $this;
    }


    /**
     * Get Username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Set Keys
     *
     * @param array $keys An array of keys
     *
     * @return self
     */
    public function setKeys(array $keys)
    {
        $this->keys = [];

        foreach ($keys as $key) {
            $this->addKey($key);
        }

        return $this;
    }


    /**
     * Get Keys
     *
     * @return array An array of keys
     */
    public function getKeys()
    {
        return $this->keys;
    }


    /**
     * Get First Key
     *
     * @return string
     *
     * @throws \Exception if no key was found
     */
    public function getFirstKey()
    {
        if (count($this->keys) == 0) {
            throw new \Exception("No key for user {$this->getUsername()} found.");
        }

        return (string) $this->keys[0];
    }


    /**
     * Add key
     *
     * @param string $key A key
     *
     * @return self
     */
    public function addKey($key)
    {
        $this->keys[] = (string)$key;

        return $this;
    }


    /**
     * Set Email
     *
     * @param string $email An email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;

        return $this;
    }


    /**
     * Get Email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Returns key filename in form username.pub
     *
     * @param string|null $suffix
     *
     * @return string
     */
    public function renderKeyFileName($suffix = null)
    {
        return $this->username . $suffix . '.pub';
    }
}
