ALTER TABLE loyalty_manual_purchases
    ADD COLUMN subcategory_id BIGINT UNSIGNED NULL AFTER client_id,
    ADD COLUMN quantity_lane INT UNSIGNED NOT NULL DEFAULT 1 AFTER total_hours,
    ADD COLUMN quantity_hours INT UNSIGNED NULL AFTER quantity_lane,
    ADD INDEX idx_loyalty_manual_subcategory (subcategory_id),
    ADD CONSTRAINT fk_loyalty_manual_subcategory
        FOREIGN KEY (subcategory_id)
        REFERENCES subcategories (id_subcategory)
        ON DELETE RESTRICT;

UPDATE loyalty_manual_purchases
SET quantity_hours = total_hours
WHERE quantity_hours IS NULL;

ALTER TABLE loyalty_manual_purchases
    MODIFY quantity_hours INT UNSIGNED NOT NULL;
