UPDATE loyalty_manual_purchases
SET receipt_number = CONCAT('SIN-BOLETA-', id)
WHERE receipt_number IS NULL OR receipt_number = '';

ALTER TABLE loyalty_manual_purchases
    MODIFY receipt_number VARCHAR(50) NOT NULL;
