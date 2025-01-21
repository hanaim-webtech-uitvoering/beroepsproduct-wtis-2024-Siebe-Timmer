IF OBJECT_ID('User', 'U') IS NOT NULL
    DROP TABLE [User];

IF OBJECT_ID('ProductType', 'U') IS NOT NULL
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


-- Create Role table (moet voor User)
CREATE TABLE [Role](
  [roleId] INT IDENTITY(1,1) PRIMARY KEY,
  [roleName] NVARCHAR(50) NOT NULL UNIQUE
);

-- Create Status table
CREATE TABLE [Status] (
  [statusId] INT IDENTITY(1,1) PRIMARY KEY,
  [statusName] NVARCHAR(255) NOT NULL UNIQUE
);

-- Create User table
CREATE TABLE [User] (
  [userId] INT IDENTITY PRIMARY KEY,
  [username] NVARCHAR(255) NOT NULL UNIQUE,
  [password] NVARCHAR(255) NOT NULL,
  [firstName] NVARCHAR(255) NOT NULL,
  [lastName] NVARCHAR(255) NOT NULL,
  [address] NVARCHAR(255),
  [roleId] INT NOT NULL,
  FOREIGN KEY ([roleId]) REFERENCES [Role]([roleId])
);

-- Create ProductType table
CREATE TABLE [ProductType] (
  [typeId] INT IDENTITY(1,1) PRIMARY KEY,
  [typeName] NVARCHAR(255) NOT NULL UNIQUE
);

-- Create Ingredient table
CREATE TABLE [Ingredient] (
  [ingredientId] INT IDENTITY(1,1) PRIMARY KEY,
  [ingredientName] NVARCHAR(255) NOT NULL UNIQUE
);

-- Create Product table
CREATE TABLE [Product] (
  [productId] INT IDENTITY(1,1) PRIMARY KEY,
  [productName] NVARCHAR(255) NOT NULL UNIQUE,
  [price] DECIMAL(10,2) NOT NULL,
  [typeId] INT NOT NULL,
  FOREIGN KEY ([typeId]) REFERENCES ProductType([typeId])
);

-- Create Product_Ingredient table
CREATE TABLE [Product_Ingredient] (
  [productId] INT NOT NULL,
  [ingredientId] INT NOT NULL,
  PRIMARY KEY ([productId], [ingredientId]),
  FOREIGN KEY ([productId]) REFERENCES Product([productId]),
  FOREIGN KEY ([ingredientId]) REFERENCES Ingredient([ingredientId])
);

-- Create Pizza_Order table
CREATE TABLE [Pizza_Order] (
  [orderId] INT IDENTITY(1,1) PRIMARY KEY,
  [userId] INT,
  [personnelId] INT NOT NULL,
  [orderDateTime] DATETIME NOT NULL,
  [statusId] INT NOT NULL,
  [deliveryAddress] NVARCHAR(255) NOT NULL,
  FOREIGN KEY ([userId]) REFERENCES [User]([userId]),
  FOREIGN KEY ([personnelId]) REFERENCES [User]([userId]),
  FOREIGN KEY ([statusId]) REFERENCES [Status]([statusId])
);

-- Create Pizza_Order_Product table
CREATE TABLE [Pizza_Order_Product] (
  [orderId] INT NOT NULL,
  [productId] INT NOT NULL,
  [quantity] INT NOT NULL,
  PRIMARY KEY ([orderId], [productId]),
  FOREIGN KEY ([orderId]) REFERENCES Pizza_Order([orderId]),
  FOREIGN KEY ([productId]) REFERENCES Product([productId])
);

-- Insert statements voor Role tabel
INSERT INTO [Role] (roleName) VALUES
('Klant'),
('Medewerker');

-- Insert statements voor Status tabel
INSERT INTO [Status] (statusName) VALUES
('In behandeling'),
('In bereiding'),
('Onderweg'),
('Afgeleverd');

