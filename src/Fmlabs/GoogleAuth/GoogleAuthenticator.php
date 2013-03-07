<?php

namespace Fmlabs\GoogleAuth;

use Nette;

class GoogleAuthenticator extends Nette\Object implements \Nette\Security\IAuthenticator
{

    /** @var GoogleAuth */
    protected $google;

    /**
     * database connection
     * @var Nette\Database\Connection
     */
    protected $connection;


    public function __construct(GoogleAuth $google, Nette\Database\Connection $connection)
    {
        $this->google = $google;
        $this->connection = $connection;
    }

    public function authenticate(array $data)
    {
        if (empty($data)) {
            throw new GoogleException('no-data');
        }
        $data = $data[0];

        $userTable = $this->connection->table('user');
        
        $user = $userTable->where('google_id', $data['id'])->fetch();

        // If user with this email exists, link the accounts
        if (!$user) {
            $user = $userTable->where('email', $data['email'])->fetch();
            if ($user) {
                $user->google_id = $data['id'];
                $user->update();
            }
        }

        // Otherwise, register new user
        if (!$user) {
            $user = $userTable->insert(array(
                'name' => $data['name'],
                'google_id' => $data['id'],
                'email' => $data['email'],
                'role' => 'googleuser',
                'created_date' => date('Y-m-d H:i:s')
            ));
            $user['id'] = $this->connection->lastInsertId();
        }

        return new \Nette\Security\Identity($user['id'], $user['role'], $user);
    }

}