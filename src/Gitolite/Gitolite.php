<?php

namespace Gitolite;

/**
 * Gitolite Class
 *
 * Project:   gitolite-php
 * File:      src/Gitolite/Gitolite.php
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
class Gitolite
{
    const GITOLITE_CONF_FILE = 'gitolite.conf';
    const GITOLITE_CONF_DIR  = 'conf/';
    const GITOLITE_KEY_DIR   = 'keydir/';
    const GITOLITE_REPO_DIR  = 'conf/repos/';

    /**
     * @var string
     */
    protected $gitRemoteRepositoryURL = null;

    /**
     * @var string
     */
    protected $gitLocalRepositoryPath = null;

    /**
     * @var string
     */
    protected $gitEmail = null;

    /**
     * @var string
     */
    protected $gitUsername = null;

    /**
     * @var string
     */
    protected $gitServerName = null;

    /**
     * @var PHPGit_Repository
     */
    protected $gitoliteRepository = null;

    /**
     * @var User[]
     */
    protected $users = array();

    /**
     * @var Team[]
     */
    protected $teams = array();

    /**
     * @var Repo[]
     */
    protected $repos = array();

    /**
     * @var array
     */
    protected $log = array();

    /**
     * Set GitRemoteRepositoryURL
     *
     * @param string $gitRemoteRepositoryURL The remote repository URL
     *
     * @return self
     */
    public function setGitRemoteRepositoryURL($gitRemoteRepositoryURL)
    {
        $this->gitRemoteRepositoryURL = (string)$gitRemoteRepositoryURL;

        return $this;
    }

    /**
     * Get GitRemoteRepositoryURL
     *
     * @return string
     */
    public function getGitRemoteRepositoryURL()
    {
        return $this->gitRemoteRepositoryURL;
    }

    /**
     * Set GitLocalRepositoryPath
     *
     * @param string $gitLocalRepositoryPath The git local repository Path
     *
     * @return self
     */
    public function setGitLocalRepositoryPath($gitLocalRepositoryPath)
    {
        $this->gitLocalRepositoryPath = (string)$gitLocalRepositoryPath;

        return $this;
    }

    /**
     * Get GitLocalRepositoryPath
     *
     * @return string
     */
    public function getGitLocalRepositoryPath()
    {
        return $this->gitLocalRepositoryPath;
    }

    /**
     * Set GitEmail
     *
     * @param string $gitEmail The git user email
     *
     * @return self
     */
    public function setGitEmail($gitEmail)
    {
        $this->gitEmail = (string)$gitEmail;

        return $this;
    }

    /**
     * Get GitEmail
     *
     * @return string
     */
    public function getGitEmail()
    {
        return $this->gitEmail;
    }

    /**
     * Set GitUsername
     *
     * @param string $gitUsername The git user name
     *
     * @return self
     */
    public function setGitUsername($gitUsername)
    {
        $this->gitUsername = (string)$gitUsername;

        return $this;
    }

    /**
     * Get GitUsername
     *
     * @return string
     */
    public function getGitUsername()
    {
        return $this->gitUsername;
    }

    /**
     * Set GitServerName
     *
     * @param string $gitServerName The git server name
     *
     * @return self
     */
    public function setGitServerName($gitServerName)
    {
        $this->gitServerName = (string)$gitServerName;

        return $this;
    }

    /**
     * Get GitServerName
     *
     * @return string
     */
    public function getGitServerName()
    {
        return $this->gitServerName;
    }

    /**
     * Set Repos
     *
     * @param Repo[] $repos An array of repositories
     *
     * @return self
     */
    public function setRepos(array $repos)
    {
        $this->$repos = $repos;

        return $this;
    }

    /**
     * Get Repos
     *
     * @return Repo[]
     */
    public function getRepos()
    {
        return $this->repos;
    }

    /**
     * Get Repo
     *
     * @param string repo name
     *
     * @return Repo|false
     */
    public function getRepo($name)
    {
        return (isset($this->repos[$name])) ? $this->repos[$name] : false;
    }

    /**
     * Add repo
     *
     * @param Repo $repo A repository object
     *
     * @return self
     */
    public function addRepo(Repo $repo)
    {
        $name = $repo->getName();

        $this->repos[$name] = $repo;

        return $this;
    }

    /**
     * Delete repo
     *
     * @param string repo name
     */
    public function delRepo($name)
    {
        unset($this->repos[$name]);
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
        $this->users = array();

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
     * Get User
     *
     * @param string username
     *
     * @return User|false
     */
    public function getUser($username)
    {
        return (isset($this->users[$username])) ? $this->users[$username] : false;
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
        $username = $user->getUsername();

        $this->users[$username] = $user;

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
        $this->teams = array();

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
     * Get Team
     *
     * @param string team name
     *
     * @return Team|false
     */
    public function getTeam($name)
    {
        return (isset($this->teams[$name])) ? $this->teams[$name] : false;
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
        $name = $team->getName();

        $this->teams[$name] = $team;

        return $this;
    }

    /**
     * Import gitolite.conf
     *
     * @throws \Exception if team definition is invalid
     * @throws \Exception if team is not defined
     * @throws \Exception if repository definition is invalid
     * @throws \Exception if rule definition is invalid
     */
    public function import()
    {
        $specialTeamAll = new Team();
        $specialTeamAll->setName('all');
        $this->addTeam($specialTeamAll);


        $file = file($this->getGitLocalRepositoryPath() . DIRECTORY_SEPARATOR . self::GITOLITE_CONF_DIR . DIRECTORY_SEPARATOR . self::GITOLITE_CONF_FILE);

        foreach ($file as $line) {
            $line = trim($line);
            if ($line == '') {
                continue;
            }

            if (preg_match('/^[@]/', $line)) {
                $line_split = preg_split("/[=]+/", $line, 2);
                if (count($line_split) != 2) {
                    throw new \Exception(sprintf('Invalid team definition for the following line content: "%s".', $line));
                }

                $team_name = substr(trim($line_split[0]), 1);

                $team = new Team();
                $team->setName($team_name);

                $usr = preg_split("/[\s\t]+/", trim($line_split[1]));

                foreach ($usr as $u) {
                    // is team
                    if (substr($u, 0, 1) == '@') {
                        $u = substr($u, 1);
                        if (!isset($this->teams[$u])) {
                            throw new \Exception(sprintf('Undefined team named "%s".', $u));
                        }
                        $team->addTeam($this->teams[$u]);
                    } // is user
                    else {
                        if (isset($this->users[$u])) {
                            $team->addUser($this->users[$u]);
                        } else {
                            $user = new User();
                            $user->setUsername($u);
                            $key = $this->getGitLocalRepositoryPath(
                                ) . DIRECTORY_SEPARATOR . self::GITOLITE_KEY_DIR . DIRECTORY_SEPARATOR . $user->renderKeyFileName(
                                );
                            if (file_exists($key)) {
                                $user->addKey(file_get_contents($key));
                            }
                            $this->users[$u] = $user;
                            $team->addUser($user);
                        }
                    }
                }

                $this->teams[$team_name] = $team;
            } elseif (preg_match('/^repo/', $line)) {
                $line_split = preg_split("/[\s\t]+/", $line, 2);

                if (count($line_split) != 2) {
                    throw new \Exception(sprintf('Invalid repository definition for the following line content: "%s".', $line));
                }

                $repo = new Repo();
                $repo->setName(trim($line_split[1]));
            } elseif (preg_match('/^(C|R|RW|RW\+|\-|RWC|RW\+C|RWD|RW\+D|RWCD|RW\+CD|RWDC|RW\+DC)/', $line)) {
                $teams = [];
                $users = [];

                $line_split = preg_split("/[=]+/", $line, 2);
                if (count($line_split) != 2) {
                    throw new \Exception(sprintf('Invalid rule definition for the following line content: "%s".', $line));
                }

                $acl_split = preg_split("/[\s\t]+/", trim($line_split[0]), 2);
                $refexes   = (isset($acl_split[1])) ? $acl_split[1] : false;

                $acl = new Acl();
                $acl->setPermission($acl_split[0]);
                if ($refexes) {
                    $acl->setRefexes($refexes);
                }

                $usr = preg_split("/[\s\t]+/", trim($line_split[1]));
                foreach ($usr as $u) {
                    // is team
                    if (substr($u, 0, 1) == '@') {
                        $u = substr($u, 1);

                        if ($u !== 'all' && !isset($this->teams[$u])) {
                            throw new \Exception(sprintf('Undefined team named "%s".', $u));
                        }

                        if ($u === 'all' || ($u !== 'all' && isset($this->teams[$u]))) {
                            if ($u === 'all') {
                                $team = new Team();
                                $team->setName("all");
                                $acl->addTeam($team);
                            } else {
                                $acl->addTeam($this->teams[$u]);
                            }
                        }
                    } // is user
                    else {
                        if (!isset($this->users[$u])) {
                            $this->users[$u] = new User();
                            $this->users[$u]->setUsername($u);
                            $key = $this->getGitLocalRepositoryPath(
                                ) . DIRECTORY_SEPARATOR . self::GITOLITE_KEY_DIR . DIRECTORY_SEPARATOR . $this->users[$u]->renderKeyFileName(
                                );
                            if (file_exists($key)) {
                                $this->users[$u]->addKey(file_get_contents($key));
                            }
                        }

                        $acl->addUser($this->users[$u]);
                    }
                }

                $repo->addAcl($acl);
                $this->repos[$repo->getName()] = $repo;
            }
        }
    }

    /**
     * Get PHPGit_Repository
     *
     * @return PHPGit_Repository
     *
     * @throws \Exception if Git local repository path not defined
     * @throws \Exception if Git repository already exists
     * @throws \Exception if Git repository directory could not be created
     */
    protected function getGitoliteRepository()
    {
        if (null === $this->gitoliteRepository) {
            if (null === $this->getGitLocalRepositoryPath()) {
                throw new \Exception('Git local repository path not defined.');
            }

            try {
                $this->gitoliteRepository = new \PHPGit_Repository($this->getGitLocalRepositoryPath());
            } catch (\Exception $exc) {

                if (file_exists($this->getGitLocalRepositoryPath())) {
                    throw new \Exception("Directory {$this->getGitLocalRepositoryPath()} already exists, impossible to create repository.");
                } else {
                    if (mkdir($this->getGitLocalRepositoryPath(), 0775)) {
                        $this->gitoliteRepository = \PHPGit_Repository::create($this->getGitLocalRepositoryPath());
                    } else {
                        throw new \Exception('Impossible to create Directory informed in Git local repository (possibly).');
                    }
                }
            }
        }

        return $this->gitoliteRepository;
    }

    /**
     * Write a File down to disk
     *
     * @param string  $filename    The file to be write to disk
     * @param string  $data        The content to be write
     * @param boolean $checkChange Wheter check or not if data is changed
     *
     * @return bool
     *
     * @throws \Exception if file could not be written
     */
    protected function writeFile($filename, $data, $checkChange = true)
    {
        if (!file_exists($filename)) {
            if (!file_put_contents($filename, $data)) {
                throw new \Exception("Impossible to write file {$filename}.", 1);
            }
        } else {
            if (!$checkChange) {
                if (!file_put_contents($filename, $data)) {
                    throw new \Exception("Impossible to write file {$filename}.", 1);
                }
            } else {
                if ($data != file_get_contents($filename)) {
                    file_put_contents($filename, $data);
                }

                return true;
            }
        }
    }

    /**
     * Push configuration to Gitolite Server
     *
     * @return void
     */
    public function pushConfig()
    {
        $cmds[] = 'push gitoliteorigin master';

        $this->runGitCommand($cmds);
    }

    /**
     * Commits changes in configuration
     *
     * @return void
     */
    public function commitConfig($message = null)
    {
        $status = $this->runGitCommand('status');

        if( ! preg_match('/nothing to commit/', $status)) {
            $cmds[] = 'add --all .';

            // check for deleted keys
            preg_match_all('/deleted:[\s]+(.+)/', $status, $matches);

            if(isset($matches[1]) && is_array($matches[1])) {
                foreach($matches[1] as $v)
                {
                    $cmds[] = 'rm '.$v;
                }
            }

            if($message) {
                $cmds[] = 'commit -m "' . $message . ' From ' .
                $this->getGitServerName() . ' on ' .date('Y-m-d H:i:s') . '"';
            } else {
                $cmds[] = 'commit -m "Update configuration from ' .
                $this->getGitServerName() . ' on ' .date('Y-m-d H:i:s') . '"';
            }

            $this->runGitCommand($cmds);

            return true;
        }

        return false;
    }

    /**
     * Write full conf file to disk
     *
     * @return string
     */
    public function writeFullConfFile()
    {
        return $this->writeFile(
            $this->getGitLocalRepositoryPath() . DIRECTORY_SEPARATOR .
            self::GITOLITE_CONF_DIR . self::GITOLITE_CONF_FILE,
            $this->renderFullConfFile()
        );
    }

    /**
     * Write users keys to disk
     *
     * @return void
     */
    public function writeUsers()
    {
        foreach ($this->getUsers() as $user) {
            try {
                $keys =  $user->getKeys();
            } catch(\Exception $e) {
                $keys =  null;
            }

            if($keys && count($keys) > 0) {
                if(count($keys) == 1) {
                    $prefix = $user->renderKeyFileName();
                    $this->writeFile(
                        $this->getGitLocalRepositoryPath() . DIRECTORY_SEPARATOR .
                        self::GITOLITE_KEY_DIR .
                        $prefix,
                        $user->getFirstKey()
                    );
                } else {
                    foreach ($keys as $index => $key) {
                        $prefix = $user->renderKeyFileName($index > 0 ? '@' . $index : null);
                        $this->writeFile(
                            $this->getGitLocalRepositoryPath() . DIRECTORY_SEPARATOR .
                            self::GITOLITE_KEY_DIR .
                            $prefix,
                            $key
                        );
                    }
                }
            }
        }
    }

    /**
     * Write everything to the disk, commit and push
     *
     * @return void
     */
    public function writeAndPush()
    {
        $this->gitConfig();
        $this->writeFullConfFile();
        $this->writeUsers();

        if ($this->commitConfig()) {
            $this->pushConfig();
        }
    }

    /**
     * Return full conf file
     *
     * @return string
     */
    public function renderFullConfFile()
    {
        return $this->renderUserAndTeams() . $this->renderRepos();
    }

    /**
     * Return user and teams for conf file
     *
     * @return string
     */
    public function renderUserAndTeams()
    {
        $return = '';

        foreach ($this->getTeams() as $team) {
            if ($team->getName() !="all") {
                $return .= $team->render();
            }
        }

        return $return . PHP_EOL;
    }

    /**
     * Return repos for conf file
     *
     * @return string
     */
    public function renderRepos()
    {
        $return = '';

        foreach ($this->getRepos() as $repo) {
            $return .= $repo->render();
        }

        return $return;
    }

    /**
     * Configure the repository
     *
     * @return void
     */
    public function gitConfig()
    {
        $cmds[] = sprintf('config user.name "%s"', $this->getGitUsername());
        $cmds[] = sprintf('config user.email "%s"', $this->getGitEmail());

        $remotes = $this->runGitCommand('remote -v');

        $gitoliteRemote = sprintf('gitoliteorigin    %s', $this->getGitRemoteRepositoryURL());

        if( ! preg_match('/'.preg_quote($gitoliteRemote, '/').'/', $remotes))
        {
            if(preg_match('/gitoliteorigin/', $remotes)) $cmds[] = 'remote rm gitoliteorigin';
            $cmds[] = 'remote add '.$gitoliteRemote;
        }

        $cmds[] = 'pull gitoliteorigin master';

        $this->runGitCommand($cmds);
    }

    /**
     * Run git commands
     *
     * @param string|array $cmds A command or an array of commands
     *
     * @return string|false
     */
    protected function runGitCommand($cmds = '')
    {
        $output = null;
        if (!is_string($cmds) && !is_array($cmds)) {
            return false;
        }

        if (!is_array($cmds)) {
            $cmds = array($cmds);
        }

        foreach ($cmds as $cmd) {
            try {
                $date = date('Y-m-d H:i:s');
                $output = $this->getGitoliteRepository()->git($cmd);
                $this->log("$date COMMAND RUN: git $cmd");
                $this->log("$date OUTPUT : . $output");
            } catch (\GitRuntimeException $e) {
                $this->logError("$date GIT ERROR: " . $e->getMessage());
            } catch (\Exception $e) {
                $this->logError("$date ERROR: " . $e->getMessage());
            }
        }

        return $output;
    }

    /**
     * Log a message
     *
     * @param string $message The message to log
     *
     * @return void
     */
    protected function log($message)
    {
        $this->log['info'][] = $message;
    }

    /**
     * Log a error message
     *
     * @param string $message The message to log
     *
     * @return void
     */
    protected function logError($message)
    {
        $this->log['error'][] = $message;
    }

    /**
     * Get the log
     *
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Get the log as string
     *
     * @param string $type type of log message ("info" or "error", defaults to "info")
     *
     * @return string|false
     */
    public function getLogAsString($type = 'info')
    {
        if(!isset($this->log[$type])) {
            return false;
        }

        return implode(PHP_EOL, $this->log[$type]);
    }
}
