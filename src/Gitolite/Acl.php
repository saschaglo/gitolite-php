<?php

namespace Gitolite;

/**
 * Gitolite ACL Class
 *
 * Project:   gitolite-php
 * File:      src/Gitolite/Acl.php
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
class Acl
{
    private $allowedPermissions = [
        'C',
        'R',
        'RW',
        'RW+',
        '-',
        'RWC',
        'RW+C',
        'RWD',
        'RW+D',
        'RWCD',
        'RW+CD',
        'RWDC',
        'RW+DC',
    ];

    /**
     * @var string
     */
    protected $permission = null;

    /**
     * @var string
     */
    protected $refexes = '';

    /**
     * @var User[]
     */
    protected $users = [];

    /**
     * @var Team[]
     */
    protected $teams = [];


    /**
     * Set Permission
     *
     * Validates the following permissions:
     * C, R, RW, RW+, -, RWC, RW+C, RWD, RW+D, RWCD, RW+CD, RWDC, RW+DC
     *
     * @param string $permission A permission
     *
     * @return self
     *
     * @throws \Exception if permission is unknown
     */
    public function setPermission($permission)
    {
        $permission = (string) $permission;
        if (!in_array($permission, $this->allowedPermissions)) {
            throw new \Exception("Unknown permission '{$permission}'.");
        }
        $this->permission = $permission;

        return $this;
    }


    /**
     * Get Permission
     *
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }


    /**
     * Set Refexes
     *
     * @param string $refexes A refex
     *
     * @return self
     */
    public function setRefexes($refexes)
    {
        $this->refexes = $refexes;

        return $this;
    }


    /**
     * Get Refexes
     *
     * @return string
     */
    public function getRefexes()
    {
        return $this->refexes;
    }


    /**
     * Set Users
     *
     * @param User[] $users An array of user objects
     *
     * @return self
     */
    public function setUsers(array $users)
    {
        $this->users = [];

        foreach ($users as $user) {
            $this->addUser($user);
        }

        return $this;
    }


    /**
     * Get Users
     *
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }


    /**
     * Add user
     *
     * @param User $user A user object
     *
     * @return self
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }


    /**
     * Set Teams
     *
     * @param Team[] $teams An array of team objects
     *
     * @return self
     */
    public function setTeams(array $teams)
    {
        $this->teams = [];

        foreach ($teams as $team) {
            $this->addTeam($team);
        }

        return $this;
    }


    /**
     * Get Teams
     *
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }


    /**
     * Add Team
     *
     * @param Team $team A team object
     *
     * @return self
     */
    public function addTeam(Team $team)
    {
        $this->teams[] = $team;

        return $this;
    }


    /**
     * Returns acl line
     *
     * Format: <permission> <zero or more refexes> = <one or more users/user teams>
     *
     * @param bool $newLine Include a new line (default true)
     *
     * @return string
     *
     * @throws \Exception if permissions are not defined
     * @throws \Exception if no teams and users exist
     */
    public function render($newLine = true)
    {
        if (null === $this->permission) {
            throw new \Exception("Permission not defined.");
        }

        if (count($this->teams) == 0 && count($this->users) == 0) {
            throw new \Exception("No users neither teams defined.");
        }

        $teams = [];
        foreach ($this->getTeams() as $team) {
            $teams[] = $team->getFormattedName();
        }

        $users = [];
        foreach ($this->getUsers() as $user) {
            $users[] = $user->getUsername();
        }

        $refexes = (!empty($this->refexes)) ? $this->refexes . ' ' : '';

        return $this->permission . ' '
            . $refexes
            . '= '
            . implode(' ', $users) . ' '
            . implode(' ', $teams)
            . ($newLine ? PHP_EOL : '');
    }


    /**
     * Returns a md5 checksum created from the rendered acl line
     *
     * @return string
     */
    public function getChecksum()
    {
        return md5($this->render(false));
    }
}
