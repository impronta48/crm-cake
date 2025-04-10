<?php

declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Identifier\Resolver;

use ArrayAccess;
use Authentication\Identifier\Resolver\ResolverInterface;
use Cake\Core\Configure;

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
        $api_key = Configure::read('App.key');
        if (array_key_exists('token', $conditions)) {
            if ($conditions['token'] == $api_key) {
                return ["1"];
            }
        }
        return null;
    }
}
