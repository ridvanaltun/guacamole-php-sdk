# PHP Guacamole SDK

> This is a very easy PHP SDK for Guacamole.

This project under devolopment, if you find a bug, feel free open an issue.

# Table of Contents

- [PHP Guacamole SDK](#php-guacamole-sdk)
- [Table of Contents](#table-of-contents)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
  - [Users](#users)
  - [Connections](#connections)
  - [Connection Groups](#connection-groups)
  - [User Groups](#user-groups)

# Features

- User management
- Connection management
- Connection group management
- User group management
- Based on Guacamole REST API, easy to use

# Installation

**This package is not installable via Composer 1.x, please make sure you upgrade to Composer 2+**

[Read more about our Composer 1.x deprecation policy.](https://blog.packagist.com/deprecating-composer-1-support/)

```bash
$ composer require ridvanaltun/guacamole
```

# Usage

Create a guacamole object before proceed.

```php
use ridvanaltun\Guacamole\Guacamole;

$host = 'localhost';
$username = 'admin';
$password = '123456';

$server = new Guacamole($host, $username, $password, [
    'timeout' => 5,
    'verify'  => false, // don't verify ssl
]);
```

## Users

```php
use ridvanaltun\Guacamole\User;

// Create an user object
$user = new User($server);

// List users
$users = $user->list();
var_dump($users);

// Details of users
$userDetails = $user->details('testuser');
var_dump($userDetails);

// Details of self
$self = $user->details();
var_dump($self);

// Details of user permissions
$userPermissions = $user->permissions('testuser');
var_dump($userPermissions);

// Details of user effective permissions
$userEffectivePermissions = $user->effectivePermissions('testuser');
var_dump($userEffectivePermissions);

// Details user groups of user
$userGroups = $user->userGroups('testuser');
var_dump($userGroups);

// Assign user group
$userGroups = ['1', '2'];
$user->assignUserGroups('testuser', $userGroups);

// Revoke user group
$userGroups = ['1', '2'];
$user->revokeUserGroups('testuser', $userGroups);

// Assign connections
$connections = ['1', '2'];
$user->assignConnections('testuser', $connections);

// Revoke connections
$connections = ['1', '2'];
$user->revokeConnections('testuser', $connections);

// Details of user history
$userHistory = $user->history('testuser');
var_dump($userHistory);

// Create an user
$attributes = [];
$newUser = $user->create('newuser', 'password', $attributes);
var_dump($newUser);

// Update user
$attributes = [];
$newUsername = 'foobar';
$password = 'xxxxx'; //empty was not update
$user->update('testuser',$password, $attributes, $newUsername);

// Delete User
$username = 'testuser';
$user->delete($username);
```

## Connections

```php
use ridvanaltun\Guacamole\Connection;

// Create a connection object
$connection = new Connection($server);

// List connections
$connections = $connection->list();
var_dump($connections);

// List active connections
$activeConnections = $connection->listActives();
var_dump($activeConnections);

// Details of connection
$connectionDetails = $connection->details('22');
var_dump($connectionDetails);

// Details of connection parameters
$connectionParameters = $connection->detailsParameters('22');
var_dump($connectionParameters);

// Details of connection history
$connectionHistory = $connection->history('20');
var_dump($connectionHistory);

// Details of connection sharing profiles
$connectionSharingProfiles = $connection->sharingProfiles('20');
var_dump($connectionSharingProfiles);

// List all sharing profiles
$sharingProfiles = $connection->listSharingProfiles();
var_dump($sharingProfiles);

// Kill connections
$sessions = ['1', '2', '3'];
$connection->batchKill($sessions);

// Create VNC
$parameters = [];
$attributes = [];
$vnc = $connection->createVnc('vnctest', '123456', 5901, $parameters, $attributes);
var_dump($vnc);

// Create SSH
$attributes = [];
$ssh = $connection->createSsh('sshtest', 'localhost', 'root', 'toor', 22, $attributes);
var_dump($ssh);

// Create RDP
$parameters = [];
$attributes = [];
$rdp = $connection->createRdp('rdptest', 'localhost', 3389, $parameters, $attributes);
var_dump($rdp);

// Create Telnet
$parameters = [];
$attributes = [];
$telnet = $connection->createTelnet('telnettest', 'localhost', 'user', 'password', 23, $parameters, $attributes);
var_dump($telnet);

// Create Kubernetes
$parameters = [];
$attributes = [];
$kubernetes = $connection->createKubernetes('kubernetestest', 'localhost', 8080, $parameters, $attributes);
var_dump($kubernetes);

// Update
$parameters = [];
$attributes = [];
$updatedConnection = $connection->update('15', 'vnc', 'vnctests', $parameters, $attributes);
var_dump($updatedConnection);

// Delete connection
$connectionId = 1;
$connection->delete($connectionId);
```

## Connection Groups

```php
use ridvanaltun\Guacamole\ConnectionGroup;

// Create a connection group object
$connectionGroup = new ConnectionGroup($server);

// List connection groups
$connectionGroups = $connectionGroup->list();
var_dump($connectionGroups);

// List connection group tree
$connectionGroupTree = $connectionGroup->listTree();
var_dump($connectionGroupTree);

// Details connection group
$connectionGroupDetails = $connectionGroup->details('1');
var_dump($connectionGroupDetails);

// Update connection group
$attributes = [];
$type = 'ORGANIZATIONAL';
$connectionGroup->update('1', 'newname', $type, $attributes);

// Create a connection group
$attributes = [];
$type = 'ORGANIZATIONAL';
$newConnectionGroup = $connectionGroup->create('newgroup', $type, $attributes);
var_dump($newConnectionGroup);

// Delete connection group
$connectionGroup->delete('1');
```

## User Groups

```php
use ridvanaltun\Guacamole\UserGroup;

// Create an user group object
$userGroup = new ConnectionGroup($server);

// List user groups
$userGroups = $userGroup->list();
var_dump($userGroups);

// Details of user group
$userGroupDetails = $userGroup->details('1');
var_dump($userGroupDetails);

// Add members to user group
$members = ['1', '2', '3'];
$userGroup->addMembers('1', $members);

// Add members of user groups to user group
$userGroups = ['1', '2', '3'];
$userGroup->addMembersOfUserGroups('4', $userGroups);

// Add user groups to user group
$userGroups = ['1', '2', '3'];
$userGroup->addUserGroups('4', $userGroups);

// Create an user group
$attributes = [];
$newUserGroup = $userGroup->create('1', $attributes);
var_dump($newUserGroup);

// Update user group
$attributes = [];
$newIdentifier = '2';
$userGroup->update('1', $attributes, $newIdentifier);

// Delete user group
$userGroup->delete('1');
```
