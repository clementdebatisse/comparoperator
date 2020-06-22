<?php


declare(strict_types=1);

namespace Models\Endpoints;

use Entities\Entity;

/**
 * @todo Break-up API into multiple files by endpoints.
 * @todo Consider using traits for endpoints and composing API by using endpoint
 *       traits.
 */
trait OperatorEndpoint
{
    /**
     * Get operators.
     * 
     * @api ComparOperatorAPI
     * 
     * @param  int $count  How many categories to return (default = 10).
     * @param  int $offset How many categories to skip   (default = 0)
     *                     Use for pagination.
     * 
     * @return \Entities\Operator[]
     */
    public function getOperators(int $count = 10, int $offset = 0): array
    {
        if ($count < 0) {
            $count = 10;
        }
        if ($offset < 0) {
            $offset = 0;
        }

        $rows = $this->execute(
            'comparoperator',
            'SELECT
                 `operator_id`,
                 `name`,
                 `website`,
                 `logo`,
                 `is_premium`
             FROM 
                 `operators`
             LIMIT ? OFFSET ?;',
            [$count, $offset]
        );

        return Entity::createEntities($rows, 'Operator');
    }
}