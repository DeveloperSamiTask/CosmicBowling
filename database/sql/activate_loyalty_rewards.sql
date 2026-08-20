UPDATE loyalty_reward_catalogs
SET
    name = '50% de descuento en una hora estándar',
    description = 'Aplicar en Wally el 50% de descuento sobre una hora estándar de bowling.',
    active = 1,
    updated_at = NOW()
WHERE milestone = 5;

UPDATE loyalty_reward_catalogs
SET
    name = 'Una hora estándar gratis',
    description = 'Aplicar en Wally una hora estándar gratis de bowling.',
    active = 1,
    updated_at = NOW()
WHERE milestone = 10;