-- Insert statements voor users
INSERT INTO [User] (username, [password], firstName, lastName, address, roleId) VALUES
('jdoe', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'John', 'Doe', 'Bakkerstraat 1, 6811EG, Arnhem', 1),
('mvermeer', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Maria', 'Vermeer', 'Jansplein 2, 6811GD, Arnhem', 1),
('rdeboer', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Rik', 'de Boer', 'Waalkade 22, 6511XR, Nijmegen', 2),
('sbakker', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Sophie', 'Bakker', 'Sint Annastraat 7, 6524EZ, Nijmegen', 2),
('fholwerda', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Fenna', 'Holwerda', 'Velperweg 11, 6814AD, Arnhem', 1),
('kdijkstra', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Klaas', 'Dijkstra', 'Kerkstraat 4, 6811DW, Arnhem', 1),
('lheineken', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Lucas', 'Heineken', 'Willemsplein 3, 6811KD, Arnhem', 2),
('mvandam', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Mila', 'van Dam', 'Oranjesingel 8, 6511NV, Nijmegen', 2),
('gkoolstra', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Gert', 'Koolstra', 'Ziekerstraat 25, 6511LH, Nijmegen', 1),
('evisscher', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Emma', 'Visscher', 'Geitenkamp 12, 6815AP, Arnhem', 1),
('tjanssen', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Tom', 'Janssen', 'Marialaan 16, 6541RP, Nijmegen', 2),
('abrouwer', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Anna', 'Brouwer', 'Smetiusstraat 17, 6511EP, Nijmegen', 2),
('wbos', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Willem', 'Bos', 'Sint Annastraat 7, 6524EZ, Nijmegen', 1),
('tvandermeer', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Tessa', 'van der Meer', 'Oranjesingel 8, 6511NV, Nijmegen', 1),
('rkramer', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Rob', 'Kramer', 'Van Welderenstraat 9, 6511MS, Nijmegen', 2),
('mnijland', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Maud', 'Nijland', 'Apeldoornsestraat 15, 6828AJ, Arnhem', 2),
('dschouten', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'David', 'Schouten', 'Velperweg 11, 6814AD, Arnhem', 1),
('hdeleeuw', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Hanna', 'de Leeuw', 'Velperweg 11, 6814AD, Arnhem', 1),
('pvanveen', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Peter', 'van Veen', 'Smetiusstraat 17, 6511EP, Nijmegen', 2),
('adekhane', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Ahmed', 'Dekhane', 'IJssellaan 13, 6821DJ, Arnhem', 1),
('mbouaziz', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Mouna', 'Bouaziz', 'Bakkerstraat 1, 6811EG, Arnhem', 1),
('tbayrak', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Tarik', 'Bayrak', 'Broekstraat 14, 6822GD, Arnhem', 2),
('ayildiz', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Aylin', 'Yildiz', 'IJssellaan 13, 6821DJ, Arnhem', 2),
('rnarsingh', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Rajesh', 'Narsingh', 'Bakkerstraat 1, 6811EG, Arnhem', 1),
('sdurga', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Shanti', 'Durga', 'Bakkerstraat 1, 6811EG, Arnhem', 1),
('mkassem', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Mohammed', 'Kassem', 'Apeldoornsestraat 15, 6828AJ, Arnhem', 2),
('lsaleh', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Lina', 'Saleh', 'Marialaan 16, 6541RP, Nijmegen', 1),
('aghebre', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Amanuel', 'Ghebre', 'Velperweg 11, 6814AD, Arnhem', 1),
('mtsega', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Miriam', 'Tsega', 'Bakkerstraat 1, 6811EG, Arnhem', 1),
('pkowalski', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Piotr', 'Kowalski', 'Smetiusstraat 17, 6511EP, Nijmegen', 2),
('aivanov', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Alexei', 'Ivanov', 'Van Oldenbarneveltstraat 18, 6511PA, Nijmegen', 2),
('mkarimi', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Mina', 'Karimi', 'Hertogstraat 19, 6511RV, Nijmegen', 1),
('hradman', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Hassan', 'Radman', 'Bakkerstraat 1, 6811EG, Arnhem', 1),
('lbaloyi', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Lerato', 'Baloyi', 'Waalkade 22, 6511XR, Nijmegen', 2),
('dpetrov', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Dmitri', 'Petrov', 'Van Schaeck Mathonsingel 20, 6512AP, Nijmegen', 2),
('ibrahimovic', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Ismail', 'Brahimovic', 'Lange Hezelstraat 21, 6511CM, Nijmegen', 1),
('snovak', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Sanja', 'Novak', 'Smetiusstraat 17, 6511EP, Nijmegen', 1),
('yabebe', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Yonas', 'Abebe', 'Van Welderenstraat 9, 6511MS, Nijmegen', 2),
('ngebre', '$2y$10$UfMd.QnzDD9tmlVJTfl9KeE0iDktouJus.iPO4vDT4iTV7JuRBGnK', 'Nardos', 'Gebre', 'Van Welderenstraat 9, 6511MS, Nijmegen', 2);

-- Insert statements voor product types
INSERT INTO ProductType (typeName) VALUES
('Pizza'),
('Maaltijd'),
('Specerij'),
('Voorgerecht'),
('Drank');

-- Insert statements voor ingredients
INSERT INTO Ingredient (ingredientName) VALUES
('Tomaat'),
('Kaas'),
('Pepperoni'),
('Champignon'),
('Ui'),
('Sla'),
('Spek'),
('Saus');

-- Insert statements voor products
INSERT INTO Product (productName, price, typeId) VALUES
('Margherita Pizza', 9.99, 1),
('Pepperoni Pizza', 11.99, 1),
('Vegetarische Pizza', 10.99, 1),
('Hawaiian Pizza', 12.99, 1),
('Combinatiemaaltijd', 15.99, 2),
('Knoflookbrood', 4.99, 4),
('Coca Cola', 2.49, 5),
('Sprite', 2.49, 5);

-- Insert statements voor product-ingredient relationships
INSERT INTO Product_Ingredient (productId, ingredientId) VALUES
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

-- Insert statements voor pizza orders
INSERT INTO [Pizza_Order] (userId, personnelId, orderDateTime, statusId, deliveryAddress) VALUES
(1, 3, '2024-06-12 18:45:00', 1, 'Bakkerstraat 1, 6811EG, Arnhem'),
(2, 4, '2024-06-12 19:00:00', 2, 'Jansplein 2, 6811GD, Arnhem'),
(3, 5, '2024-06-12 19:15:00', 1, 'Willemsplein 3, 6811KD, Arnhem'),
(4, 6, '2024-06-12 19:30:00', 2, 'Kerkstraat 4, 6811DW, Arnhem'),
(5, 7, '2024-06-12 19:45:00', 3, 'Rijnkade 5, 6811HA, Arnhem'),
(6, 8, '2024-06-12 20:00:00', 1, 'Grote Markt 6, 6511KB, Nijmegen'),
(7, 9, '2024-06-12 20:15:00', 2, 'Sint Annastraat 7, 6524EZ, Nijmegen'),
(8, 10, '2024-06-12 20:30:00', 3, 'Oranjesingel 8, 6511NV, Nijmegen'),
(9, 11, '2024-06-12 20:45:00', 1, 'Van Welderenstraat 9, 6511MS, Nijmegen'),
(10, 12, '2024-06-12 21:00:00', 2, 'Molenstraat 10, 6511HJ, Nijmegen'),
(11, 13, '2024-06-13 18:45:00', 1, 'Velperweg 11, 6814AD, Arnhem'),
(12, 14, '2024-06-13 19:00:00', 2, 'Geitenkamp 12, 6815AP, Arnhem'),
(13, 15, '2024-06-13 19:15:00', 1, 'IJssellaan 13, 6821DJ, Arnhem'),
(14, 16, '2024-06-13 19:30:00', 2, 'Broekstraat 14, 6822GD, Arnhem'),
(15, 17, '2024-06-13 19:45:00', 3, 'Apeldoornsestraat 15, 6828AJ, Arnhem'),
(16, 18, '2024-06-13 20:00:00', 1, 'Marialaan 16, 6541RP, Nijmegen'),
(17, 19, '2024-06-13 20:15:00', 2, 'Smetiusstraat 17, 6511EP, Nijmegen'),
(18, 20, '2024-06-13 20:30:00', 3, 'Van Oldenbarneveltstraat 18, 6511PA, Nijmegen'),
(19, 21, '2024-06-13 20:45:00', 1, 'Hertogstraat 19, 6511RV, Nijmegen'),
(20, 22, '2024-06-13 21:00:00', 2, 'Van Schaeck Mathonsingel 20, 6512AP, Nijmegen'),
(21, 23, '2024-06-14 18:45:00', 1, 'Lange Hezelstraat 21, 6511CM, Nijmegen'),
(22, 24, '2024-06-14 19:00:00', 2, 'Waalkade 22, 6511XR, Nijmegen'),
(23, 25, '2024-06-14 19:15:00', 1, 'Sint Jacobslaan 23, 6533BT, Nijmegen'),
(24, 26, '2024-06-14 19:30:00', 2, 'Van Broeckhuysenstraat 24, 6511PE, Nijmegen'),
(25, 27, '2024-06-14 19:45:00', 3, 'Ziekerstraat 25, 6511LH, Nijmegen');

-- Insert statements voor Pizza_Order_Product
INSERT INTO Pizza_Order_Product (orderId, productId, quantity) VALUES
(1, 1, 2), -- 2x Margherita Pizza voor order 1
(1, 7, 3), -- 3x Coca Cola voor order 1
(2, 2, 1), -- 1x Pepperoni Pizza voor order 2
(2, 8, 2), -- 2x Sprite voor order 2
(3, 3, 1), -- 1x Vegetarische Pizza voor order 3
(3, 4, 1), -- 1x Hawaiian Pizza voor order 3
(4, 5, 2), -- 2x Combinatiemaaltijd voor order 4
(4, 6, 1), -- 1x Knoflookbrood voor order 4
(5, 2, 1), -- 1x Pepperoni Pizza voor order 5
(6, 4, 2), -- 2x Hawaiian Pizza voor order 6
(6, 7, 2), -- 2x Coca Cola voor order 6
(7, 5, 2), -- 2x Combinatiemaaltijd voor order 7
(8, 6, 2), -- 2x Knoflookbrood voor order 8
(8, 8, 1), -- 1x Sprite voor order 8
(9, 2, 1), -- 1x Pepperoni Pizza voor order 9
(10, 4, 2), -- 2x Hawaiian Pizza voor order 10
(10, 7, 2), -- 2x Coca Cola voor order 10
(11, 1, 2), -- 2x Margherita Pizza voor order 11
(12, 3, 1), -- 1x Vegetarische Pizza voor order 12
(13, 4, 3), -- 3x Hawaiian Pizza voor order 13
(13, 7, 1), -- 1x Coca Cola voor order 13
(14, 5, 1), -- 1x Combinatiemaaltijd voor order 14
(14, 6, 1), -- 1x Knoflookbrood voor order 14
(15, 2, 2), -- 2x Pepperoni Pizza voor order 15
(15, 8, 2), -- 2x Sprite voor order 15
(16, 1, 1), -- 1x Margherita Pizza voor order 16
(17, 3, 2), -- 2x Vegetarische Pizza voor order 17
(18, 4, 1), -- 1x Hawaiian Pizza voor order 18
(19, 5, 2), -- 2x Combinatiemaaltijd voor order 19
(19, 6, 1), -- 1x Knoflookbrood voor order 19
(20, 2, 3), -- 3x Pepperoni Pizza voor order 20
(21, 4, 2), -- 2x Hawaiian Pizza voor order 21
(21, 7, 1), -- 1x Coca Cola voor order 21
(22, 1, 2), -- 2x Margherita Pizza voor order 22
(22, 6, 1), -- 1x Knoflookbrood voor order 22
(23, 2, 1), -- 1x Pepperoni Pizza voor order 23
(24, 3, 2), -- 2x Vegetarische Pizza voor order 24
(25, 4, 2), -- 2x Hawaiian Pizza voor order 25
(25, 8, 1); -- 1x Sprite voor order 25

-- pak de oudste en de nieuwste datum
declare @date_start datetime;
declare @date_end datetime;

select @date_start = MIN(orderDateTime) from Pizza_Order;
select @date_end   = max(orderDateTime) from Pizza_Order;

-- Bereken aan de hand van het verschil de middelste datum tussen start en eind
declare @diff int;
set @diff = DATEDIFF(minute, @date_start, @date_end);

declare @middle_date datetime;
set @middle_date = DATEADD(minute, @diff/2, @date_start);

-- Bereken verschil middelste datum met nu (huidig tijdstip)
set @diff = DATEDIFF(minute, @middle_date, GETDATE());

-- update vlucht vertrektijden en passagier inchecktijd
update Pizza_Order set orderDateTime = DATEADD(minute, @diff, orderDateTime);

go