/* a. Выбрать имена (name) всех клиентов, которые не делали заказы в последние 7 дней. */
SELECT c.name
FROM orders o
         JOIN clients c ON o.customer_id = c.id
WHERE order_date <= ADDDATE(current_date(), -7);

/* b. Выбрать имена (name) 5 клиентов, которые сделали больше всего заказов в магазине. */
SELECT c.name
FROM clients c, orders o
WHERE c.id=o.customer_id
GROUP BY c.id
ORDER BY COUNT(*) DESC
    LIMIT 5

/* c. Выбрать имена (name) 10 клиентов, которые сделали заказы на наибольшую сумму. */
SELECT c.name, SUM(m.price) as price
FROM clients as c
         INNER JOIN orders o ON o.customer_id = c.id
         INNER JOIN merchandise m ON o.item_id = m.id
GROUP BY c.name
ORDER BY price DESC
    LIMIT 10

/* d. Выбрать имена (name) всех товаров, по которым не было доставленных заказов (со статусом “complete”). */
SELECT m.name
FROM merchandise as m
         INNER JOIN orders o ON o.item_id = m.id
WHERE o.status = 'complete'
GROUP BY m.name

/**
  Дополнительные индексы (добавляем потому, что...)
  - В запросах присутсвуют выражения WHERE
  - Делаем группировку
  - Используем объединение таблиц
 */
ALTER TABLE `orders` ADD INDEX `item_idx` (`item_id`);
ALTER TABLE `orders` ADD INDEX `customer_idx` (`customer_id`);
ALTER TABLE `orders` ADD INDEX `status_idx` (`status`);