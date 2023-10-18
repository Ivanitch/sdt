<?php

namespace App;

class Service
{
    protected string $runtimeFile;

    public function __construct(
        protected Query $query
    )
    {
        $this->runtimeFile = 'invalid.log';
    }

    public function import(array $data): void
    {
        $items = $this->validate($data);
        $sql = self::sqlImport();

        foreach ($items as $item):
            $item = $this->prepare($item);
            $args = [
                'item_id'     => $item['item_id'],
                'customer_id' => $item['customer_id'],
                'order_date'  => $item['order_date'],
                'status'      => $item['status'],
                'comment'     => $item['comment'],
            ];
            $this->query->sql($sql, $args);
        endforeach;
    }


    /**
     * @param string $string
     * @return array
     */
    protected function prepare(string $string): array
    {
        $array = explode(';', $string);;
        $result = [];
        $result['item_id']     = $array[0];
        $result['customer_id'] = $array[1];
        $result['order_date']  = $array[2];
        $result['status']      = (empty($array[3])) ? 'new': $array[3];
        $result['comment']     = $array[4] ?? '';

        unset($string, $array);

        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function validate(array $data): array
    {
        $result = [];
        foreach ($data as $item):
            if (Validator::validate($item)):
                $result[] = $item;
            else:
                Logger::log($item, $this->runtimeFile);
                continue;
            endif;
        endforeach;

        unset($data);

        return $result;
    }

    protected static function sqlImport(): string
    {
        return "INSERT INTO orders (`item_id`, `customer_id`, `order_date`, `status`, `comment`)
                VALUES (:item_id, :customer_id, :order_date, :status, :comment);";
    }
}
