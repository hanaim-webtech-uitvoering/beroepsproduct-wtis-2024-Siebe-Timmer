
IF OBJECT_ID('User', 'U') IS NOT NULL
    DROP TABLE [User];

IF OBJECT_ID('Product_Type', 'U') IS NOT NULL
    DROP TABLE ProductType;

IF OBJECT_ID('Ingredient', 'U') IS NOT NULL
    DROP TABLE Ingredient;

IF OBJECT_ID('Product', 'U') IS NOT NULL
    DROP TABLE Product;

IF OBJECT_ID('Product_Ingredient', 'U') IS NOT NULL
    DROP TABLE Product_Ingredient;

IF OBJECT_ID('Pizza_Order_Product', 'U') IS NOT NULL
    DROP TABLE Pizza_Order_Product;

IF OBJECT_ID('Pizza_Order', 'U') IS NOT NULL
    DROP TABLE Pizza_Order;


-- Create User table
CREATE TABLE [User] (
  [username] NVARCHAR(255) PRIMARY KEY,
  [password] NVARCHAR(255) NOT NULL,
  [first_name] NVARCHAR(255) NOT NULL,
  [last_name] NVARCHAR(255) NOT NULL,
  [address] NVARCHAR(255),
  [role] NVARCHAR(50) NOT NULL
);

-- Create ProductType table
CREATE TABLE [Product_Type] (
  [type_id] INT PRIMARY KEY NOT NULL,
  [name] NVARCHAR(255) UNIQUE
);

-- Create Ingredient table
CREATE TABLE [Ingredient] (
  [ingredient_id] INT PRIMARY KEY NOT NULL,
  [name] NVARCHAR(255) UNIQUE
);

-- Create Product table
CREATE TABLE [Product] (
  [product_id] INT PRIMARY KEY NOT NULL,
  [name] NVARCHAR(255) UNIQUE,
  [price] DECIMAL(10,2) NOT NULL,
  [type_id] INT NOT NULL
);

-- Create Product_Ingredient table
CREATE TABLE [Product_Ingredient] (
  [product_id] INT,
  [ingredient_id] INT,
  PRIMARY KEY ([product_id], [ingredient_id])
);

CREATE TABLE [Status](
  [status_id] INT PRIMARY KEY NOT NULL,
  [status_name] NVARCHAR(255) UNIQUE 
)

CREATE TABLE [Pizza_Order] (
  [order_id] INT PRIMARY KEY IDENTITY(1, 1),
  [client_username] NVARCHAR(255),
  [client_name] NVARCHAR(255) NOT NULL,
  [personnel_username] NVARCHAR(255) NOT NULL,
  [datetime] DATETIME NOT NULL,
  [status_id] INT,
  [address] NVARCHAR(255)
)

-- Create Pizza_Order_Product table
CREATE TABLE [Pizza_Order_Product] (
  [order_id] INT NOT NULL,
  [product_id] INT NOT NULL,
  [quantity] INT NOT NULL,
  PRIMARY KEY ([order_id], [product_id])
);

-- -- Add foreign key constraints
ALTER TABLE [Product] ADD FOREIGN KEY ([type_id]) REFERENCES [Product_Type] ([type_id]);
ALTER TABLE [Product_Ingredient] ADD FOREIGN KEY ([product_id]) REFERENCES [Product] ([product_id]);
ALTER TABLE [Product_Ingredient] ADD FOREIGN KEY ([ingredient_id]) REFERENCES [Ingredient] ([ingredient_id]);
ALTER TABLE [Pizza_Order] ADD FOREIGN KEY ([client_username]) REFERENCES [User] ([username]);
ALTER TABLE [Pizza_Order] ADD FOREIGN KEY ([personnel_username]) REFERENCES [User] ([username]);
ALTER TABLE [Pizza_Order] ADD FOREIGN KEY ([status_id]) REFERENCES [Status] ([status_id]);
ALTER TABLE [Pizza_Order_Product] ADD FOREIGN KEY ([order_id]) REFERENCES [Pizza_Order] ([order_id]);
ALTER TABLE [Pizza_Order_Product] ADD FOREIGN KEY ([product_id]) REFERENCES [Product] ([product_id]);

