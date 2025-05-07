<?php
declare(strict_types=1);

namespace App\Identifier\Resolver;

use ArrayAccess;
use Authentication\Identifier\Resolver\ResolverInterface;
use Cake\Core\Configure;
use Cake\Datasource\FactoryLocator;
use Cake\Log\Log;

class TokenResolver implements ResolverInterface
{
    /**
     * Returns identity with given conditions.
     *
     * @param array $conditions Find conditions.
     * @param string $type Condition type. Can be `AND` or `OR`.
     * @return \ArrayAccess|array|null
     */
    public function find(array $conditions, string $type = self::TYPE_AND): ArrayAccess|array|null 
    {
        if (array_key_exists('token', $conditions)) {
            $token = $conditions['token'];
            
            try {
                // Process the token - strip Bearer prefix if present
                if (strpos($token, 'Bearer ') === 0) {
                    $token = substr($token, 7);
                }
                
                // Get Users table
                $usersTable = FactoryLocator::get('Table')->get('Users');
                $userId = $usersTable->getUserIdFromAuthToken($token);
                
                if ($userId) {
                    return $usersTable->get($userId, [
                        'contain' => ['Persone']
                    ]);
                }
                
                // Try alternative methods if JWT token fails
                // Check for a database token (if you store tokens in the users table)
                try {
                    $user = $usersTable->find()
                        ->where(['api_token' => $token, 'active' => true])
                        ->contain(['Persone'])
                        ->first();
                        
                    if ($user) {
                        return $user;
                    }
                } catch (\Exception $e) {
                    Log::debug('Database token lookup error: ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                Log::debug('Token authentication error: ' . $e->getMessage());
            }
        }
        
        return null;
    }
}
