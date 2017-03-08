<?php

namespace Gitolite;

/**
 * Gitolite Team Class
 *
 * Project:   gitolite-php
 * File:      src/Gitolite/Team.php
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
class Team
{
    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var User[]
     */
    protected $users = [];

    /**
     * @var Team[]
     */
    protected $teams = [];


    /**
     * Set Name
     *
     * @param string $name The team name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }


    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Get Formated Name (as @team)
     *
     * @return string
     */
    public function getFormattedName()
    {
        return '@' . $this->name;
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
     * @param User $user An user object
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
     * Returns team group line
     *
     * Format: @<team_name> = <user 1> <user 2> <user 3> <user 'n'>
     *
     * @param bool $newLine Include a new line (default true)
     *
     * @return string
     */
    public function render($newLine = true)
    {
        $users = [];
        foreach ($this->getUsers() as $user) {
            $users[] = $user->getUsername();
        }

        $teams = [];
        foreach ($this->getTeams() as $team) {
            $teams[] = $team->getFormattedName();
        }

        return $this->getFormattedName() . ' = '
            . implode(' ', $users) . ' '
            . implode(' ', $teams)
            . ($newLine ? PHP_EOL : '');
    }
}