-- -- Insert statements for 20 users with realistic names
INSERT INTO [User] (username, [password], first_name, last_name, [role]) VALUES
('jdoe', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'John', 'Doe', 'Client'),
('mvermeer', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Maria', 'Vermeer', 'Client'),
('rdeboer', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Rik', 'de Boer', 'Personnel'),
('sbakker', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Sophie', 'Bakker', 'Personnel'),
('fholwerda', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Fenna', 'Holwerda', 'Client'),
('kdijkstra', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Klaas', 'Dijkstra', 'Client'),
('lheineken', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Lucas', 'Heineken', 'Personnel'),
('mvandam', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Mila', 'van Dam', 'Personnel'),
('gkoolstra', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Gert', 'Koolstra', 'Client'),
('evisscher', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Emma', 'Visscher', 'Client'),
('tjanssen', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Tom', 'Janssen', 'Personnel'),
('abrouwer', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Anna', 'Brouwer', 'Personnel'),
('wbos', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Willem', 'Bos', 'Client'),
('tvandermeer', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Tessa', 'van der Meer', 'Client'),
('rkramer', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Rob', 'Kramer', 'Personnel'),
('mnijland', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Maud', 'Nijland', 'Personnel'),
('dschouten', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'David', 'Schouten', 'Client'),
('hdeleeuw', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Hanna', 'de Leeuw', 'Client'),
('pvanveen', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Peter', 'van Veen', 'Personnel'),
('adekhane', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Ahmed', 'Dekhane', 'Client'), 
('mbouaziz', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Mouna', 'Bouaziz', 'Client'), 
('tbayrak', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Tarik', 'Bayrak', 'Personnel'), 
('ayildiz', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Aylin', 'Yildiz', 'Personnel'), 
('rnarsingh', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Rajesh', 'Narsingh', 'Client'), 
('sdurga', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Shanti', 'Durga', 'Client'), 
('mkassem', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Mohammed', 'Kassem', 'Personnel'), 
('lsaleh', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Lina', 'Saleh', 'Personnel'), 
('aghebre', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Amanuel', 'Ghebre', 'Client'), 
('mtsega', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Miriam', 'Tsega', 'Client'), 
('pkowalski', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Piotr', 'Kowalski', 'Personnel'), 
('aivanov', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Alexei', 'Ivanov', 'Personnel'), 
('mkarimi', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Mina', 'Karimi', 'Client'), 
('hradman', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Hassan', 'Radman', 'Client'), 
('lbaloyi', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Lerato', 'Baloyi', 'Personnel'), 
('dpetrov', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Dmitri', 'Petrov', 'Personnel'), 
('ibrahimovic', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Ismail', 'Brahimovic', 'Client'), 
('snovak', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Sanja', 'Novak', 'Client'), 
('yabebe', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Yonas', 'Abebe', 'Personnel'), 
('ngebre', '$2y$10$MjSXR9T9UOuozLIxHzvr3uaxhUjERjn3d2qTHiqEvfCtOCHcFZSGK', 'Nardos', 'Gebre', 'Personnel'); 

-- Insert statements for product types
INSERT INTO Product_Type (type_id, [name]) VALUES
(1, 'Pizza'),
(2, 'Maaltijd'),
(3, 'Specerij'),
(4, 'Voorgerecht'),
(5, 'Drank');

-- Insert statements for ingredients
INSERT INTO Ingredient (ingredient_id, [name]) VALUES
(1, 'Tomaat'),
(2, 'Kaas'),
(3, 'Pepperoni'),
(4, 'Champignon'),
(5, 'Ui'),
(6, 'Sla'),
(7, 'Spek'),
(8, 'Saus');

-- Insert statements for products
INSERT INTO Product (product_id, [name], price, type_id) VALUES
(1, 'Margherita Pizza', 9.99, 1),
(2, 'Pepperoni Pizza', 11.99, 1),
(3, 'Vegetarische Pizza', 10.99, 1),
(4, 'Hawaiian Pizza', 12.99, 1),
(5, 'Combinatiemaaltijd', 15.99, 2),
(6, 'Knoflookbrood', 4.99, 4),
(7, 'Coca Cola', 2.49, 5),
(8, 'Sprite', 2.49, 5);

-- Insert statements for product-ingredient relationships
INSERT INTO Product_Ingredient (product_id, ingredient_id) VALUES
(1, 1), -- Margherita Pizza met Tomaat
(1, 2), -- Margherita Pizza met Kaas
(2, 1), -- Pepperoni Pizza met Tomaat
(2, 2), -- Pepperoni Pizza met Kaas
(2, 3), -- Pepperoni Pizza met Pepperoni
(3, 1), -- Vegetarische Pizza met Tomaat
(3, 2), -- Vegetarische Pizza met Kaas
(3, 4), -- Vegetarische Pizza met Champignon
(3, 5), -- Vegetarische Pizza met Ui
(4, 1), -- Hawaiian Pizza met Tomaat
(4, 2), -- Hawaiian Pizza met Kaas
(4, 3), -- Hawaiian Pizza met Pepperoni
(4, 5), -- Hawaiian Pizza met Ui
(4, 6), -- Hawaiian Pizza met Sla
(4, 7), -- Hawaiian Pizza met Spek
(4, 8), -- Hawaiian Pizza met Saus
(5, 1), -- Combinatiemaaltijd met Tomaat
(5, 2), -- Combinatiemaaltijd met Kaas
(5, 3), -- Combinatiemaaltijd met Pepperoni
(5, 4), -- Combinatiemaaltijd met Champignon
(5, 5), -- Combinatiemaaltijd met Ui
(5, 6), -- Combinatiemaaltijd met Sla
(5, 7), -- Combinatiemaaltijd met Spek
(5, 8); -- Combinatiemaaltijd met Saus

INSERT INTO [Status] (status_id, status_name) VALUES
(1, 'Ontvangen'),
(2, 'Wordt bereid'),
(3, 'Onderweg'),
(4, 'Geleverd');

-- Insert statements for pizza orders
INSERT INTO [Pizza_Order] (client_username, client_name, personnel_username, datetime, status_id, address) VALUES
('jdoe', 'John Doe', 'rdeboer', '2024-06-12 18:45:00', 1, 'Bakkerstraat 1, 6811EG, Arnhem'),
('mvermeer', 'Maria Vermeer', 'sbakker', '2024-06-12 19:00:00', 2, 'Jansplein 2, 6811GD, Arnhem'),
('fholwerda', 'Fenna Holwerda', 'lheineken', '2024-06-12 19:15:00', 1, 'Willemsplein 3, 6811KD, Arnhem'),
('kdijkstra', 'Klaas Dijkstra', 'mvandam', '2024-06-12 19:30:00', 2, 'Kerkstraat 4, 6811DW, Arnhem'),
('gkoolstra', 'Gert Koolstra', 'tjanssen', '2024-06-12 19:45:00', 3, 'Rijnkade 5, 6811HA, Arnhem'),
(NULL, 'Pieter Post', 'abrouwer', '2024-06-12 20:00:00', 1, 'Grote Markt 6, 6511KB, Nijmegen'),
(NULL, 'Anna Smits', 'wbos', '2024-06-12 20:15:00', 2, 'Sint Annastraat 7, 6524EZ, Nijmegen'),
(NULL, 'Bert van Dijk', 'tvandermeer', '2024-06-12 20:30:00', 3, 'Oranjesingel 8, 6511NV, Nijmegen'),
(NULL, 'Sara de Vries', 'rkramer', '2024-06-12 20:45:00', 1, 'Van Welderenstraat 9, 6511MS, Nijmegen'),
(NULL, 'Jan Jansen', 'mnijland', '2024-06-12 21:00:00', 2, 'Molenstraat 10, 6511HJ, Nijmegen'),
('dschouten', 'David Schouten', 'hdeleeuw', '2024-06-13 18:45:00', 1, 'Velperweg 11, 6814AD, Arnhem'),
('evisscher', 'Emma Visscher', 'pvanveen', '2024-06-13 19:00:00', 2, 'Geitenkamp 12, 6815AP, Arnhem'),
('adekhane', 'Ahmed Dekhane', 'ayildiz', '2024-06-13 19:15:00', 1, 'IJssellaan 13, 6821DJ, Arnhem'),
('wbos', 'Willem Bos', 'tbayrak', '2024-06-13 19:30:00', 2, 'Broekstraat 14, 6822GD, Arnhem'),
('mnijland', 'Maud Nijland', 'mkassem', '2024-06-13 19:45:00', 3, 'Apeldoornsestraat 15, 6828AJ, Arnhem'),
(NULL, 'Els de Boer', 'lsaleh', '2024-06-13 20:00:00', 1, 'Marialaan 16, 6541RP, Nijmegen'),
(NULL, 'Tom Bakker', 'pkowalski', '2024-06-13 20:15:00', 2, 'Smetiusstraat 17, 6511EP, Nijmegen'),
(NULL, 'Mila Janssen', 'aivanov', '2024-06-13 20:30:00', 3, 'Van Oldenbarneveltstraat 18, 6511PA, Nijmegen'),
(NULL, 'Lars de Groot', 'mkarimi', '2024-06-13 20:45:00', 1, 'Hertogstraat 19, 6511RV, Nijmegen'),
(NULL, 'Rik Kramer', 'dpetrov', '2024-06-13 21:00:00', 2, 'Van Schaeck Mathonsingel 20, 6512AP, Nijmegen'),
(NULL, 'Sophie van der Meer', 'ibrahimovic', '2024-06-14 18:45:00', 1, 'Lange Hezelstraat 21, 6511CM, Nijmegen'),
('rdeboer', 'Rik de Boer', 'sbakker', '2024-06-14 19:00:00', 2, 'Waalkade 22, 6511XR, Nijmegen'),
('mvermeer', 'Maria Vermeer', 'lheineken', '2024-06-14 19:15:00', 1, 'Sint Jacobslaan 23, 6533BT, Nijmegen'),
('jdoe', 'John Doe', 'mvandam', '2024-06-14 19:30:00', 2, 'Van Broeckhuysenstraat 24, 6511PE, Nijmegen'),
(NULL, 'Henk de Wit', 'gkoolstra', '2024-06-14 19:45:00', 3, 'Ziekerstraat 25, 6511LH, Nijmegen');

-- Insert statements for Pizza_Order_Product (dummy data for orders)
INSERT INTO Pizza_Order_Product (order_id, product_id, quantity) VALUES
(1, 1, 2),
(1, 7, 3),
(2, 2, 1),
(2, 8, 2),
(3, 3, 1),
(3, 4, 1),
(4, 5, 2),
(4, 6, 1),
(5, 2, 1),
(6, 1, 3),
(6, 4, 2),
(7, 5, 2),
(8, 6, 2),
(8, 8, 1),
(9, 2, 1),
(10, 4, 2),
(10, 7, 2),
(11, 1, 2),
(12, 3, 1),
(13, 4, 3),
(13, 7, 1),
(14, 5, 1),
(14, 6, 1),
(15, 2, 2),
(15, 8, 2),
(16, 1, 1),
(17, 3, 2),
(18, 4, 1),
(19, 5, 2),
(19, 6, 1),
(20, 2, 3),
(21, 4, 2),
(21, 7, 1),
(22, 1, 2),
(22, 6, 1),
(23, 2, 1),
(24, 3, 2),
(25, 4, 2),
(25, 8, 1);


-- pak de oudste en de nieuwste datum
declare @date_start datetime;
declare @date_end datetime;

select @date_start = MIN(datetime) from Pizza_Order;
select @date_end   = max(datetime) from Pizza_Order;

-- Bereken aan de hand van het verschil de middelste datum tussen start en eind
declare @diff int;
set @diff = DATEDIFF(minute, @date_start, @date_end);

declare @middle_date datetime;
set @middle_date = DATEADD(minute, @diff/2, @date_start);

-- Bereken verschil middelste datum met nu (huidig tijdstip)
set @diff = DATEDIFF(minute, @middle_date, GETDATE());

-- update vlucht vertrektijden en passagier inchecktijd
update Pizza_Order set [datetime] = DATEADD(minute, @diff, datetime);

go