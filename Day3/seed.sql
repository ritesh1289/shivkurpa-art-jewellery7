USE shivkrupa_art_jewellery;
INSERT IGNORE INTO categories (name, slug, description) VALUES
('Necklaces', 'necklaces', 'Hand-finished statement necklaces.'),
('Earrings', 'earrings', 'Light-catching earrings for every occasion.');
INSERT IGNORE INTO products (category_id, name, slug, description, price, stock_quantity)
SELECT id, 'Mogra Pearl Necklace', 'mogra-pearl-necklace', 'A delicate pearl-inspired art jewellery necklace.', 2499.00, 12 FROM categories WHERE slug = 'necklaces';
INSERT IGNORE INTO products (category_id, name, slug, description, price, stock_quantity)
SELECT id, 'Kundan Bloom Earrings', 'kundan-bloom-earrings', 'Floral kundan-inspired earrings with a warm finish.', 1299.00, 20 FROM categories WHERE slug = 'earrings';
